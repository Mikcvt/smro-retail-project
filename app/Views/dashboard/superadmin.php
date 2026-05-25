<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h2 class="fw-bold mb-1">Welcome, <?= esc(session('name')) ?> <span class="badge bg-danger ms-2" style="font-size:0.6em">SuperAdmin</span></h2>
    <p class="text-muted mb-4">Manage the entire retail ecosystem from here.</p>

    <?= $this->include('partials/_alerts') ?>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="<?= site_url('products') ?>" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3"><i class="bi bi-box-seam fs-4 text-primary"></i></div>
                        <div><div class="text-muted small">Products</div><div class="fw-bold fs-5">Inventory</div></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= site_url('sales') ?>" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded p-3"><i class="bi bi-cart3 fs-4 text-success"></i></div>
                        <div><div class="text-muted small">Sales</div><div class="fw-bold fs-5">Transactions</div></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= site_url('returns') ?>" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 rounded p-3"><i class="bi bi-arrow-return-left fs-4 text-warning"></i></div>
                        <div><div class="text-muted small">Returns</div><div class="fw-bold fs-5">Management</div></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= site_url('sales/create') ?>" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 rounded p-3"><i class="bi bi-plus-circle fs-4 text-info"></i></div>
                        <div><div class="text-muted small">Quick</div><div class="fw-bold fs-5">New Sale</div></div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('products/new') ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-plus"></i> Add Product</a>
                        <a href="<?= site_url('sales/create') ?>" class="btn btn-outline-success btn-sm"><i class="bi bi-cart-plus"></i> New Sale</a>
                        <a href="<?= site_url('returns/create') ?>" class="btn btn-outline-warning btn-sm"><i class="bi bi-arrow-return-left"></i> Process Return</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
