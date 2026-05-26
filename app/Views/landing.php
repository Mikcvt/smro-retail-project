<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMRO — Retail & Apparel Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; background: #0a0f1e; color: #f8fafc; overflow-x: hidden; }

        /* Hero */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #0a0f1e 0%, #0f172a 50%, #1a1040 100%);
            position: relative;
            display: flex; flex-direction: column;
        }
        .blob { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.15; pointer-events: none; }
        .blob-1 { width: 600px; height: 600px; background: #6366f1; top: -100px; right: -100px; }
        .blob-2 { width: 400px; height: 400px; background: #8b5cf6; bottom: 0; left: -100px; }
        .blob-3 { width: 300px; height: 300px; background: #3b82f6; top: 50%; left: 40%; }

        /* Navbar */
        .landing-nav { padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 10; }
        .brand { font-size: 1.4rem; font-weight: 700; color: #fff; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        .brand-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; }

        /* Hero content */
        .hero-content { flex: 1; display: flex; align-items: center; justify-content: center; text-align: center; padding: 2rem; position: relative; z-index: 5; }
        .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.85rem; margin-bottom: 1.5rem; }
        .hero-title { font-size: clamp(2.5rem, 6vw, 4.5rem); font-weight: 700; line-height: 1.1; margin-bottom: 1.5rem; }
        .hero-title span { background: linear-gradient(135deg, #6366f1, #a78bfa, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero-subtitle { font-size: 1.15rem; color: #94a3b8; max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.7; }
        .btn-login { background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; color: #fff; padding: 0.9rem 2.5rem; border-radius: 12px; font-size: 1.05rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s; box-shadow: 0 8px 30px rgba(99,102,241,0.4); }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(99,102,241,0.5); color: #fff; }

        /* Features */
        .features { padding: 5rem 2rem; background: #0f172a; position: relative; z-index: 5; }
        .feature-card { background: rgba(30,41,59,0.6); border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 2rem; transition: all 0.3s; }
        .feature-card:hover { transform: translateY(-5px); border-color: rgba(99,102,241,0.3); background: rgba(30,41,59,0.9); }
        .feature-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 1rem; }
        .feature-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .feature-desc { font-size: 0.875rem; color: #94a3b8; line-height: 1.6; }

        /* Stats */
        .stats { padding: 4rem 2rem; background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(139,92,246,0.1)); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .stat-number { font-size: 2.5rem; font-weight: 700; background: linear-gradient(135deg, #6366f1, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .stat-label { color: #94a3b8; font-size: 0.9rem; }

        /* Footer */
        footer { padding: 2rem; text-align: center; color: #475569; font-size: 0.85rem; background: #0a0f1e; }
    </style>
</head>
<body>

<div class="hero">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <!-- Navbar -->
    <nav class="landing-nav">
        <a href="#" class="brand">
            <div class="brand-icon"><i class="bi bi-shop text-white"></i></div>
            SMRO
        </a>
        <!-- Employee login removed (Superadmin will create accounts) -->
    </nav>

    <!-- Hero Content -->
    <div class="hero-content">
        <div>
            <div class="hero-badge">
                <i class="bi bi-shield-check"></i>
                Secure Multi-Tenant Resource Orchestrator
            </div>
            <h1 class="hero-title">
                Retail & Apparel Hub<br>
                <span>Management System</span>
            </h1>
            <p class="hero-subtitle">
                A complete point-of-sale and inventory management platform for retail businesses.
                Manage products, process sales, track returns, and generate reports — all in one place.
            </p>
            <a href="<?= site_url('login') ?>" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In to Your Account
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<section class="stats">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <div class="stat-number">3</div>
                <div class="stat-label">Role Levels</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">10+</div>
                <div class="stat-label">System Modules</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">100%</div>
                <div class="stat-label">Secure & Encrypted</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">24/7</div>
                <div class="stat-label">System Availability</div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold fs-2">Everything You Need</h2>
            <p class="text-muted">Powerful tools for every role in your retail operation</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(99,102,241,0.15)"><i class="bi bi-cart3 text-primary"></i></div>
                    <div class="feature-title">Point of Sale</div>
                    <div class="feature-desc">Fast and intuitive sales processing with real-time stock deduction and receipt generation.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(16,185,129,0.15)"><i class="bi bi-box-seam text-success"></i></div>
                    <div class="feature-title">Inventory Management</div>
                    <div class="feature-desc">Track products, variants, stock levels, and get alerts when items run low.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(245,158,11,0.15)"><i class="bi bi-arrow-return-left text-warning"></i></div>
                    <div class="feature-title">Returns Processing</div>
                    <div class="feature-desc">Handle customer returns with automatic stock restocking and approval workflows.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(59,130,246,0.15)"><i class="bi bi-bar-chart-line text-info"></i></div>
                    <div class="feature-title">Analytics & Reports</div>
                    <div class="feature-desc">Comprehensive sales reports, revenue tracking, and exportable data for business insights.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(239,68,68,0.15)"><i class="bi bi-people text-danger"></i></div>
                    <div class="feature-title">User Management</div>
                    <div class="feature-desc">Role-based access control with SuperAdmin, Manager, and Staff permission levels.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(139,92,246,0.15)"><i class="bi bi-shield-lock" style="color:#a78bfa"></i></div>
                    <div class="feature-title">Secure & Reliable</div>
                    <div class="feature-desc">CSRF protection, bcrypt password hashing, and Bearer Token API authentication.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer>
    &copy; <?= date('Y') ?> SMRO — Retail & Apparel Hub. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
