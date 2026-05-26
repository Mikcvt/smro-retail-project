<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
use App\Models\SaleModel;

$saleModel    = new SaleModel();
$firstname    = session('firstname') ?: session('name');
$todaySales   = $saleModel->where('user_id', session('user_id'))->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
$todayRevenue = $saleModel->where('user_id', session('user_id'))->where('status', 'completed')->where('DATE(created_at)', date('Y-m-d'))->selectSum('total_amount')->first()['total_amount'] ?? 0;

$myTransactions = $saleModel
    ->select('sales.*, users.name as cashier_name')
    ->join('users', 'users.id = sales.user_id', 'left')
    ->where('sales.user_id', session('user_id'))
    ->where('DATE(sales.created_at)', date('Y-m-d'))
    ->orderBy('sales.id', 'DESC')
    ->findAll();

$hour = (int) date('H');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
?>

<h4 class="fw-bold mb-1"><?= $greeting ?>, <?= esc($firstname) ?>! 👋</h4>
<p class="text-muted mb-4">Here's your activity for today, <?= date('l, d F Y') ?>.</p>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-dark border-secondary">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(99,102,241,0.15)"><i class="bi bi-receipt fs-4 text-primary"></i></div>
                <div>
                    <div class="fw-bold fs-3"><?= $todaySales ?></div>
                    <div class="text-muted small">Your sales today</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark border-secondary">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(16,185,129,0.15)"><i class="bi bi-currency-dollar fs-4 text-success"></i></div>
                <div>
                    <div class="fw-bold fs-3">₱<?= number_format((float)$todayRevenue, 0) ?></div>
                    <div class="text-muted small">Your revenue today</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <a href="<?= site_url('sales/create') ?>" class="card bg-dark border-secondary text-decoration-none d-block h-100" style="border-color:rgba(99,102,241,0.4)!important">
            <div class="card-body d-flex align-items-center justify-content-center gap-2 py-4">
                <i class="bi bi-cart-plus fs-4 text-primary"></i>
                <span class="fw-semibold">New Sale</span>
            </div>
        </a>
    </div>
</div>

<div class="card bg-dark border-secondary">
    <div class="card-header border-secondary"><h6 class="mb-0">Your transactions today</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 small">
                <thead class="border-bottom border-secondary">
                    <tr>
                        <th class="px-3 py-2">ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($myTransactions)): ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted">No transactions today yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($myTransactions as $sale): ?>
                        <tr>
                            <td class="px-3"><code class="text-primary">#<?= $sale['id'] ?></code></td>
                            <td>₱<?= number_format((float)$sale['total_amount'], 2) ?></td>
                            <td>
                                <?php $sc = match($sale['status']) { 'completed' => 'success', 'returned' => 'warning', default => 'danger' }; ?>
                                <span class="badge bg-<?= $sc ?>"><?= ucfirst($sale['status']) ?></span>
                            </td>
                            <td><a href="<?= site_url('sales') ?>" class="text-primary small">View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
