<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT. CARGOMAS CAKRAWALA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background-color: white;
            border-radius: 0;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }
        .login-row {
            display: flex;
            flex-wrap: wrap;
        }
        .login-left {
            flex: 1;
            padding: 6rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-right {
            flex: 1;
            padding: 3rem;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            line-height: 1.2;
        }
        .btn-login {
            background-color: #000;
            color: white;
            border: none;
            border-radius: 0;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        .btn-login:hover {
            background-color: #333;
        }
        .form-control {
            border-radius: 0;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 768px) {
            .login-left, .login-right {
                flex: 0 0 100%;
            }
            .login-left {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-row">
                <div class="login-left">
                    <h1 class="login-title">ABSENSI<br>KARYAWAN<br>PT. CARGOMAS<br>CAKRAWALA</h1>
                </div>
                <div class="login-right">
                    <div class="login-form">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="email">Username</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                            </div>
                            <button type="submit" class="btn btn-login">Login →</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
