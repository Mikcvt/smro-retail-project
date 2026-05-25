<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SMRO Retail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
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
            position: relative;
        }
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.5;
            animation: float 10s infinite alternate;
        }
        .blob-1 {
            width: 350px;
            height: 350px;
            background: #3b82f6;
            top: -50px;
            right: -50px;
            border-radius: 50%;
        }
        .blob-2 {
            width: 300px;
            height: 300px;
            background: #8b5cf6;
            bottom: -50px;
            left: -50px;
            border-radius: 50%;
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 500px;
            padding: 3rem 2rem;
            z-index: 10;
            animation: fadeIn 0.8s ease-out;
        }
        .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
            color: white;
        }
        .form-control.is-invalid {
            border-color: #ef4444;
            background-image: none;
        }
        .invalid-feedback {
            color: #fca5a5;
            font-size: 0.8rem;
        }
        .form-label {
            color: #94a3b8;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .btn-primary {
            background: linear-gradient(to right, #4f46e5, #3b82f6);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        .auth-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .auth-link:hover {
            color: #60a5fa;
        }
        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-30px, 30px); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="glass-card">
        <h3 class="text-center mb-4 fw-bold">Create Account</h3>

        <form action="<?= base_url('register') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control <?= validation_show_error('name') ? 'is-invalid' : '' ?>" id="name" value="<?= esc(old('name')) ?>" placeholder="John Doe">
                <div class="invalid-feedback">
                    <?= validation_show_error('name') ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control <?= validation_show_error('email') ? 'is-invalid' : '' ?>" id="email" value="<?= esc(old('email')) ?>" placeholder="john@example.com">
                <div class="invalid-feedback">
                    <?= validation_show_error('email') ?>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control <?= validation_show_error('password') ? 'is-invalid' : '' ?>" id="password" placeholder="••••••••">
                <div class="invalid-feedback">
                    <?= validation_show_error('password') ?>
                </div>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?= validation_show_error('confirm_password') ? 'is-invalid' : '' ?>" id="confirm_password" placeholder="••••••••">
                <div class="invalid-feedback">
                    <?= validation_show_error('confirm_password') ?>
                </div>
            </div>
            
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>

        <div class="text-center">
            <span class="text-muted">Already have an account?</span> 
            <a href="<?= base_url('login') ?>" class="auth-link">Sign In</a>
        </div>
    </div>

</body>
</html>
