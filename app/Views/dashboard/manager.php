<?php
/**
 * View: Manager Dashboard
 * Expected data from DashboardController::manager():
 *   $salesToday        int
 *   $pendingReturns    int
 *   $revenueThisMonth  float
 *   $recentSales       array [{id, product_name, quantity, total_amount, staff_name, created_at}]
 *   $topProducts       array [{product_name, total_sold, total_revenue}]
 *
 * @var \CodeIgniter\View\View $this
 */

$this->extend('layouts/main');
$this->section('title');
echo 'Manager Dashboard';
$this->endSection();

$this->section('content');

$salesToday       = $salesToday       ?? 0;
$pendingReturns   = $pendingReturns   ?? 0;
$revenueThisMonth = $revenueThisMonth ?? 0.0;
$recentSales      = $recentSales      ?? [];
$topProducts      = $topProducts      ?? [];
?>

<!-- Page Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-line-fill text-warning me-2"></i>Manager Dashboard
        </h4>
        <p class="text-muted small mb-0">
            Operations overview &mdash; <?= esc(date('l, F j, Y')) ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/reports') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-file-earmark-bar-graph me-1"></i>Reports
        </a>
        <a href="<?= base_url('/sales/create') ?>" class="btn btn-sm btn-warning text-dark fw-semibold">
            <i class="bi bi-cart-plus-fill me-1"></i>New Sale
        </a>
    </div>
</div>

<!-- ── METRIC CARDS ─────────────────────────────────────────────── -->
<div class="row g-3 mb-4">

    <!-- Today's Sales -->
    <div class="col-12 col-sm-4">
        <div class="card smro-metric-card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="smro-metric-icon bg-success-subtle text-success">
                    <i class="bi bi-cart-check-fill fs-4"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="smro-metric-label">Sales Today</div>
                    <div class="smro-metric-value"><?= number_format($salesToday) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Returns -->
    <div class="col-12 col-sm-4">
        <div class="card smro-metric-card border-0 shadow-sm h-100 <?= $pendingReturns > 0 ? 'smro-card-alert' : '' ?>">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="smro-metric-icon <?= $pendingReturns > 0 ? 'bg-danger-subtle text-danger' : 'bg-secondary-subtle text-secondary' ?>">
                    <i class="bi bi-arrow-return-left fs-4"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="smro-metric-label">Pending Returns</div>
                    <div class="smro-metric-value">
                        <?= number_format($pendingReturns) ?>
                        <?php if ($pendingReturns > 0) : ?>
                            <span class="badge bg-danger ms-1 align-middle">!</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($pendingReturns > 0) : ?>
                    <a href="<?= base_url('/returns?status=pending') ?>"
                       class="btn btn-sm btn-outline-danger stretched-link"
                       title="Review Returns">
                        <i class="bi bi-arrow-right-short"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Revenue This Month -->
    <div class="col-12 col-sm-4">
        <div class="card smro-metric-card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="smro-metric-icon bg-primary-subtle text-primary">
                    <i class="bi bi-cash-coin fs-4"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="smro-metric-label">Revenue This Month</div>
                    <div class="smro-metric-value smro-metric-value--sm">
                        ₱<?= number_format($revenueThisMonth, 2) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /METRIC CARDS -->

<!-- ── RECENT SALES + TOP PRODUCTS ─────────────────────────────── -->
<div class="row g-3">

    <!-- Recent Sales Table -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                <h6 class="fw-semibold mb-0">
                    <i class="bi bi-clock-history text-warning me-2"></i>Recent Sales
                </h6>
                <a href="<?= base_url('/sales') ?>" class="btn btn-sm btn-outline-warning">
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentSales)) : ?>
                    <?= view('partials/_empty_state', [
                        'icon'       => 'bi-cart-x',
                        'message'    => 'No sales recorded today.',
                        'subMessage' => 'Completed transactions will appear here.',
                    ]) ?>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" scope="col">#</th>
                                    <th scope="col">Product</th>
                                    <th scope="col" class="text-center">Qty</th>
                                    <th scope="col" class="text-end">Amount</th>
                                    <th scope="col">Staff</th>
                                    <th scope="col" class="text-center pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentSales as $sale) : ?>
                                    <tr>
                                        <td class="ps-3 text-muted small">
                                            <?= esc($sale['id']) ?>
                                        </td>
                                        <td class="fw-medium">
                                            <?= esc($sale['product_name']) ?>
                                        </td>
                                        <td class="text-center">
                                            <?= esc($sale['quantity']) ?>
                                        </td>
                                        <td class="text-end fw-semibold text-success">
                                            ₱<?= number_format((float)$sale['total_amount'], 2) ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?= esc($sale['staff_name']) ?>
                                        </td>
                                        <td class="text-center pe-3">
                                            <a href="<?= base_url('/sales/' . esc($sale['id'])) ?>"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="View details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                <h6 class="fw-semibold mb-0">
                    <i class="bi bi-trophy-fill text-warning me-2"></i>Top Products
                    <span class="text-muted fw-normal small">(This Month)</span>
                </h6>
                <a href="<?= base_url('/products') ?>" class="btn btn-sm btn-outline-secondary">
                    All Products
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($topProducts)) : ?>
                    <?= view('partials/_empty_state', [
                        'icon'    => 'bi-trophy',
                        'message' => 'No sales data yet this month.',
                    ]) ?>
                <?php else : ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($topProducts as $rank => $product) : ?>
                            <li class="list-group-item d-flex align-items-center gap-3 py-3">
                                <span class="smro-rank-badge <?= $rank === 0 ? 'smro-rank-gold' : ($rank === 1 ? 'smro-rank-silver' : ($rank === 2 ? 'smro-rank-bronze' : 'smro-rank-default')) ?>">
                                    <?= $rank + 1 ?>
                                </span>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-medium text-truncate">
                                        <?= esc($product['product_name']) ?>
                                    </div>
                                    <div class="small text-muted">
                                        <?= number_format($product['total_sold']) ?> units sold
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <div class="fw-semibold text-success small">
                                        ₱<?= number_format((float)$product['total_revenue'], 2) ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<!-- /RECENT SALES + TOP PRODUCTS -->


