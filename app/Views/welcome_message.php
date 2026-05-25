<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMRO Retail - Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .hero-container {
            text-align: center;
            z-index: 10;
        }
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(to right, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            animation: fadeInDown 1s ease-out;
        }
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 300;
            color: #94a3b8;
            margin-bottom: 2.5rem;
            animation: fadeInUp 1s ease-out 0.3s both;
        }
        .btn-custom {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0 0.5rem;
            animation: zoomIn 0.8s ease-out 0.6s both;
        }
        .btn-primary-custom {
            background: linear-gradient(to right, #4f46e5, #3b82f6);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
            color: white;
        }
        .btn-secondary-custom {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        .btn-secondary-custom:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            color: white;
        }
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.6;
            animation: float 10s infinite alternate;
        }
        .blob-1 {
            width: 400px;
            height: 400px;
            background: #3b82f6;
            top: -100px;
            left: -100px;
            border-radius: 50%;
        }
        .blob-2 {
            width: 300px;
            height: 300px;
            background: #8b5cf6;
            bottom: -50px;
            right: -50px;
            border-radius: 50%;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 30px) scale(1.1); }
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="hero-container container">
        <h1 class="hero-title">SMRO Retail Hub</h1>
        <p class="hero-subtitle">Secure Multi-Tenant Resource Orchestrator for Next-Gen Retail</p>
        
        <?php if (session()->get('is_logged_in')): ?>
            <a href="<?= base_url('dashboard') ?>" class="btn-custom btn-primary-custom">Enter Dashboard</a>
        <?php else: ?>
            <a href="<?= base_url('login') ?>" class="btn-custom btn-primary-custom">Sign In</a>
            <a href="<?= base_url('register') ?>" class="btn-custom btn-secondary-custom">Create Account</a>
        <?php endif; ?>
    </div>

</body>
</html>
