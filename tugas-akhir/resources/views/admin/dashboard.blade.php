@extends('layouts.admin-app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Absensi Hari Ini -->
        <div class="col-md-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-light-subtle rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h6 class="mb-0">
                        <i class="bi bi-calendar-check-fill me-2"></i>
                        Absensi Hari Ini ({{ now()->format('d M Y') }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($absensiHariIni->isEmpty())
                        <div class="alert alert-warning mb-0 rounded-3">
                            <i class="bi bi-info-circle"></i> Belum ada absensi hari ini.
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($absensiHariIni as $absen)
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0 px-0 py-2">
                                    <div>
                                        <strong>{{ $absen->user->nama ?? $absen->user->name }}</strong><br>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $absen->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success text-uppercase px-3 py-2 rounded-pill shadow-sm">{{ $absen->status }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grafik Absensi Bulanan -->
        <div class="col-md-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-light-subtle rounded-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h6 class="mb-0">
                        <i class="bi bi-bar-chart-fill me-2"></i>
                        Grafik Absensi Bulanan
                    </h6>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center ms-3">
                        <select name="bulan" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                            @foreach($bulanList as $key => $bulanNama)
                                <option value="{{ $key }}" {{ $key == $bulanSekarang ? 'selected' : '' }}>
                                    {{ $bulanNama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px;">
                        <canvas id="absenChart" class="w-100 h-100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('absenChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(25, 135, 84, 0.9)');
        gradient.addColorStop(1, 'rgba(25, 135, 84, 0.2)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [{
                    label: 'Jumlah Absensi',
                    data: {!! json_encode($chartData['data']) !!},
                    backgroundColor: gradient,
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    borderRadius: {
                        topLeft: 10,
                        topRight: 10
                    },
                    barThickness: 'flex'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 15
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#198754',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 6,
                        padding: 10,
                        callbacks: {
                            label: function (context) {
                                return ` ${context.raw} Absensi`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#333',
                            font: { size: 12, weight: '600' }
                        },
                        title: {
                            display: true,
                            text: 'Tanggal',
                            color: '#198754',
                            font: { size: 14, weight: 'bold' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#333',
                            font: { size: 12 }
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Absensi',
                            color: '#198754',
                            font: { size: 14, weight: 'bold' }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
