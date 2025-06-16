@extends('layouts.admin-app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Absensi Hari Ini -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-calendar-check-fill me-2"></i>
                        Absensi Hari Ini ({{ now()->format('d M Y') }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($absensiHariIni->isEmpty())
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-info-circle"></i> Belum ada absensi hari ini.
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($absensiHariIni as $absen)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $absen->user->nama ?? $absen->user->name }}</strong><br>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $absen->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success text-uppercase px-3 py-2">{{ $absen->status }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grafik Absensi Bulanan -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-bar-chart-fill me-2"></i>
                        Grafik Absensi Bulanan
                    </h6>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                        <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach($bulanList as $key => $bulanNama)
                                <option value="{{ $key }}" {{ $key == $bulanSekarang ? 'selected' : '' }}>
                                    {{ $bulanNama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="absenChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('absenChart').getContext('2d');
    const absenChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Jumlah Absensi',
                data: {!! json_encode($chartData['data']) !!},
                backgroundColor: 'rgba(25, 135, 84, 0.8)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` ${context.raw} Absensi`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Absensi'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                }
            }
        }
    });
</script>
@endsection
