@extends('layouts.admin-app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header dengan Greeting -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient-primary text-white rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="fw-bold mb-1">Selamat Datang, Admin! 👋</h2>
                                <p class="mb-0 opacity-75">Dashboard Sistem Absensi - {{ now()->format('l, d F Y') }}</p>
                            </div>
                            <div class="col-md-4 text-end d-none d-md-block">
                                <i class="bi bi-speedometer2 fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Ringkasan dengan Animasi -->
        <div class="row mb-4 g-3">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden stat-card">
                    <div class="card-body p-4 position-relative">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-primary fw-semibold mb-1">Total Karyawan</h6>
                                <h2 class="fw-bold text-dark mb-0">{{ $totalKaryawan }}</h2>
                                <small class="text-muted">
                                    <i class="bi bi-people-fill me-1"></i>
                                    Karyawan Aktif
                                </small>
                            </div>
                            <div class="col-4 text-end">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex">
                                    <i class="bi bi-people-fill text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-gradient-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden stat-card">
                    <div class="card-body p-4 position-relative">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-success fw-semibold mb-1">Hadir Hari Ini</h6>
                                <h2 class="fw-bold">{{ $hadirHariIni + $terlambatHariIni }}</h2>
                                <small class="text-muted">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Sudah Absen
                                </small>
                            </div>
                            <div class="col-4 text-end">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-flex">
                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-gradient-success"
                                style="width: {{ $totalKaryawan > 0 ? ($hadirHariIni / $totalKaryawan) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden stat-card">
                    <div class="card-body p-4 position-relative">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-warning fw-semibold mb-1">Terlambat</h6>
                                <h2 class="fw-bold text-dark mb-0">{{ $terlambatHariIni }}</h2>
                                <small class="text-muted">
                                    <i class="bi bi-clock-fill me-1"></i>
                                    Hari Ini
                                </small>
                            </div>
                            <div class="col-4 text-end">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-flex">
                                    <i class="bi bi-clock-fill text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-gradient-warning"
                                style="width: {{ $totalKaryawan > 0 ? ($terlambatHariIni / $totalKaryawan) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden stat-card">
                    <div class="card-body p-4 position-relative">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-danger fw-semibold mb-1">Belum Absen</h6>
                                <h2 class="fw-bold text-dark mb-0">{{ $belumAbsenHariIni }}</h2>
                                <small class="text-muted">
                                    <i class="bi bi-x-circle-fill me-1"></i>
                                    Hari Ini
                                </small>
                            </div>
                            <div class="col-4 text-end">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-3 d-inline-flex">
                                    <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-gradient-danger"
                                style="width: {{ $totalKaryawan > 0 ? ($belumAbsenHariIni / $totalKaryawan) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Filter dengan Desain Modern -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-transparent border-0 p-4 pb-0">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-funnel-fill me-2 text-primary"></i>
                            Filter & Pencarian
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-muted">Cari Nama Karyawan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" name="nama" class="form-control border-start-0 bg-light"
                                        placeholder="Masukkan nama..." value="{{ request('nama') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">Bulan</label>
                                <select name="bulan" class="form-select bg-light">
                                    @foreach ($bulanList as $key => $val)
                                        <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                            {{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">Status Absensi</label>
                                <select name="status" class="form-select bg-light">
                                    <option value="">Semua Status</option>
                                    <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah
                                        Absen</option>
                                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum
                                        Absen</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold text-muted">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                                    <i class="bi bi-search me-1"></i>
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absensi Hari Ini dengan Desain Menarik -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-gradient-primary text-white border-0 p-4 rounded-top-4">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="fw-bold mb-1">
                                    <i class="bi bi-calendar-check-fill me-2"></i>
                                    Absensi Hari Ini
                                </h5>
                                <p class="mb-0 opacity-75">{{ now()->format('l, d F Y') }}</p>
                            </div>
                            <div class="col-auto d-none d-md-block">
                                <div class="bg-white bg-opacity-20 rounded-3 px-3 py-2">
                                    <i class="bi bi-clock fs-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if ($absensiHariIni->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted opacity-25"></i>
                                <p class="text-muted mt-3 mb-0">Belum ada data absensi hari ini</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach ($absensiHariIni as $absen)
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="card border-0 bg-light rounded-3 h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 45px; height: 45px;">
                                                            <i class="bi bi-person-fill text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1">
                                                            {{ $absen->user->nama ?? $absen->user->name }}</h6>
                                                        <div class="d-flex align-items-center text-muted small">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ $absen->created_at ? $absen->created_at->format('H:i') : '--:--' }}
                                                            WIB
                                                        </div>
                                                    </div>
                                                    <div class="ms-2">
                                                        <span
                                                            class="badge {{ $absen->status == 'belum absen' ? 'bg-danger' : 'bg-success' }} px-3 py-2">
                                                            {{ ucfirst($absen->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik dengan Layout Responsif -->
        <div class="row g-4">
            <!-- Grafik Batang -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-gradient-success text-white border-0 p-4 rounded-top-4">
                        <h5 class="fw-bold mb-0d">
                            <i class="bi bi-bar-chart-fill me-2"></i>
                            Grafik Absensi Bulanan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="absenChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik Pie Chart -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-gradient-info text-white border-0 p-4 rounded-top-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-pie-chart-fill me-2"></i>
                            Distribusi Status
                        </h5>
                    </div>
                    <div class="card-body p-4 d-flex align-items-center">
                        <canvas id="pieChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        .stat-card {
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        }

        .progress-bar {
            border-radius: 10px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .card {
            transition: all 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .avatar-circle {
            transition: transform 0.3s ease;
        }

        .avatar-circle:hover {
            transform: scale(1.1);
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Konfigurasi Chart.js dengan tema modern
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#6c757d';

        // Bar Chart dengan gradient
        const ctxBar = document.getElementById('absenChart').getContext('2d');
        const gradient = ctxBar.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
        gradient.addColorStop(1, 'rgba(102, 126, 234, 0.1)');

        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Jumlah Absensi',
                    data: @json($chartData['data']),
                    backgroundColor: gradient,
                    borderColor: '#667eea',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            color: '#6c757d'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6c757d'
                        }
                    }
                },
                elements: {
                    bar: {
                        tension: 0.4
                    }
                }
            }
        });

        // Pie Chart dengan warna modern
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Terlambat'],
                datasets: [{
                    data: [
                        {{ $pieChart['hadir'] }},
                        {{ $pieChart['izin'] }},
                        {{ $pieChart['sakit'] }},
                        {{ $pieChart['terlambat'] }}
                    ],
                    backgroundColor: [
                        '#4facfe',
                        '#ffc107',
                        '#0dcaf0',
                        '#ff6b6b'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        displayColors: true
                    }
                }
            }
        });

        // Animasi loading untuk statistik
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
@endsection
