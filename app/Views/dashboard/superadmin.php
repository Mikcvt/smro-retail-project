<?php
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

<style>
/* ===== DASHBOARD LAYOUT FIX ===== */
.smro-page-title {
    font-weight: 700;
    font-size: 1.25rem;
}

.smro-subtitle {
    font-size: 0.85rem;
    color: #6c757d;
}

/* ===== METRIC CARDS ===== */
.smro-metric-card {
    border-radius: 14px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.smro-metric-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.smro-metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.smro-metric-label {
    font-size: 0.75rem;
    color: #6c757d;
}

.smro-metric-value {
    font-size: 1.4rem;
    font-weight: 700;
}

/* ===== TABLE CARD ===== */
.card {
    border-radius: 14px;
}

.table th {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
}

.table td {
    font-size: 0.9rem;
}

/* ===== TOP PRODUCTS ===== */
.smro-rank-badge {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
}

.smro-rank-gold { background: #ffd70033; color: #b8860b; }
.smro-rank-silver { background: #c0c0c033; color: #666; }
.smro-rank-bronze { background: #cd7f3233; color: #8b5a2b; }
.smro-rank-default { background: #e9ecef; color: #495057; }
</style>

<!-- HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">

    <div>
        <div class="smro-page-title">
            <i class="bi bi-bar-chart-line-fill text-warning me-1"></i>
            Superadmin Dashboard
        </div>

        <div class="smro-subtitle">
            Operations overview • <?= esc(date('l, F j, Y')) ?>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="<?= base_url('/reports') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-file-earmark-bar-graph me-1"></i> Reports
        </a>

        <a href="<?= base_url('/sales/create') ?>" class="btn btn-sm btn-warning fw-semibold">
            <i class="bi bi-cart-plus-fill me-1"></i> New Sale
        </a>
    </div>

</div>

<!-- METRICS -->
<div class="row g-3 mb-4">

    <div class="col-12 col-md-4">
        <div class="card smro-metric-card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="smro-metric-icon bg-success-subtle text-success">
                    <i class="bi bi-cart-check-fill fs-5"></i>
                </div>
                <div>
                    <div class="smro-metric-label">Sales Today</div>
                    <div class="smro-metric-value"><?= number_format($salesToday) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card smro-metric-card border-0 shadow-sm <?= $pendingReturns > 0 ? 'border border-danger-subtle' : '' ?>">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="smro-metric-icon <?= $pendingReturns > 0 ? 'bg-danger-subtle text-danger' : 'bg-secondary-subtle text-secondary' ?>">
                    <i class="bi bi-arrow-return-left fs-5"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="smro-metric-label">Pending Returns</div>
                    <div class="smro-metric-value">
                        <?= number_format($pendingReturns) ?>
                    </div>
                </div>

                <?php if ($pendingReturns > 0): ?>
                    <a href="<?= base_url('/returns?status=pending') ?>" class="btn btn-sm btn-outline-danger">
                        View
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card smro-metric-card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="smro-metric-icon bg-primary-subtle text-primary">
                    <i class="bi bi-cash-coin fs-5"></i>
                </div>
                <div>
                    <div class="smro-metric-label">Revenue This Month</div>
                    <div class="smro-metric-value">
                        ₱<?= number_format($revenueThisMonth, 2) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- TABLES -->
<div class="row g-3">

    <!-- RECENT SALES -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-clock-history text-warning me-1"></i> Recent Sales</strong>
                <a href="<?= base_url('/sales') ?>" class="btn btn-sm btn-outline-warning">View All</a>
            </div>

            <div class="card-body p-0">

                <?php if (empty($recentSales)) : ?>
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-cart-x fs-2"></i>
                        <div class="mt-2">No sales today</div>
                    </div>
                <?php else : ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Amount</th>
                                <th>Staff</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentSales as $sale): ?>
                                <tr>
                                    <td class="text-muted small"><?= esc($sale['id']) ?></td>
                                    <td><?= esc($sale['product_name']) ?></td>
                                    <td class="text-center"><?= esc($sale['quantity']) ?></td>
                                    <td class="text-end text-success fw-semibold">
                                        ₱<?= number_format((float)$sale['total_amount'], 2) ?>
                                    </td>
                                    <td class="text-muted small"><?= esc($sale['staff_name']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- TOP PRODUCTS -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <strong><i class="bi bi-trophy-fill text-warning me-1"></i> Top Products</strong>
            </div>

            <div class="card-body p-0">

                <?php if (empty($topProducts)) : ?>
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-trophy fs-2"></i>
                        <div class="mt-2">No data yet</div>
                    </div>
                <?php else : ?>

                <ul class="list-group list-group-flush">
                    <?php foreach ($topProducts as $rank => $product): ?>
                        <li class="list-group-item d-flex align-items-center gap-3">
                            
                            <div class="smro-rank-badge smro-rank-default">
                                <?= $rank + 1 ?>
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-semibold">
                                    <?= esc($product['product_name']) ?>
                                </div>
                                <div class="text-muted small">
                                    <?= number_format($product['total_sold']) ?> sold
                                </div>
                            </div>

                            <div class="text-success fw-semibold small">
                                ₱<?= number_format((float)$product['total_revenue'], 2) ?>
                            </div>

                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php endif; ?>

            </div>
        </div>
    </div>

</div>

<?php $this->endSection(); ?>