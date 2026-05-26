<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
use App\Models\SaleModel;
use App\Models\ReturnModel;

$saleModel   = new SaleModel();
$returnModel = new ReturnModel();

$todaySales    = $saleModel->where('status', 'completed')->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
$todayRevenue  = $saleModel->where('status', 'completed')->where('DATE(created_at)', date('Y-m-d'))->selectSum('total_amount')->first()['total_amount'] ?? 0;
$pendingReturns = $returnModel->where('status', 'pending')->countAllResults();
$monthRevenue  = $saleModel->where('status', 'completed')->where('MONTH(created_at)', date('m'))->where('YEAR(created_at)', date('Y'))->selectSum('total_amount')->first()['total_amount'] ?? 0;

$recentSales = $saleModel
    ->select('sales.*, users.name as cashier_name')
    ->join('users', 'users.id = sales.user_id', 'left')
    ->orderBy('sales.id', 'DESC')
    ->limit(8)
    ->findAll();
?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Today's Sales</div>
                <div class="fw-bold fs-3">₱<?= number_format((float)$todayRevenue, 0) ?></div>
                <div class="small text-success mt-1"><i class="bi bi-graph-up me-1"></i><?= $todaySales ?> transactions</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Pending Returns</div>
                <div class="fw-bold fs-3 <?= $pendingReturns > 0 ? 'text-warning' : '' ?>"><?= $pendingReturns ?></div>
                <div class="small text-warning mt-1"><i class="bi bi-clock me-1"></i>Awaiting review</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Revenue This Month</div>
                <div class="fw-bold fs-3">₱<?= number_format((float)$monthRevenue, 0) ?></div>
                <div class="small text-info mt-1"><i class="bi bi-calendar me-1"></i><?= date('F Y') ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card bg-dark border-secondary">
    <div class="card-header border-secondary d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Recent Sales</h6>
        <a href="<?= site_url('sales') ?>" class="small text-primary">View all →</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 small">
                <thead class="border-bottom border-secondary">
                    <tr>
                        <th class="px-3 py-2">ID</th>
                        <th>Cashier</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentSales)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No sales yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentSales as $sale): ?>
                        <tr>
                            <td class="px-3"><code class="text-primary">#<?= $sale['id'] ?></code></td>
                            <td><?= esc($sale['cashier_name'] ?? '—') ?></td>
                            <td>₱<?= number_format((float)$sale['total_amount'], 2) ?></td>
                            <td>
                                <?php $sc = match($sale['status']) { 'completed' => 'success', 'returned' => 'warning', default => 'danger' }; ?>
                                <span class="badge bg-<?= $sc ?>"><?= ucfirst($sale['status']) ?></span>
                            </td>
                            <td class="text-muted"><?= date('H:i', strtotime($sale['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
