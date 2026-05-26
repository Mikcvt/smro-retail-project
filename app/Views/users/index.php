<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>User Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Manage staff and manager accounts</p>
    </div>
    <a href="<?= site_url('users/create') ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Add Account
    </a>
</div>

<div class="card bg-dark border-secondary">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead class="border-bottom border-secondary">
                    <tr>
                        <th class="px-4 py-3">Employee</th>
                        <th>Role</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No accounts found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center gap-3">
                                    <?php if ($user['profile_image']): ?>
                                        <img src="<?= base_url('uploads/profiles/' . $user['profile_image']) ?>" class="rounded-circle" width="38" height="38" style="object-fit:cover">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;font-size:.85rem">
                                            <?= strtoupper(substr($user['firstname'] ?? $user['name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold"><?= esc($user['firstname'] . ' ' . $user['lastname']) ?></div>
                                        <div class="small text-muted"><?= esc($user['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php $rc = $user['role'] === 'manager' ? 'warning' : 'primary'; ?>
                                <span class="badge bg-<?= $rc ?> text-dark"><?= ucfirst($user['role']) ?></span>
                            </td>
                            <td class="text-muted small"><?= esc($user['contact_no'] ?? '—') ?></td>
                            <td>
                                <span class="badge bg-<?= $user['is_active'] ? 'success' : 'danger' ?>">
                                    <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            <td class="text-end px-4">
                                <a href="<?= site_url('users/' . $user['id'] . '/edit') ?>" class="btn btn-sm btn-outline-light me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?= site_url('users/' . $user['id'] . '/delete') ?>" method="post" class="d-inline"
                                      onsubmit="return confirm('Delete this account?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
