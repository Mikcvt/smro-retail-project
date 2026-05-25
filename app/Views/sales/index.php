<?php
/**
 * @var array $sales
 * @var \CodeIgniter\Pager\Pager $pager
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Sales Transactions</h2>
        <a href="<?= site_url('sales/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Sale
        </a>
    </div>

    <?= $this->include('partials/_alerts') ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Reference No</th>
                            <th>Cashier</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sales)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No sales found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sales as $sale): ?>
                                <tr>
                                    <td><?= esc($sale['id']) ?></td>
                                    <td><strong><?= esc($sale['reference_no']) ?></strong></td>
                                    <td><?= esc($sale['cashier_name'] ?? 'N/A') ?></td>
                                    <td>₱<?= number_format((float) $sale['total_amount'], 2) ?></td>
                                    <td>
                                        <?php
                                        $badges = [
                                            'completed' => 'bg-success',
                                            'returned'  => 'bg-warning text-dark',
                                            'void'      => 'bg-danger',
                                        ];
                                        $badge = $badges[$sale['status']] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $badge ?>"><?= ucfirst(esc($sale['status'])) ?></span>
                                    </td>
                                    <td><?= esc($sale['created_at']) ?></td>
                                    <td>
                                        <?php if ($sale['status'] === 'completed' && in_array(session('role'), ['superadmin', 'manager'], true)): ?>
                                            <form action="<?= site_url('sales/' . $sale['id'] . '/void') ?>" method="post" class="d-inline"
                                                  onsubmit="return confirm('Void this sale and restore stock?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Void</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?= $pager ? $pager->links() : '' ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
