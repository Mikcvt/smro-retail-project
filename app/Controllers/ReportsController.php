<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\ProductModel;
use App\Models\ReturnModel;
use App\Models\UserModel;

class ReportsController extends BaseController
{
    private function requireManagerOrAbove(): void
    {
        if (!in_array(session('role'), ['superadmin', 'manager'], true)) {
            redirect()->to(site_url('dashboard'))->with('error', 'Unauthorized.')->send();
            exit;
        }
    }

    public function index(): string
    {
        $this->requireManagerOrAbove();

        $saleModel    = new SaleModel();
        $productModel = new ProductModel();
        $returnModel  = new ReturnModel();

        $totalSales    = $saleModel->where('status', 'completed')->countAllResults();
        $totalRevenue  = $saleModel->where('status', 'completed')->selectSum('total_amount')->first()['total_amount'] ?? 0;
        $totalReturns  = $returnModel->countAllResults();
        $totalProducts = $productModel->where('is_active', 1)->countAllResults();

        $recentSales = $saleModel
            ->select('sales.*, users.name as cashier_name')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->orderBy('sales.id', 'DESC')
            ->limit(10)
            ->findAll();

        $monthlySales = $saleModel
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as revenue, COUNT(*) as count")
            ->where('status', 'completed')
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month', 'DESC')
            ->limit(6)
            ->findAll();

        return view('reports/index', compact('totalSales', 'totalRevenue', 'totalReturns', 'totalProducts', 'recentSales', 'monthlySales'));
    }

    public function export(): string
    {
        $this->requireManagerOrAbove();
        return view('reports/export');
    }
}
