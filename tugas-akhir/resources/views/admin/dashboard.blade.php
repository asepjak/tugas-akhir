@extends('layouts.admin-app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Dashboard Absensi</h2>
                    <p class="text-muted mb-0">Kelola dan pantau absensi karyawan</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ now()->format('d F Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Absensi Hari Ini -->
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white border-0 position-relative">
                    <div class="position-absolute top-0 end-0 opacity-25">
                        <i class="bi bi-calendar-check-fill" style="font-size: 4rem;"></i>
                    </div>
                    <div class="position-relative">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-calendar-check-fill me-2"></i>
                            Absensi Hari Ini
                        </h5>
                        <small class="opacity-75">{{ now()->format('d M Y') }}</small>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-body bg-light-subtle border-bottom">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3">
                        <div class="col-md-5">
                            <div class="form-floating">
                                <input type="text" name="nama" class="form-control"
                                       placeholder="Cari nama..." id="floatingName"
                                       value="{{ is_string(request('nama')) ? request('nama') : '' }}">
                                <label for="floatingName">
                                    <i class="bi bi-search me-1"></i>Cari Nama
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select name="bulan" class="form-select" id="floatingMonth">
                                    @foreach ($bulanList as $key => $val)
                                        <option value="{{ $key }}" {{ (isset($bulan) && $bulan == $key) ? 'selected' : '' }}>
                                            {{ is_string($val) ? $val : $key }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="floatingMonth">
                                    <i class="bi bi-calendar-month me-1"></i>Pilih Bulan
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-filter me-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @if ($absensiHariIni->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h6 class="text-muted mb-2">Belum Ada Absensi</h6>
                            <p class="text-muted small mb-0">Data absensi hari ini belum tersedia</p>
                        </div>
                    @else
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Total: {{ is_countable($absensiHariIni) ? $absensiHariIni->count() : 0 }} absensi</span>
                            <div class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i>Aktif
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            @foreach ($absensiHariIni as $index => $absen)
                                <div class="list-group-item border-0 px-0 py-3 bg-transparent position-relative">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-person-fill text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold">
                                                {{ is_string($absen->user->nama ?? $absen->user->name) ? ($absen->user->nama ?? $absen->user->name) : 'Nama tidak tersedia' }}
                                            </h6>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $absen->created_at ? $absen->created_at->format('H:i') : '--:--' }} WIB
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">
                                                <i class="bi bi-check-circle me-1"></i>{{ is_string($absen->status) ? $absen->status : 'Hadir' }}
                                            </span>
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <hr class="my-3 opacity-25">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grafik Absensi Bulanan -->
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-header bg-gradient-success text-white border-0 position-relative">
                    <div class="position-absolute top-0 end-0 opacity-25">
                        <i class="bi bi-bar-chart-fill" style="font-size: 4rem;"></i>
                    </div>
                    <div class="position-relative">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-bar-chart-fill me-2"></i>
                            Grafik Absensi Bulanan
                        </h5>
                        <small class="opacity-75">Statistik per hari</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 350px;">
                        <canvas id="absenChart" class="w-100 h-100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart Absensi Per Orang -->
        @if ($pieChart)
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-gradient-info text-white border-0 position-relative">
                    <div class="position-absolute top-0 end-0 opacity-25">
                        <i class="bi bi-pie-chart-fill" style="font-size: 4rem;"></i>
                    </div>
                    <div class="position-relative">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-pie-chart-fill me-2"></i>
                            Distribusi Absensi - {{ isset($nama) && is_string($nama) ? $nama : 'Semua Karyawan' }}
                        </h5>
                        <small class="opacity-75">Persentase status absensi</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div style="height: 400px;">
                                <canvas id="pieChart" class="w-100 h-100"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="stats-summary">
                                <h6 class="text-muted mb-3">Ringkasan Data</h6>
                                @if(isset($pieChart) && is_array($pieChart))
                                    @foreach($pieChart as $status => $count)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small">{{ is_string($status) ? $status : 'Status' }}</span>
                                        <span class="badge bg-secondary">{{ is_numeric($count) ? $count : 0 }}</span>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Custom CSS -->
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #198754 0%, #157347 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.list-group-item {
    transition: background-color 0.2s ease;
}

.list-group-item:hover {
    background-color: rgba(13, 110, 253, 0.05) !important;
}

.badge {
    font-size: 0.8em;
    font-weight: 500;
}

.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.stats-summary {
    background: rgba(13, 110, 253, 0.05);
    padding: 1.5rem;
    border-radius: 0.5rem;
    border-left: 4px solid #0d6efd;
}
</style>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Bar Chart dengan validasi data
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('absenChart').getContext('2d');

        // Validasi data chart
        const chartLabels = @json($chartData['labels'] ?? []);
        const chartDataValues = @json($chartData['data'] ?? []);

        // Gradient untuk bar chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(25, 135, 84, 0.9)');
        gradient.addColorStop(0.5, 'rgba(25, 135, 84, 0.7)');
        gradient.addColorStop(1, 'rgba(25, 135, 84, 0.3)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Array.isArray(chartLabels) ? chartLabels : [],
                datasets: [{
                    label: 'Jumlah Absensi',
                    data: Array.isArray(chartDataValues) ? chartDataValues : [],
                    backgroundColor: gradient,
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 2,
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8
                    },
                    borderSkipped: false,
                    barThickness: 'flex',
                    maxBarThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                layout: {
                    padding: {
                        top: 20,
                        right: 20,
                        bottom: 20,
                        left: 20
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(25, 135, 84, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function (context) {
                                return ` ${context.raw} Absensi`;
                            }
                        },
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#495057',
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            maxRotation: 0
                        },
                        title: {
                            display: true,
                            text: 'Tanggal',
                            color: '#198754',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: 10
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            stepSize: 1,
                            color: '#495057',
                            font: {
                                size: 12
                            },
                            padding: 10
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Absensi',
                            color: '#198754',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: 10
                        }
                    }
                }
            }
        });
    });

    // Pie Chart dengan validasi data
    @if (isset($pieChart) && is_array($pieChart) && !empty($pieChart))
    const ctxPie = document.getElementById('pieChart').getContext('2d');

    // Validasi data pie chart
    const pieLabels = @json(array_keys($pieChart));
    const pieData = @json(array_values($pieChart));

    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: Array.isArray(pieLabels) ? pieLabels : [],
            datasets: [{
                data: Array.isArray(pieData) ? pieData : [],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545',
                    '#6f42c1',
                    '#20c997'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverBorderWidth: 4,
                hoverBorderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 13,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8,
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.raw / total) * 100).toFixed(1);
                            return ` ${context.label}: ${context.raw} (${percentage}%)`;
                        }
                    }
                }
            },
            elements: {
                arc: {
                    borderWidth: 0
                }
            }
        }
    });
    @endif
</script>
@endsection
