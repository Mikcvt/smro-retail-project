<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Roles & Permissions<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Roles & Permissions</h2>
        <p class="text-muted mb-0">Manage role definitions and assigned permissions (scaffold).</p>
    </div>
    <div>
        <!-- Future: Add role/permission management actions here -->
    </div>
</div>

<div class="card bg-dark border-secondary">
    <div class="card-body">
        <div class="row">
            <?php foreach ($roles as $key => $r): ?>
                <div class="col-md-4 mb-3">
                    <div class="p-3 rounded" style="background: rgba(255,255,255,0.02);">
                        <div class="fw-semibold mb-1"><?= esc($r['label']) ?></div>
                        <div class="text-muted small mb-2"><?= esc($r['description']) ?></div>

                        <div class="small text-muted mb-1">Sample permissions</div>
                        <ul class="list-unstyled small">
                            <li><i class="bi bi-check2 me-2 text-success"></i>Access dashboard</li>
                            <li><i class="bi bi-check2 me-2 text-success"></i>Manage products</li>
                            <li><i class="bi bi-check2 me-2 text-success"></i>Process sales</li>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
