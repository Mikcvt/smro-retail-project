<?php
/**
 * @var array $returns
 * @var \CodeIgniter\Pager\Pager $pager
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Returns</h2>
        <a href="<?= site_url('returns/create') ?>" class="btn btn-primary">
            <i class="bi bi-arrow-return-left"></i> New Return
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
                            <th>Sale Reference</th>
                            <th>Processed By</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($returns)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No returns found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($returns as $return): ?>
                                <tr>
                                    <td><?= esc($return['id']) ?></td>
                                    <td><strong><?= esc($return['reference_no'] ?? 'N/A') ?></strong></td>
                                    <td><?= esc($return['processed_by'] ?? 'N/A') ?></td>
                                    <td><?= esc($return['reason']) ?></td>
                                    <td>
                                        <?php
                                        $badges = [
                                            'pending'  => 'bg-warning text-dark',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                        ];
                                        $badge = $badges[$return['status']] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $badge ?>"><?= ucfirst(esc($return['status'])) ?></span>
                                    </td>
                                    <td><?= esc($return['created_at']) ?></td>
                                    <td>
                                        <?php if ($return['status'] === 'pending' && in_array(session('role'), ['superadmin', 'manager'], true)): ?>
                                            <form action="<?= site_url('returns/' . $return['id'] . '/approve') ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <form action="<?= site_url('returns/' . $return['id'] . '/reject') ?>" method="post" class="d-inline"
                                                  onsubmit="return confirm('Reject this return and reverse stock?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
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
