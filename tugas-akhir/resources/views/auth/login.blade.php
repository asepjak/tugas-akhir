<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRESCA - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #00bcd4;
            --accent-color: #0056b3;
            --text-dark: #333;
            --text-muted: #6c757d;
            --border-color: #ced4da;
            --shadow-light: rgba(0, 0, 0, 0.1);
            --shadow-medium: rgba(0, 0, 0, 0.2);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #ececec 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 1rem;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px var(--shadow-medium);
            max-width: 1000px;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-left {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 3rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-20px, -20px) rotate(180deg); }
        }

        .login-left .content {
            position: relative;
            z-index: 2;
        }

        .login-left h1 {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .login-left p {
            font-size: 1.2rem;
            font-weight: 300;
            line-height: 1.6;
            opacity: 0.9;
        }

        .login-left .company-info {
            margin-top: 2rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .login-right {
            flex: 1;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .login-subtitle {
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            font-size: 1rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            padding-left: 3rem;
            padding-right: 3rem;
            border: 2px solid var(--border-color);
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            width: 100%;
            height: 56px;
            line-height: 1.5;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            background-color: white;
            outline: none;
        }

        .form-control:hover:not(:focus) {
            border-color: var(--primary-color);
            background-color: white;
        }

        .form-group .bi {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            color: var(--text-muted);
            transition: color 0.3s ease;
            z-index: 2;
            pointer-events: none;
        }

        .form-group:focus-within .bi:not(.toggle-password) {
            color: var(--primary-color);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--text-muted);
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 0.375rem;
            z-index: 3;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .toggle-password:hover {
            color: var(--primary-color);
            background-color: rgba(0, 123, 255, 0.1);
        }

        .toggle-password:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .toggle-password:active {
            transform: translateY(-50%) scale(0.95);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .loading-spinner {
            display: none;
            margin-right: 0.5rem;
        }

        .loading .loading-spinner {
            display: inline-block;
        }

        .loading .btn-text {
            display: none;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .login-container {
                max-width: 800px;
            }

            .login-left,
            .login-right {
                padding: 3rem 2rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 0.5rem;
            }

            .login-container {
                flex-direction: column;
                border-radius: 1rem;
            }

            .login-left {
                padding: 3rem 2rem 2rem 2rem;
            }

            .login-left h1 {
                font-size: 2.5rem;
            }

            .login-right {
                padding: 2rem;
            }

            .login-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .login-left,
            .login-right {
                padding: 2rem 1.5rem;
            }

            .login-left h1 {
                font-size: 2rem;
            }

            .form-control {
                padding: 0.875rem 1rem;
                padding-left: 2.75rem;
                padding-right: 2.75rem;
                font-size: 0.95rem;
                height: 52px;
            }

            .form-group .bi:not(.toggle-password) {
                left: 0.85rem;
                font-size: 1.1rem;
            }

            .toggle-password {
                right: 0.85rem;
                font-size: 1.1rem;
                width: 36px;
                height: 36px;
                padding: 0.375rem;
            }
        }

        /* Accessibility improvements */
        .form-control:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .btn-login:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Print styles */
        @media print {
            body {
                background: white;
            }

            .login-container {
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-left">
            <div class="content">
                <h1>SIPRESCA</h1>
                <p>Sistem Presensi<br>PT. Cargomas Cakrawala</p>
                <div class="company-info">
                    <i class="bi bi-person-fill"></i> Selamat Datang
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="login-form">
                <h2 class="login-title">Selamat Datang</h2>
                <p class="login-subtitle">Masuk ke akun SIPRESCA Anda</p>

                <!-- Laravel error messages -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <i class="bi bi-person-fill"></i>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email / Username" required>
                    </div>
                    <div class="form-group mb-4">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <button type="button" class="toggle-password" id="togglePassword" tabindex="-1">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                    <button type="submit" class="btn btn-login w-100">Login</button>
                </form>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Butuh bantuan? Hubungi administrator sistem
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = togglePassword.querySelector('i');

            togglePassword.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle icon
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');

                // Keep focus on password input
                passwordInput.focus();
            });

            // Prevent form submission when clicking toggle button
            togglePassword.addEventListener('mousedown', function(e) {
                e.preventDefault();
            });
        });
    </script>
</body>

</html>
