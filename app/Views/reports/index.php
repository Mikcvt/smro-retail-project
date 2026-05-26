<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Reports & Analytics<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background:rgba(99,102,241,0.15)"><i class="bi bi-cart-check fs-4 text-primary"></i></div>
                    <div>
                        <div class="text-muted small">Total Sales</div>
                        <div class="fw-bold fs-4"><?= number_format($totalSales) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background:rgba(16,185,129,0.15)"><i class="bi bi-currency-dollar fs-4 text-success"></i></div>
                    <div>
                        <div class="text-muted small">Total Revenue</div>
                        <div class="fw-bold fs-4">₱<?= number_format((float)$totalRevenue, 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background:rgba(245,158,11,0.15)"><i class="bi bi-arrow-return-left fs-4 text-warning"></i></div>
                    <div>
                        <div class="text-muted small">Total Returns</div>
                        <div class="fw-bold fs-4"><?= number_format($totalReturns) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background:rgba(59,130,246,0.15)"><i class="bi bi-box-seam fs-4 text-info"></i></div>
                    <div>
                        <div class="text-muted small">Active Products</div>
                        <div class="fw-bold fs-4"><?= number_format($totalProducts) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Monthly Revenue -->
    <div class="col-lg-5">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Monthly Revenue</h6>
                <a href="<?= site_url('reports/export') ?>" class="btn btn-sm btn-outline-light"><i class="bi bi-download me-1"></i>Export</a>
            </div>
            <div class="card-body">
                <?php if (empty($monthlySales)): ?>
                    <p class="text-muted text-center py-4">No data available.</p>
                <?php else: ?>
                    <?php foreach ($monthlySales as $month): ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span><?= $month['month'] ?></span>
                            <span class="fw-semibold">₱<?= number_format((float)$month['revenue'], 2) ?></span>
                        </div>
                        <?php
                        $maxRevenue = max(array_column($monthlySales, 'revenue')) ?: 1;
                        $pct = round(($month['revenue'] / $maxRevenue) * 100);
                        ?>
                        <div class="progress" style="height:6px;background:rgba(255,255,255,0.1)">
                            <div class="progress-bar" style="width:<?= $pct ?>%;background:linear-gradient(90deg,#6366f1,#8b5cf6)"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="col-lg-7">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Recent Sales</h6>
                <a href="<?= site_url('sales') ?>" class="small text-primary">View all →</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 small">
                        <thead class="border-bottom border-secondary">
                            <tr>
                                <th class="px-3 py-2">Reference</th>
                                <th>Cashier</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentSales)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No sales yet.</td></tr>
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
                                    <td class="text-muted"><?= date('M d', strtotime($sale['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
