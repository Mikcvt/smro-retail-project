<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - SMRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
        }
        .navbar-custom {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .dashboard-card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            background: rgba(30, 41, 59, 0.8);
        }
        .role-badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            font-weight: 500;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">SMRO Retail</a>
        <div class="d-flex align-items-center">
            <span class="me-3 text-muted">Hello, <?= esc(session()->get('name')) ?></span>
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">Sign Out</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Welcome Back, <span class="badge role-badge ms-2">Staff</span></h2>
            <p class="text-muted">Access your daily tasks and point of sale tools.</p>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="dashboard-card p-4 h-100">
                <h5 class="fw-bold text-white mb-3">Point of Sale</h5>
                <p class="text-muted mb-0">Process transactions and handle customer checkouts.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card p-4 h-100">
                <h5 class="fw-bold text-white mb-3">Product Lookup</h5>
                <p class="text-muted mb-0">Search inventory availability and details.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card p-4 h-100">
                <h5 class="fw-bold text-white mb-3">My Profile</h5>
                <p class="text-muted mb-0">View your schedule and update personal details.</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
