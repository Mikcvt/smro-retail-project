<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ReturnModel;
use App\Models\ReturnItemModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductVariantModel;

class ReturnController extends BaseController
{
    public function index(): string
    {
        $returnModel = new ReturnModel();

        $returns = $returnModel
            ->select('returns.*, sales.reference_no, users.name as processed_by')
            ->join('sales', 'sales.id = returns.sale_id', 'left')
            ->join('users', 'users.id = returns.user_id', 'left')
            ->orderBy('returns.id', 'DESC')
            ->paginate(10);

        return view('returns/index', [
            'returns' => $returns,
            'pager'   => $returnModel->pager,
        ]);
    }

    public function create(): string
    {
        return view('returns/create');
    }

    public function store()
    {
        $rules = [
            'reference_no' => 'required',
            'variant_ids'  => 'required',
            'quantities'   => 'required',
            'reason'       => 'required|min_length[5]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saleModel = new SaleModel();
        $sale = $saleModel->where('reference_no', $this->request->getPost('reference_no'))
                          ->where('status', 'completed')
                          ->first();

        if (!$sale) {
            return redirect()->back()->withInput()->with('error', 'Sale not found or not eligible for return.');
        }

        $variantIds = $this->request->getPost('variant_ids');
        $quantities = $this->request->getPost('quantities');
        $reasons    = $this->request->getPost('item_reasons') ?? [];

        $db = \Config\Database::connect();
        $db->transStart();

        $returnModel = new ReturnModel();
        $returnId = $returnModel->insert([
            'sale_id'    => $sale['id'],
            'user_id'    => session('user_id'),
            'reason'     => $this->request->getPost('reason'),
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $returnItemModel = new ReturnItemModel();
        $variantModel    = new ProductVariantModel();

        foreach ($variantIds as $i => $variantId) {
            $qty = (int) ($quantities[$i] ?? 0);
            if ($qty <= 0) {
                continue;
            }

            $returnItemModel->insert([
                'return_id'  => $returnId,
                'variant_id' => (int) $variantId,
                'quantity'   => $qty,
                'reason'     => $reasons[$i] ?? '',
            ]);

            // Restock immediately on submission
            $variantModel
                ->set('stock_quantity', 'stock_quantity + ' . $qty, false)
                ->where('id', (int) $variantId)
                ->update();
        }

        $saleModel->update($sale['id'], ['status' => 'returned']);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Return processing failed.');
        }

        return redirect()->to(site_url('returns'))->with('success', 'Return submitted successfully.');
    }

    public function approve(int $id)
    {
        if (!in_array(session('role'), ['superadmin', 'manager'], true)) {
            return redirect()->to(site_url('returns'))->with('error', 'Unauthorized.');
        }

        $returnModel = new ReturnModel();
        $return = $returnModel->find($id);

        if (!$return || $return['status'] !== 'pending') {
            return redirect()->to(site_url('returns'))->with('error', 'Return cannot be approved.');
        }

        $returnModel->update($id, ['status' => 'approved']);
        return redirect()->to(site_url('returns'))->with('success', 'Return approved.');
    }

    public function reject(int $id)
    {
        if (!in_array(session('role'), ['superadmin', 'manager'], true)) {
            return redirect()->to(site_url('returns'))->with('error', 'Unauthorized.');
        }

        $returnModel  = new ReturnModel();
        $return = $returnModel->find($id);

        if (!$return || $return['status'] !== 'pending') {
            return redirect()->to(site_url('returns'))->with('error', 'Return cannot be rejected.');
        }

        // Reverse the restock since return is rejected
        $db = \Config\Database::connect();
        $db->transStart();

        $returnItemModel = new ReturnItemModel();
        $variantModel    = new ProductVariantModel();

        $items = $returnItemModel->where('return_id', $id)->findAll();
        foreach ($items as $item) {
            $variantModel
                ->set('stock_quantity', 'stock_quantity - ' . (int) $item['quantity'], false)
                ->where('id', (int) $item['variant_id'])
                ->update();
        }

        $returnModel->update($id, ['status' => 'rejected']);

        // Restore sale status back to completed
        $saleModel = new SaleModel();
        $saleModel->update($return['sale_id'], ['status' => 'completed']);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->to(site_url('returns'))->with('error', 'Failed to reject return.');
        }

        return redirect()->to(site_url('returns'))->with('success', 'Return rejected and stock reversed.');
    }
}
