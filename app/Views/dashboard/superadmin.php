<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
use App\Models\SaleModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Models\ReturnModel;

$saleModel    = new SaleModel();
$productModel = new ProductModel();
$userModel    = new UserModel();
$returnModel  = new ReturnModel();

$totalProducts = $productModel->where('is_active', 1)->countAllResults();
$todayRevenue  = $saleModel->where('status', 'completed')->where('DATE(created_at)', date('Y-m-d'))->selectSum('total_amount')->first()['total_amount'] ?? 0;
$totalUsers    = $userModel->where('is_active', 1)->countAllResults();
$pendingReturns = $returnModel->where('status', 'pending')->countAllResults();

$recentSales = $saleModel
    ->select('sales.*, users.name as cashier_name')
    ->join('users', 'users.id = sales.user_id', 'left')
    ->orderBy('sales.id', 'DESC')
    ->limit(5)
    ->findAll();
?>

<!-- Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Products</div>
                <div class="fw-bold fs-3"><?= number_format($totalProducts) ?></div>
                <div class="small text-success mt-1"><i class="bi bi-box-seam me-1"></i>Active items</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Today</div>
                <div class="fw-bold fs-3">₱<?= number_format((float)$todayRevenue, 0) ?></div>
                <div class="small text-success mt-1"><i class="bi bi-graph-up me-1"></i>Revenue today</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Users</div>
                <div class="fw-bold fs-3"><?= number_format($totalUsers) ?></div>
                <div class="small text-info mt-1"><i class="bi bi-people me-1"></i>Active accounts</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Alerts</div>
                <div class="fw-bold fs-3 <?= $pendingReturns > 0 ? 'text-warning' : '' ?>"><?= $pendingReturns ?></div>
                <div class="small text-warning mt-1"><i class="bi bi-exclamation-triangle me-1"></i>Pending returns</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Sales -->
    <div class="col-lg-7">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Recent Sales (Today)</h6>
                <a href="<?= site_url('sales') ?>" class="small text-primary">View all →</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 small">
                        <thead class="border-bottom border-secondary">
                            <tr>
                                <th class="px-3 py-2">Ref #</th>
                                <th>Cashier</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentSales)): ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No sales yet today.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentSales as $sale): ?>
                                <tr>
                                    <td class="px-3"><code class="text-primary"><?= esc($sale['reference_no']) ?></code></td>
                                    <td><?= esc($sale['cashier_name'] ?? '—') ?></td>
                                    <td>₱<?= number_format((float)$sale['total_amount'], 2) ?></td>
                                    <td>
                                        <?php $sc = match($sale['status']) { 'completed' => 'success', 'returned' => 'warning', default => 'danger' }; ?>
                                        <span class="badge bg-<?= $sc ?>"><?= ucfirst($sale['status']) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="col-lg-5">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header border-secondary"><h6 class="mb-0">Quick Links</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="<?= site_url('sales/create') ?>" class="card bg-dark border-secondary text-decoration-none h-100 d-block p-3 text-center" style="border-color:rgba(99,102,241,0.3)!important">
                            <i class="bi bi-cart-plus fs-4 text-primary d-block mb-2"></i>
                            <div class="small fw-semibold">New Sale</div>
                            <div class="text-muted" style="font-size:.75rem">Record a transaction</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('products/new') ?>" class="card bg-dark border-secondary text-decoration-none h-100 d-block p-3 text-center" style="border-color:rgba(16,185,129,0.3)!important">
                            <i class="bi bi-plus-circle fs-4 text-success d-block mb-2"></i>
                            <div class="small fw-semibold">Add Product</div>
                            <div class="text-muted" style="font-size:.75rem">New inventory item</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('users') ?>" class="card bg-dark border-secondary text-decoration-none h-100 d-block p-3 text-center" style="border-color:rgba(245,158,11,0.3)!important">
                            <i class="bi bi-people fs-4 text-warning d-block mb-2"></i>
                            <div class="small fw-semibold">Manage Users</div>
                            <div class="text-muted" style="font-size:.75rem">Roles & accounts</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('reports') ?>" class="card bg-dark border-secondary text-decoration-none h-100 d-block p-3 text-center" style="border-color:rgba(59,130,246,0.3)!important">
                            <i class="bi bi-bar-chart-line fs-4 text-info d-block mb-2"></i>
                            <div class="small fw-semibold">View Reports</div>
                            <div class="text-muted" style="font-size:.75rem">Sales analytics</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('settings') ?>" class="card bg-dark border-secondary text-decoration-none h-100 d-block p-3 text-center">
                            <i class="bi bi-gear fs-4 text-secondary d-block mb-2"></i>
                            <div class="small fw-semibold">Settings</div>
                            <div class="text-muted" style="font-size:.75rem">System config</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('reports/export') ?>" class="card bg-dark border-secondary text-decoration-none h-100 d-block p-3 text-center">
                            <i class="bi bi-download fs-4 text-secondary d-block mb-2"></i>
                            <div class="small fw-semibold">Export Data</div>
                            <div class="text-muted" style="font-size:.75rem">CSV / PDF</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
