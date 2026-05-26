<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>My Profile<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card bg-dark border-secondary text-center p-4">
            <?php if ($user['profile_image']): ?>
                <img src="<?= base_url('uploads/profiles/' . $user['profile_image']) ?>"
                     class="rounded-circle mx-auto mb-3" width="100" height="100" style="object-fit:cover;border:3px solid #6366f1">
            <?php else: ?>
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-2"
                     style="width:100px;height:100px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border:3px solid #6366f1">
                    <?= strtoupper(substr($user['firstname'] ?? $user['name'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            <h5 class="fw-bold mb-1"><?= esc($user['firstname'] . ' ' . $user['lastname']) ?></h5>
            <p class="text-muted small mb-2"><?= esc($user['email']) ?></p>
            <?php $rc = match($user['role']) { 'superadmin' => 'danger', 'manager' => 'warning', default => 'primary' }; ?>
            <span class="badge bg-<?= $rc ?>"><?= ucfirst($user['role'] === 'superadmin' ? 'Super Admin' : $user['role']) ?></span>
            <hr class="border-secondary mt-3">
            <div class="text-start small">
                <div class="d-flex justify-content-between py-1 border-bottom border-secondary">
                    <span class="text-muted">Age</span>
                    <span><?= esc($user['age'] ?? '—') ?></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom border-secondary">
                    <span class="text-muted">Contact</span>
                    <span><?= esc($user['contact_no'] ?? '—') ?></span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Address</span>
                    <span class="text-end" style="max-width:150px; word-break: break-word; white-space: normal;"><?= esc($user['address'] ?? '—') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="col-lg-8">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary">
                <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Profile</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('profile') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">First Name *</label>
                            <input type="text" name="firstname" class="form-control bg-dark text-white border-secondary"
                                   value="<?= esc(old('firstname', $user['firstname'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Last Name *</label>
                            <input type="text" name="lastname" class="form-control bg-dark text-white border-secondary"
                                   value="<?= esc(old('lastname', $user['lastname'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Age</label>
                            <input type="number" name="age" class="form-control bg-dark text-white border-secondary"
                                   value="<?= esc(old('age', $user['age'])) ?>" min="18" max="99">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Contact No.</label>
                            <input type="text" name="contact_no" class="form-control bg-dark text-white border-secondary"
                                   value="<?= esc(old('contact_no', $user['contact_no'])) ?>" placeholder="09XX-XXX-XXXX">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small">Address</label>
                            <input type="text" name="address" class="form-control bg-dark text-white border-secondary"
                                   value="<?= esc(old('address', $user['address'])) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small">Profile Photo</label>
                            <input type="file" name="profile_image" class="form-control bg-dark text-white border-secondary"
                                   accept="image/jpeg,image/png,image/webp">
                            <div class="form-text text-muted">Max 2MB. JPG, PNG, or WEBP.</div>
                        </div>
                        <div class="col-12"><hr class="border-secondary"></div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">New Password <span class="text-muted">(leave blank to keep current)</span></label>
                            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" minlength="8">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
