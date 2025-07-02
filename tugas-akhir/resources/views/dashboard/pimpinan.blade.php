@extends('layouts.pimpinan-app')

@section('title', 'Dashboard Pimpinan')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Dashboard Pimpinan</h1>

    {{-- Filter --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-2">
            <select name="bulan" class="form-select">
                @foreach($bulanList as $key => $val)
                    <option value="{{ $key }}" {{ $key == $bulan ? 'selected' : '' }}>{{ $val }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="tahun" class="form-select">
                @foreach($tahunList as $key => $val)
                    <option value="{{ $key }}" {{ $key == $tahun ? 'selected' : '' }}>{{ $val }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="filter_type" class="form-select">
                <option value="hari_ini" {{ request('filter_type') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                <option value="bulanan" {{ request('filter_type') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="nama" class="form-control" placeholder="Cari nama" value="{{ request('nama') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Filter Status --</option>
                <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah Absen</option>
                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Absen</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">Filter</button>
        </div>
    </form>

    {{-- Statistik --}}
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Karyawan</h5>
                    <h2 class="text-primary">{{ $statistik['total_karyawan'] }}</h2>
                </div>
            </div>
        </div>

        @if($filterType === 'bulanan')
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Absen {{ $bulanList[$bulan] }}</h5>
                        <h2 class="text-info">{{ $statistik['total_absen'] }}</h2>
                        <small class="text-muted">{{ $statistik['unique_users'] }} karyawan unik</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Hadir {{ $bulanList[$bulan] }}</h5>
                        <h2 class="text-success">{{ $statistik['hadir'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Terlambat {{ $bulanList[$bulan] }}</h5>
                        <h2 class="text-warning">{{ $statistik['terlambat'] }}</h2>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Hadir Hari Ini</h5>
                        <h2 class="text-success">{{ $statistik['hadir'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Terlambat Hari Ini</h5>
                        <h2 class="text-warning">{{ $statistik['terlambat'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Belum Absen</h5>
                        <h2 class="text-danger">{{ $statistik['belum_absen'] }}</h2>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Statistik tambahan untuk bulanan --}}
    @if($filterType === 'bulanan')
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Izin {{ $bulanList[$bulan] }}</h5>
                    <h2 class="text-info">{{ $statistik['izin'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Sakit {{ $bulanList[$bulan] }}</h5>
                    <h2 class="text-secondary">{{ $statistik['sakit'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Rata-rata per Hari</h5>
                    <h2 class="text-primary">{{ $statistik['total_absen'] > 0 ? round($statistik['total_absen'] / now()->day, 1) : 0 }}</h2>
                    <small class="text-muted">absen per hari</small>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Grafik --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Grafik Absensi {{ $bulanList[$bulan] }} {{ $tahun }}</h5>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Distribusi Status {{ $bulanList[$bulan] }}</h5>
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Absensi --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">
                @if($filterType === 'bulanan')
                    Absensi {{ $bulanList[$bulan] }} {{ $tahun }}
                @else
                    Absensi Hari Ini
                @endif

                @if($statusFilter == 'sudah')
                    - Sudah Absen
                @elseif($statusFilter == 'belum')
                    - Belum Absen
                @endif
            </h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Waktu Absen</th>
                            @if($filterType === 'bulanan')
                                <th>Tanggal</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensiData as $index => $absen)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $absen->user->name ?? '-' }}</td>
                                <td>
                                    @if($absen->status == 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($absen->status == 'terlambat')
                                        <span class="badge bg-warning">Terlambat</span>
                                    @elseif($absen->status == 'izin')
                                        <span class="badge bg-info">Izin</span>
                                    @elseif($absen->status == 'sakit')
                                        <span class="badge bg-secondary">Sakit</span>
                                    @else
                                        <span class="badge bg-danger">Belum Absen</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $absen->created_at ? $absen->created_at->format('H:i:s') : '-' }}
                                </td>
                                @if($filterType === 'bulanan')
                                    <td>
                                        {{ $absen->created_at ? $absen->created_at->format('d/m/Y') : '-' }}
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $filterType === 'bulanan' ? '5' : '4' }}" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Bonus --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Bonus Karyawan {{ $bulanList[$bulan] }} {{ $tahun }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah Bonus</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonus as $index => $b)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $b->user->name ?? '-' }}</td>
                                <td>Rp {{ number_format($b->jumlah_bonus, 0, ',', '.') }}</td>
                                <td>{{ $b->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data bonus</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Jumlah Absen',
                data: {!! json_encode($chartData['data']) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
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
                    '#28a745',
                    '#17a2b8',
                    '#6c757d',
                    '#ffc107'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
