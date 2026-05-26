<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Create Account<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary">
                <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>New Employee Account</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('users') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">First Name *</label>
                            <input type="text" name="firstname" class="form-control bg-dark text-white border-secondary" value="<?= old('firstname') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Last Name *</label>
                            <input type="text" name="lastname" class="form-control bg-dark text-white border-secondary" value="<?= old('lastname') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Email Address *</label>
                            <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="<?= old('email') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Role *</label>
                            <select name="role" class="form-select bg-dark text-white border-secondary" required>
                                <option value="">Select Role</option>
                                <option value="manager" <?= old('role') === 'manager' ? 'selected' : '' ?>>Manager</option>
                                <option value="staff" <?= old('role') === 'staff' ? 'selected' : '' ?>>Staff</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Age</label>
                            <input type="number" name="age" class="form-control bg-dark text-white border-secondary" value="<?= old('age') ?>" min="18" max="99">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Contact No.</label>
                            <input type="text" name="contact_no" class="form-control bg-dark text-white border-secondary" value="<?= old('contact_no') ?>" placeholder="09XX-XXX-XXXX">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Password *</label>
                            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required minlength="8">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small">Address</label>
                            <input type="text" name="address" class="form-control bg-dark text-white border-secondary" value="<?= old('address') ?>">
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Create Account</button>
                        <a href="<?= site_url('users') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
