<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>System Settings<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary"><h6 class="mb-0"><i class="bi bi-building me-2"></i>Store Information</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted small">Store Name</label>
                    <input type="text" class="form-control bg-dark text-white border-secondary" value="SMRO Retail & Apparel Hub">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Branch</label>
                    <input type="text" class="form-control bg-dark text-white border-secondary" value="Downtown Branch">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Contact Email</label>
                    <input type="email" class="form-control bg-dark text-white border-secondary" value="admin@smro.com">
                </div>
                <button class="btn btn-primary btn-sm"><i class="bi bi-check me-1"></i>Save Settings</button>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary"><h6 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Security Settings</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary">
                    <div><div class="fw-semibold small">CSRF Protection</div><div class="text-muted" style="font-size:.8rem">Cross-site request forgery protection</div></div>
                    <span class="badge bg-success">Enabled</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary">
                    <div><div class="fw-semibold small">Password Hashing</div><div class="text-muted" style="font-size:.8rem">bcrypt with cost factor 10</div></div>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2">
                    <div><div class="fw-semibold small">API Bearer Token</div><div class="text-muted" style="font-size:.8rem">24-hour token expiry</div></div>
                    <span class="badge bg-success">Active</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
