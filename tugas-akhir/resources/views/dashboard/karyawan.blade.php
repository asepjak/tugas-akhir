<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyawan Dashboard - PT. CARGOMAS CAKRAWALA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">PT. CARGOMAS CAKRAWALA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Absensi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Riwayat</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Dashboard Karyawan</h1>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Absensi Hari Ini</h5>
                        <p class="card-text">Tanggal: {{ date('d F Y') }}</p>
                        <p class="card-text">Status: <span class="badge bg-success">Hadir</span></p>
                        <p class="card-text">Jam Masuk: 08:00</p>
                        <p class="card-text">Jam Keluar: -</p>
                        <button class="btn btn-primary">Absen Keluar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Absensi Bulan Ini</h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Total Hari Kerja</td>
                                    <td>22</td>
                                </tr>
                                <tr>
                                    <td>Hadir</td>
                                    <td>18</td>
                                </tr>
                                <tr>
                                    <td>Terlambat</td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>Tidak Hadir</td>
                                    <td>2</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
