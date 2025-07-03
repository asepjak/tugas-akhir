@extends('layouts.pimpinan-app')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800 fw-bold">Dashboard Pimpinan</h1>
                <p class="text-muted mb-0">Monitoring absensi karyawan secara real-time</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
                <button class="btn btn-primary btn-sm" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3 text-primary">
                    <i class="fas fa-filter me-2"></i>Filter & Pencarian
                </h6>
                <form method="GET" class="row g-3">
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label small text-muted">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            @foreach ($bulanList as $key => $val)
                                <option value="{{ $key }}" {{ $key == $bulan ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label small text-muted">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            @foreach ($tahunList as $key => $val)
                                <option value="{{ $key }}" {{ $key == $tahun ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label small text-muted">Tampilkan</label>
                        <select name="view_type" class="form-select form-select-sm">
                            <option value="monthly" {{ request('view_type') == 'monthly' ? 'selected' : '' }}>Total per
                                Bulan</option>
                            <option value="daily" {{ request('view_type') == 'daily' ? 'selected' : '' }}>Hari Ini</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small text-muted">Cari Nama</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="nama" class="form-control" placeholder="Nama karyawan"
                                value="{{ request('nama') }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat
                            </option>
                            <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <label class="form-label small text-muted d-block">&nbsp;</label>
                        <button class="btn btn-primary btn-sm w-100" type="submit">
                            <i class="fas fa-filter me-1"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-users text-primary fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 text-muted">Total Karyawan</h6>
                                <h3 class="mb-0 fw-bold text-primary">{{ $statistik['total_karyawan'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (request('view_type') == 'monthly' || !request('view_type'))
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-calendar-check text-info fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Total Absen {{ $bulanList[$bulan] }}</h6>
                                    <h3 class="mb-0 fw-bold text-info">{{ $statistik['total_absen'] }}</h3>
                                    <small class="text-muted">{{ $statistik['unique_users'] }} karyawan unik</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-check-circle text-success fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Hadir {{ $bulanList[$bulan] }}</h6>
                                    <h3 class="mb-0 fw-bold text-success">{{ $statistik['hadir'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-clock text-warning fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Terlambat {{ $bulanList[$bulan] }}</h6>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $statistik['terlambat'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-check-circle text-success fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Hadir Hari Ini</h6>
                                    <h3 class="mb-0 fw-bold text-success">{{ $statistik['hadir'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-clock text-warning fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Terlambat Hari Ini</h6>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $statistik['terlambat'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-times-circle text-danger fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Belum Absen</h6>
                                    <h3 class="mb-0 fw-bold text-danger">{{ $statistik['belum_absen'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Additional Statistics for Monthly View -->
        @if (request('view_type') == 'monthly' || !request('view_type'))
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-file-medical text-info fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Izin {{ $bulanList[$bulan] }}</h6>
                                    <h3 class="mb-0 fw-bold text-info">{{ $statistik['izin'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-secondary bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-user-injured text-secondary fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Sakit {{ $bulanList[$bulan] }}</h6>
                                    <h3 class="mb-0 fw-bold text-secondary">{{ $statistik['sakit'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-chart-line text-primary fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Rata-rata per Hari</h6>
                                    <h3 class="mb-0 fw-bold text-primary">
                                        {{ $statistik['total_absen'] > 0 ? round($statistik['total_absen'] / now()->day, 1) : 0 }}
                                    </h3>
                                    <small class="text-muted">absen per hari</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-percentage text-success fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Tingkat Kehadiran</h6>
                                    <h3 class="mb-0 fw-bold text-success">
                                        {{ $statistik['total_absen'] > 0 ? round(($statistik['hadir'] / $statistik['total_absen']) * 100, 1) : 0 }}%
                                    </h3>
                                    <small class="text-muted">bulan ini</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Charts Section -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-chart-bar me-2"></i>
                                @if (request('view_type') == 'monthly' || !request('view_type'))
                                    Grafik Absensi {{ $bulanList[$bulan] }} {{ $tahun }}
                                @else
                                    Grafik Absensi Hari Ini
                                @endif
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i
                                                class="fas fa-download me-2"></i>Download</a></li>
                                    <li><a class="dropdown-item" href="#"><i
                                                class="fas fa-share me-2"></i>Share</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="barChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-chart-pie me-2"></i>
                            Distribusi Status
                            @if (request('view_type') == 'monthly' || !request('view_type'))
                                {{ $bulanList[$bulan] }}
                            @else
                                Hari Ini
                            @endif
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Attendance Summary Table -->
        @if (request('view_type') == 'monthly' || !request('view_type'))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Ringkasan Absensi per Karyawan - {{ $bulanList[$bulan] }} {{ $tahun }}
                            @if (request('status'))
                                <span class="badge bg-primary ms-2">{{ ucfirst(request('status')) }}</span>
                            @endif
                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="monthlyAttendanceTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-3 py-3 text-muted small">No</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Nama Karyawan</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Total Absen</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Hadir</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Terlambat</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Izin</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Sakit</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyAttendanceData as $index => $data)
                                    <tr>
                                        <td class="px-3 py-3 text-muted">{{ $index + 1 }}</td>
                                        <td class="px-3 py-3">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <span class="fw-medium">{{ $data['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="fw-bold text-primary">{{ $data['total_absen'] }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-success fw-medium">{{ $data['hadir'] }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-warning fw-medium">{{ $data['terlambat'] }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-info fw-medium">{{ $data['izin'] }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-secondary fw-medium">{{ $data['sakit'] }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            @php
                                                $percentage =
                                                    $data['total_absen'] > 0
                                                        ? round(($data['hadir'] / $data['total_absen']) * 100, 1)
                                                        : 0;
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-2" style="width: 60px; height: 8px;">
                                                    <div class="progress-bar bg-{{ $percentage >= 80 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger') }}"
                                                        role="progressbar" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span
                                                    class="text-{{ $percentage >= 80 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger') }} fw-medium">
                                                    {{ $percentage }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Tidak ada data absensi untuk bulan ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <!-- Daily Attendance Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-table me-2"></i>
                            Daftar Absensi Hari Ini
                            @if (request('status'))
                                <span class="badge bg-primary ms-2">{{ ucfirst(request('status')) }}</span>
                            @endif
                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dailyAttendanceTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-3 py-3 text-muted small">No</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Nama Karyawan</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Status</th>
                                    <th class="border-0 px-3 py-3 text-muted small">Waktu Absen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensiData as $index => $absen)
                                    <tr>
                                        <td class="px-3 py-3 text-muted">{{ $index + 1 }}</td>
                                        <td class="px-3 py-3">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <span class="fw-medium">{{ $absen->user->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            @if ($absen->status == 'hadir')
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success-subtle">
                                                    <i class="fas fa-check-circle me-1"></i>Hadir
                                                </span>
                                            @elseif($absen->status == 'terlambat')
                                                <span
                                                    class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                                    <i class="fas fa-clock me-1"></i>Terlambat
                                                </span>
                                            @elseif($absen->status == 'izin')
                                                <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                    <i class="fas fa-file-medical me-1"></i>Izin
                                                </span>
                                            @elseif($absen->status == 'sakit')
                                                <span
                                                    class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                                    <i class="fas fa-user-injured me-1"></i>Sakit
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                    <i class="fas fa-times-circle me-1"></i>Belum Absen
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">
                                                {{ $absen->created_at ? $absen->created_at->format('H:i:s') : '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Tidak ada data absensi hari ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Bonus Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-gift me-2"></i>Bonus Karyawan {{ $bulanList[$bulan] }} {{ $tahun }}
                    </h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="addBonus()">
                            <i class="fas fa-plus me-1"></i> Tambah Bonus
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-3 py-3 text-muted small">No</th>
                                <th class="border-0 px-3 py-3 text-muted small">Nama Karyawan</th>
                                <th class="border-0 px-3 py-3 text-muted small">Jumlah Bonus</th>
                                <th class="border-0 px-3 py-3 text-muted small">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bonusData as $index => $bonus)
                                <tr>
                                    <td class="px-3 py-3 text-muted">{{ $index + 1 }}</td>
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <span class="fw-medium">{{ $bonus->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="fw-bold text-success">Rp
                                            {{ number_format($bonus->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-muted">{{ $bonus->description ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-gift fa-2x mb-2 d-block"></i>
                                        Belum ada bonus untuk bulan ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 text-primary">
                    <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @forelse($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $activity->type == 'login' ? 'success' : 'primary' }}">
                                <i class="fas fa-{{ $activity->type == 'login' ? 'sign-in-alt' : 'clock' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $activity->user->name ?? 'Unknown' }}</h6>
                                <p class="text-muted mb-1">{{ $activity->description }}</p>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-history fa-2x mb-2 d-block"></i>
                            Belum ada aktivitas terbaru
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bonus Modal -->
    <div class="modal fade" id="addBonusModal" tabindex="-1" aria-labelledby="addBonusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBonusModalLabel">Tambah Bonus Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addBonusForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Karyawan</label>
                            <select class="form-select" id="employee_id" name="employee_id" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bonus_amount" class="form-label">Jumlah Bonus</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="bonus_amount" name="amount" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="bonus_description" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="bonus_description" name="description" rows="3"
                                placeholder="Keterangan bonus..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Bonus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -18px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #007bff;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                border: 1px solid #dee2e6 !important;
                box-shadow: none !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Charts
            initializeCharts();

            // Auto refresh every 5 minutes
            setInterval(function() {
                if ({{ request('view_type') == 'daily' ? 'true' : 'false' }}) {
                    location.reload();
                }
            }, 300000);
        });

        function initializeCharts() {
            // Bar Chart
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Jumlah Absensi',
                        data: @json($chartData['data']),
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
                type: 'doughnut',
                data: {
                    labels: @json($pieChartData['labels']),
                    datasets: [{
                        data: @json($pieChartData['data']),
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(23, 162, 184, 0.8)',
                            'rgba(108, 117, 125, 0.8)',
                            'rgba(220, 53, 69, 0.8)'
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(23, 162, 184, 1)',
                            'rgba(108, 117, 125, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        function addBonus() {
            const modal = new bootstrap.Modal(document.getElementById('addBonusModal'));
            modal.show();
        }

        function exportToExcel() {
            const table = document.querySelector('table');
            const wb = XLSX.utils.table_to_book(table, {
                sheet: "Absensi"
            });
            XLSX.writeFile(wb, 'absensi_' + new Date().toISOString().split('T')[0] + '.xlsx');
        }

        function exportToPDF() {
            window.print();
        }

        // Handle bonus form submission
        document.getElementById('addBonusForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('pimpinan.bonus.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambah bonus');
                });
        });
    </script>

    <!-- Include Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <!-- Include XLSX for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

@endsection
