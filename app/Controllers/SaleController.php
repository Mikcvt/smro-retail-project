<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductVariantModel;
use App\Models\ProductModel;

class SaleController extends BaseController
{
    public function index(): string
    {
        $saleModel = new SaleModel();

        $sales = $saleModel
            ->select('sales.*, users.name as cashier_name')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->orderBy('sales.id', 'DESC')
            ->paginate(10);

        return view('sales/index', [
            'sales' => $sales,
            'pager' => $saleModel->pager,
        ]);
    }

    public function create(): string
    {
        $productModel = new ProductModel();
        $variantModel = new ProductVariantModel();

        $products = $productModel->where('is_active', 1)->findAll();
        foreach ($products as &$product) {
            $product['variants'] = $variantModel->where('product_id', $product['id'])->findAll();
        }

        return view('sales/create', ['products' => $products]);
    }

    public function store()
    {
        $rules = [
            'variants'   => 'required',
            'quantities' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Invalid sale data.');
        }

        $variantIds = $this->request->getPost('variants');
        $quantities = $this->request->getPost('quantities');

        if (empty($variantIds) || !is_array($variantIds)) {
            return redirect()->back()->withInput()->with('error', 'No items selected.');
        }

        $variantModel = new ProductVariantModel();
        $productModel = new ProductModel();
        $db = \Config\Database::connect();

        $db->transStart();

        $totalAmount = 0.0;
        $items = [];

        foreach ($variantIds as $i => $variantId) {
            $qty = (int) ($quantities[$i] ?? 0);
            if ($qty <= 0) {
                continue;
            }

            $variant = $variantModel->find((int) $variantId);
            if (!$variant || $variant['stock_quantity'] < $qty) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Insufficient stock for one or more items.');
            }

            // Get base_price from product
            $product   = $productModel->find($variant['product_id']);
            $unitPrice = (float) $product['base_price'] + (float) $variant['price_modifier'];
            $subtotal  = $unitPrice * $qty;
            $totalAmount += $subtotal;

            // Atomic stock deduction — no read-then-write race condition
            $variantModel
                ->set('stock_quantity', 'stock_quantity - ' . $qty, false)
                ->where('id', (int) $variantId)
                ->update();

            $items[] = [
                'variant_id' => (int) $variantId,
                'quantity'   => $qty,
                'unit_price' => $unitPrice,
                'subtotal'   => $subtotal,
            ];
        }

        if (empty($items)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'No valid items in sale.');
        }

        $referenceNo = 'SALE-' . strtoupper(bin2hex(random_bytes(4))) . '-' . date('Ymd');

        $saleModel = new SaleModel();
        $saleId = $saleModel->insert([
            'user_id'      => session('user_id'),
            'reference_no' => $referenceNo,
            'total_amount' => $totalAmount,
            'status'       => 'completed',
            'notes'        => $this->request->getPost('notes') ?? '',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        $saleItemModel = new SaleItemModel();
        foreach ($items as &$item) {
            $item['sale_id'] = $saleId;
        }
        $saleItemModel->insertBatch($items);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Transaction failed. Please try again.');
        }

        return redirect()->to(site_url('sales'))->with('success', "Sale {$referenceNo} recorded successfully.");
    }

    public function lookup()
    {
        $ref = $this->request->getGet('ref');
        $saleModel = new SaleModel();

        $sale = $saleModel
            ->where('reference_no', $ref)
            ->where('status', 'completed')
            ->first();

        if (!$sale) {
            return $this->response->setJSON([
                'status'  => 'error',
                'data'    => null,
                'message' => 'Sale not found or not eligible for return.',
            ]);
        }

        $saleItemModel = new SaleItemModel();
        $variantModel  = new ProductVariantModel();

        $rawItems = $saleItemModel->where('sale_id', $sale['id'])->findAll();
        $items = [];
        foreach ($rawItems as $item) {
            $variant = $variantModel->find($item['variant_id']);
            $items[] = [
                'variant_id' => $item['variant_id'],
                'sku'        => $variant['sku'] ?? '',
                'size'       => $variant['size'] ?? '',
                'color'      => $variant['color'] ?? '',
                'quantity'   => $item['quantity'],
            ];
        }

        $sale['items'] = $items;

        return $this->response->setJSON([
            'status'  => 'success',
            'data'    => $sale,
            'message' => 'OK',
        ]);
    }

    public function void(int $id)
    {
        if (!in_array(session('role'), ['superadmin', 'manager'], true)) {
            return redirect()->to(site_url('sales'))->with('error', 'Unauthorized.');
        }

        $saleModel = new SaleModel();
        $sale = $saleModel->find($id);

        if (!$sale || $sale['status'] !== 'completed') {
            return redirect()->to(site_url('sales'))->with('error', 'Sale cannot be voided.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $saleItemModel  = new SaleItemModel();
        $variantModel   = new ProductVariantModel();

        $items = $saleItemModel->where('sale_id', $id)->findAll();
        foreach ($items as $item) {
            $variantModel
                ->set('stock_quantity', 'stock_quantity + ' . (int) $item['quantity'], false)
                ->where('id', (int) $item['variant_id'])
                ->update();
        }

        $saleModel->update($id, ['status' => 'void']);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->to(site_url('sales'))->with('error', 'Failed to void sale.');
        }

        return redirect()->to(site_url('sales'))->with('success', 'Sale voided and stock restored.');
    }
}
