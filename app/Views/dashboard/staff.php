<?php
/**
 * View: Staff Dashboard
 * Expected data from DashboardController::staff():
 *   $salesTodayCount  int
 *   $myTransactions   array [{id, product_name, quantity, total_amount, created_at}]
 *
 * @var \CodeIgniter\View\View $this
 */

$this->extend('layouts/main');
$this->section('title');
echo 'Staff Dashboard';
$this->endSection();

$this->section('content');

$staffName       = session('name') ?? 'Staff';
$firstName       = explode(' ', trim($staffName))[0];
$salesTodayCount = $salesTodayCount ?? 0;
$myTransactions  = $myTransactions  ?? [];

// Determine greeting based on hour
$hour     = (int) date('G');
$greeting = match (true) {
    $hour < 12 => 'Good morning',
    $hour < 17 => 'Good afternoon',
    default    => 'Good evening',
};
?>

<!-- Greeting Header -->
<div class="smro-staff-greeting mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                <?= esc($greeting) ?>, <?= esc($firstName) ?>
                <span class="smro-wave" aria-hidden="true">👋</span>
            </h4>
            <p class="text-muted small mb-0">
                <i class="bi bi-calendar3 me-1"></i>
                <?= esc(date('l, F j, Y')) ?> &bull;
                <i class="bi bi-clock me-1 ms-1"></i>
                <span id="smroLiveClock"><?= esc(date('g:i A')) ?></span>
            </p>
        </div>
        <!-- Primary CTA -->
        <a href="<?= base_url('/sales/create') ?>"
           class="btn btn-primary btn-lg smro-cta-btn fw-semibold px-4"
           id="newSaleBtn">
            <i class="bi bi-cart-plus-fill me-2"></i>New Sale
        </a>
    </div>
</div>

<!-- ── STAT CARD + QUICK ACTIONS ─────────────────────────────────── -->
<div class="row g-3 mb-4">

    <!-- My Sales Today -->
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm smro-metric-card smro-staff-sales-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="smro-metric-icon bg-primary-subtle text-primary">
                    <i class="bi bi-bag-check-fill fs-3"></i>
                </div>
                <div>
                    <div class="smro-metric-label">My Sales Today</div>
                    <div class="smro-metric-value display-6 fw-bold lh-1">
                        <?= number_format($salesTodayCount) ?>
                    </div>
                    <div class="small text-muted mt-1">
                        transaction<?= $salesTodayCount !== 1 ? 's' : '' ?> completed
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action: New Sale (duplicate CTA for mobile) -->
    <div class="col-12 col-sm-6 col-md-4">
        <a href="<?= base_url('/sales/create') ?>"
           class="card border-0 shadow-sm h-100 text-decoration-none smro-quick-action-card smro-quick-action-card--primary">
            <div class="card-body d-flex flex-column align-items-center justify-content-center gap-2 py-4">
                <i class="bi bi-cart-plus-fill fs-2 text-primary"></i>
                <span class="fw-semibold text-dark">Record a Sale</span>
                <span class="small text-muted text-center">Process a customer transaction</span>
            </div>
        </a>
    </div>

    <!-- Quick Action: View Returns -->
    <div class="col-12 col-sm-6 col-md-4">
        <a href="<?= base_url('/returns') ?>"
           class="card border-0 shadow-sm h-100 text-decoration-none smro-quick-action-card">
            <div class="card-body d-flex flex-column align-items-center justify-content-center gap-2 py-4">
                <i class="bi bi-arrow-return-left fs-2 text-warning"></i>
                <span class="fw-semibold text-dark">Process Return</span>
                <span class="small text-muted text-center">Handle a customer return request</span>
            </div>
        </a>
    </div>

</div>
<!-- /STAT CARD + QUICK ACTIONS -->

<!-- ── MY TODAY'S TRANSACTIONS ────────────────────────────────────── -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
        <h6 class="fw-semibold mb-0">
            <i class="bi bi-receipt text-primary me-2"></i>My Transactions Today
        </h6>
        <a href="<?= base_url('/sales?filter=mine') ?>" class="btn btn-sm btn-outline-primary">
            Full History
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($myTransactions)) : ?>
            <?= view('partials/_empty_state', [
                'icon'          => 'bi-cart-x',
                'message'       => 'No transactions yet today.',
                'subMessage'    => 'Your completed sales will appear here.',
                'actionLabel'   => 'Record First Sale',
                'actionUrl'     => base_url('/sales/create'),
                'actionClass'   => 'btn-primary',
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
                            <th scope="col">Time</th>
                            <th scope="col" class="text-center pe-3">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($myTransactions as $txn) : ?>
                            <tr>
                                <td class="ps-3 text-muted small">
                                    <?= esc($txn['id']) ?>
                                </td>
                                <td class="fw-medium">
                                    <?= esc($txn['product_name']) ?>
                                </td>
                                <td class="text-center">
                                    <?= esc($txn['quantity']) ?>
                                </td>
                                <td class="text-end fw-semibold text-success">
                                    ₱<?= number_format((float)$txn['total_amount'], 2) ?>
                                </td>
                                <td class="text-muted small">
                                    <?= esc(date('g:i A', strtotime($txn['created_at']))) ?>
                                </td>
                                <td class="text-center pe-3">
                                    <a href="<?= base_url('/sales/' . esc($txn['id'])) ?>"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="View receipt">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <!-- Daily total footer -->
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="ps-3 fw-semibold text-end">
                                Daily Total:
                            </td>
                            <td class="text-end fw-bold text-success">
                                ₱<?= number_format(
                                    array_sum(array_column($myTransactions, 'total_amount')),
                                    2
                                ) ?>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    /* Live clock — vanilla JS, updates every minute */
    (function () {
        'use strict';
        const clockEl = document.getElementById('smroLiveClock');
        if (!clockEl) return;

        function updateClock() {
            const now  = new Date();
            let   h    = now.getHours();
            const m    = String(now.getMinutes()).padStart(2, '0');
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            clockEl.textContent = h + ':' + m + ' ' + ampm;
        }

        updateClock();
        setInterval(updateClock, 60000);
    }());
</script>

<?php $this->endSection(); ?>