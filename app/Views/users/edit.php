<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Edit Account<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary">
                <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Account — <?= esc($user['firstname'] . ' ' . $user['lastname']) ?></h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('users/' . $user['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">First Name *</label>
                            <input type="text" name="firstname" class="form-control bg-dark text-white border-secondary" value="<?= esc(old('firstname', $user['firstname'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Last Name *</label>
                            <input type="text" name="lastname" class="form-control bg-dark text-white border-secondary" value="<?= esc(old('lastname', $user['lastname'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Email Address *</label>
                            <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="<?= esc(old('email', $user['email'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Role *</label>
                            <select name="role" class="form-select bg-dark text-white border-secondary" required>
                                <option value="manager" <?= ($user['role'] === 'manager') ? 'selected' : '' ?>>Manager</option>
                                <option value="staff" <?= ($user['role'] === 'staff') ? 'selected' : '' ?>>Staff</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Age</label>
                            <input type="number" name="age" class="form-control bg-dark text-white border-secondary" value="<?= esc(old('age', $user['age'])) ?>" min="18" max="99">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Contact No.</label>
                            <input type="text" name="contact_no" class="form-control bg-dark text-white border-secondary" value="<?= esc(old('contact_no', $user['contact_no'])) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">New Password <span class="text-muted">(leave blank to keep)</span></label>
                            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" minlength="8">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-muted small">Address</label>
                            <input type="text" name="address" class="form-control bg-dark text-white border-secondary" value="<?= esc(old('address', $user['address'])) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Status</label>
                            <select name="is_active" class="form-select bg-dark text-white border-secondary">
                                <option value="1" <?= $user['is_active'] ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= !$user['is_active'] ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
                        <a href="<?= site_url('users') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
