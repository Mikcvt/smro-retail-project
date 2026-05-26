<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Export Data<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card bg-dark border-secondary">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-spreadsheet fs-1 text-success mb-3 d-block"></i>
                <h5 class="fw-bold">Export Sales Report</h5>
                <p class="text-muted small mb-4">Download all sales transactions as CSV</p>
                <a href="<?= site_url('sales') ?>" class="btn btn-success"><i class="bi bi-download me-1"></i>Export Sales CSV</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card bg-dark border-secondary">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-bar-graph fs-1 text-primary mb-3 d-block"></i>
                <h5 class="fw-bold">Export Inventory Report</h5>
                <p class="text-muted small mb-4">Download current product stock levels as CSV</p>
                <a href="<?= site_url('products') ?>" class="btn btn-primary"><i class="bi bi-download me-1"></i>Export Inventory CSV</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
