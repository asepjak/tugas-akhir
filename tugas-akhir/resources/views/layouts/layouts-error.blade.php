<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404 - Halaman Tidak Ditemukan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .error-container {
            height: 100vh;
        }
        .error-box {
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center error-container">
        <div class="text-center error-box p-4 shadow bg-white rounded">
            <h1 class="display-1 text-danger">404</h1>
            <h4 class="mb-3">Halaman Tidak Ditemukan</h4>
            <p class="text-muted">Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>
            <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
