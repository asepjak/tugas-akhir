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
            --primary-color: #3a3a3a;
            --secondary-color: #1a1a1a;
            --accent-color: #ff6b00;
            --text-dark: #2d2d2d;
            --text-light: #f8f9fa;
            --text-muted: #888888;
            --border-color: #e0e0e0;
            --shadow-light: rgba(0, 0, 0, 0.05);
            --shadow-medium: rgba(0, 0, 0, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            padding: 1rem;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Heavy equipment decorative elements */
        .bg-equipment {
            position: absolute;
            opacity: 0.03;
            z-index: 0;
            pointer-events: none;
        }

        .bg-excavator {
            bottom: -50px;
            left: -50px;
            width: 300px;
            height: 300px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23000000'%3E%3Cpath d='M18 4V3c0-.55-.45-1-1-1H5c-.55 0-1 .45-1 1v1c0 .55.45 1 1 1h12c.55 0 1-.45 1-1zm1 3H5c-1.1 0-2 .9-2 2v9c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm-1 10H6c-.55 0-1-.45-1-1v-7h14v7c0 .55-.45 1-1 1z'/%3E%3Cpath d='M8 18h1v-7H8v7zm5 0h1v-7h-1v7zm-2.5-9.5h2v2h-2v-2zm0-3h2v2h-2v-2z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            transform: rotate(-15deg);
        }

        .bg-bulldozer {
            top: 50px;
            right: -50px;
            width: 250px;
            height: 250px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23000000'%3E%3Cpath d='M17 16h-2v-1H9v1H7v-1H2v5h20v-5h-5v1zm0-9V7H7v1H2v5h5v-1h2v1h6v-1h2v1h5V8h-5z'/%3E%3Cpath d='M15 5h-1V2h-4v3h-1V1h6v4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            transform: rotate(15deg);
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            max-width: 1000px;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .login-left {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            padding: 4rem 3rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            background-image:
                linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(58, 58, 58, 0.9)),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ff6b00' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--accent-color);
        }

        .login-left h1 {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
            color: white;
        }

        .login-left p {
            font-size: 1.1rem;
            font-weight: 400;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .login-left .company-info {
            margin-top: 3rem;
            font-size: 0.9rem;
            opacity: 0.8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-left .company-info i {
            color: var(--accent-color);
        }

        .login-right {
            flex: 1;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            padding-left: 3rem;
            padding-right: 3rem;
            border: 1px solid var(--border-color);
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: #f8f9fa;
            width: 100%;
            height: 52px;
            line-height: 1.5;
            font-weight: 400;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(255, 107, 0, 0.2);
            background-color: white;
            outline: none;
        }

        .form-control:hover:not(:focus) {
            border-color: var(--accent-color);
            background-color: white;
        }

        .form-group .bi {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.1rem;
            color: var(--text-muted);
            transition: color 0.2s ease;
            z-index: 2;
            pointer-events: none;
        }

        .form-group:focus-within .bi {
            color: var(--accent-color);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.1rem;
            color: var(--text-muted);
            transition: all 0.2s ease;
            padding: 0.5rem;
            border-radius: 8px;
            z-index: 3;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }

        .toggle-password:hover {
            color: var(--accent-color);
            background-color: rgba(255, 107, 0, 0.1);
        }

        .toggle-password:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 107, 0, 0.3);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            padding: 0.875rem 2rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        .alert-danger i {
            margin-right: 8px;
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
                border-radius: 12px;
            }

            .login-left {
                padding: 3rem 2rem 2rem 2rem;
            }

            .login-left h1 {
                font-size: 2rem;
            }

            .login-right {
                padding: 2.5rem 2rem;
            }

            .login-title {
                font-size: 1.6rem;
            }

            .bg-equipment {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .login-left,
            .login-right {
                padding: 2rem 1.5rem;
            }

            .login-left h1 {
                font-size: 1.8rem;
            }

            .form-control {
                padding: 0.875rem 1rem;
                padding-left: 2.75rem;
                padding-right: 2.75rem;
                font-size: 0.9rem;
                height: 48px;
            }

            .form-group .bi {
                left: 0.85rem;
                font-size: 1rem;
            }

            .toggle-password {
                right: 0.85rem;
                font-size: 1rem;
                width: 32px;
                height: 32px;
                padding: 0.375rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-form {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Utility classes */
        .text-accent {
            color: var(--accent-color);
        }

        .opacity-75 {
            opacity: 0.75;
        }
    </style>
</head>

<body>
    <!-- Decorative heavy equipment elements -->
    <div class="bg-equipment bg-excavator"></div>
    <div class="bg-equipment bg-bulldozer"></div>

    <div class="login-container">
        <div class="login-left">
            <div class="content">
                <h1>SIPRESCA</h1>
                <p>Sistem Presensi Digital<br>PT. Cargomas Cakrawala</p>
                <div class="company-info">
                    <i class="bi bi-gear-fill"></i> <span class="opacity-75">Solusi Presensi Alat Berat</span>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="login-form">
                <h2 class="login-title">Masuk ke Akun</h2>
                <p class="login-subtitle">Gunakan kredensial Anda untuk mengakses sistem</p>

                <!-- Laravel error messages -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li><i class="bi bi-exclamation-circle"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <i class="bi bi-person-fill"></i>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Email / Username" required>
                    </div>
                    <div class="form-group mb-4">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password" required>
                        <button type="button" class="toggle-password" id="togglePassword" tabindex="-1">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                    <button type="submit" class="btn btn-login w-100">
                        <span class="btn-text">Login</span>
                    </button>
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
