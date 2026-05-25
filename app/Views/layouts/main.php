<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMRO Retail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f1f5f9; }
        #sidebar {
            width: 240px; min-height: 100vh;
            background: #0f172a;
            color: #cbd5e1;
            position: fixed; top: 0; left: 0; z-index: 100;
        }
        #sidebar .brand { padding: 1.25rem 1.5rem; font-size: 1.1rem; font-weight: 600; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.07); }
        #sidebar .nav-link { color: #94a3b8; padding: 0.6rem 1.5rem; border-radius: 0; font-size: 0.9rem; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { background: rgba(255,255,255,0.07); color: #fff; }
        #sidebar .nav-link i { margin-right: 0.5rem; }
        #main { margin-left: 240px; }
        .topbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 0.75rem 1.5rem; }
    </style>
</head>
<body>

<div id="sidebar">
    <div class="brand"><i class="bi bi-shop"></i> SMRO Retail</div>
    <nav class="nav flex-column mt-2">
        <a href="<?= site_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= site_url('products') ?>" class="nav-link"><i class="bi bi-box-seam"></i> Products</a>
        <a href="<?= site_url('sales') ?>" class="nav-link"><i class="bi bi-cart3"></i> Sales</a>
        <a href="<?= site_url('returns') ?>" class="nav-link"><i class="bi bi-arrow-return-left"></i> Returns</a>
        <?php if (in_array(session('role'), ['superadmin', 'manager'], true)): ?>
        <hr class="border-secondary mx-3">
        <span class="nav-link text-secondary" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.05em">Management</span>
        <?php endif; ?>
    </nav>
</div>

<div id="main">
    <div class="topbar d-flex justify-content-between align-items-center">
        <span class="text-muted small">
            <?php
            $badges = ['superadmin' => 'danger', 'manager' => 'warning', 'staff' => 'primary'];
            $color  = $badges[session('role')] ?? 'secondary';
            ?>
            <span class="badge bg-<?= $color ?>"><?= ucfirst(esc(session('role') ?? '')) ?></span>
            <?= esc(session('name')) ?>
        </span>
        <a href="<?= site_url('logout') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

    <div class="p-4">
        <?= $this->renderSection('content') ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
