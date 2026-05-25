<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h2 class="fw-bold mb-1">Welcome, <?= esc(session('name')) ?> <span class="badge bg-primary ms-2" style="font-size:0.6em">Staff</span></h2>
    <p class="text-muted mb-4">Process sales and view your transactions.</p>

    <?= $this->include('partials/_alerts') ?>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <a href="<?= site_url('sales/create') ?>" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded p-3"><i class="bi bi-cart-plus fs-4 text-success"></i></div>
                        <div><div class="text-muted small">Quick Action</div><div class="fw-bold fs-5">New Sale</div></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= site_url('sales') ?>" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3"><i class="bi bi-receipt fs-4 text-primary"></i></div>
                        <div><div class="text-muted small">View</div><div class="fw-bold fs-5">Sales History</div></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= site_url('products') ?>" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 rounded p-3"><i class="bi bi-box-seam fs-4 text-info"></i></div>
                        <div><div class="text-muted small">Browse</div><div class="fw-bold fs-5">Products</div></div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
