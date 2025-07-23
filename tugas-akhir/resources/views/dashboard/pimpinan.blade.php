@extends('layouts.pimpinan-app')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 mb-1 fw-bold text-primary">Dashboard Pimpinan</h1>
                <p class="text-muted mb-0">Monitoring real-time kehadiran dan produktivitas tim</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm px-3" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
                <button class="btn btn-primary btn-sm px-3" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-filter text-primary me-2 fs-5"></i>
                    <h6 class="mb-0 fw-semibold">Filter Data</h6>
                </div>
                <form method="GET" class="row g-3">
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label small fw-semibold">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm shadow-none">
                            @foreach ($bulanList as $key => $val)
                                <option value="{{ $key }}" {{ $key == $bulan ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label small fw-semibold">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm shadow-none">
                            @foreach ($tahunList as $key => $val)
                                <option value="{{ $key }}" {{ $key == $tahun ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label small fw-semibold">Tampilan</label>
                        <select name="view_type" class="form-select form-select-sm shadow-none">
                            <option value="monthly" {{ $viewType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="daily" {{ $viewType == 'daily' ? 'selected' : '' }}>Harian</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-semibold">Cari Karyawan</label>
                        <div class="input-group input-group-sm shadow-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="nama" class="form-control border-start-0 shadow-none"
                                   placeholder="Nama karyawan" value="{{ $nama }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm shadow-none">
                            <option value="">Semua</option>
                            <option value="hadir" {{ $status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="terlambat" {{ $status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="izin" {{ $status == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ $status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            @if ($viewType == 'daily')
                                <option value="belum_absen" {{ $status == 'belum_absen' ? 'selected' : '' }}>Belum Absen</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex align-items-end">
                        <button class="btn btn-primary btn-sm w-100 shadow-sm" type="submit">
                            <i class="fas fa-filter me-1"></i> Terapkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Karyawan -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-users text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 text-muted small fw-semibold">Total Karyawan</h6>
                                <h3 class="mb-0 fw-bold">{{ $statistik['total_karyawan'] }}</h3>
                                <small class="text-muted">Total anggota tim</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($viewType == 'monthly')
                <!-- Monthly Stats -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-calendar-check text-info fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted small fw-semibold">Total Absen {{ $bulanList[$bulan] }}</h6>
                                    <h3 class="mb-0 fw-bold">{{ $statistik['total_absen'] }}</h3>
                                    <small class="text-muted">{{ $statistik['unique_users'] }} karyawan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-check-circle text-success fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted small fw-semibold">Kehadiran {{ $bulanList[$bulan] }}</h6>
                                    <h3 class="mb-0 fw-bold">{{ $statistik['hadir'] }}</h3>
                                    <div class="progress mt-2" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                             style="width: {{ $statistik['attendance_rate'] }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $statistik['attendance_rate'] }}% tingkat kehadiran</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-clock text-warning fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted small fw-semibold">Keterlambatan {{ $bulanList[$bulan] }}</h6>
                                    <h3 class="mb-0 fw-bold">{{ $statistik['terlambat'] }}</h3>
                                    <small class="text-muted">{{ $statistik['avg_per_day'] }} rata-rata/hari</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Daily Stats -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-check-circle text-success fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted small fw-semibold">Hadir Hari Ini</h6>
                                    <h3 class="mb-0 fw-bold">{{ $statistik['hadir'] }}</h3>
                                    <div class="progress mt-2" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                             style="width: {{ $statistik['attendance_rate'] }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $statistik['attendance_rate'] }}% dari total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-clock text-warning fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted small fw-semibold">Terlambat Hari Ini</h6>
                                    <h3 class="mb-0 fw-bold">{{ $statistik['terlambat'] }}</h3>
                                    <small class="text-muted text-warning">Perlu perhatian</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-heart text-info fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted small fw-semibold">Izin/Sakit Hari Ini</h6>
                                    <h3 class="mb-0 fw-bold">{{ $statistik['izin'] + $statistik['sakit'] }}</h3>
                                    <small class="text-muted">
                                        <span class="text-info">{{ $statistik['izin'] }} izin</span>,
                                        <span class="text-secondary">{{ $statistik['sakit'] }} sakit</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-user-clock text-danger fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted small fw-semibold">Belum Absen</h6>
                                    <h3 class="mb-0 fw-bold">{{ $statistik['belum_absen'] }}</h3>
                                    <small class="text-danger">Perlu tindak lanjut</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content Row -->
        <div class="row g-4 mb-4">
            <!-- Charts Column -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line text-primary me-2 fs-5"></i>
                            <h6 class="mb-0 fw-semibold">
                                Grafik Presensi
                                @if ($viewType == 'monthly')
                                    {{ $bulanList[$bulan] }} {{ $tahun }}
                                @else
                                    Hari Ini
                                @endif
                            </h6>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshChart()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <canvas id="attendanceChart" height="280"></canvas>
                    </div>
                </div>
            </div>

            <!-- Notifications Column -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-bell text-primary me-2 fs-5"></i>
                            <h6 class="mb-0 fw-semibold">Notifikasi Terbaru</h6>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshNotifications()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline">
                            @forelse($notifications as $notification)
                                <div class="timeline-item mb-3">
                                    <div class="timeline-marker {{ str_contains($notification['icon'], 'text-') ? str_replace('fas fa-', '', explode(' ', $notification['icon'])[1]) : 'bg-primary' }}"></div>
                                    <div class="timeline-content bg-light rounded p-3">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="{{ $notification['icon'] }} me-2 text-primary"></i>
                                            <h6 class="timeline-title mb-0 fw-semibold">{{ $notification['title'] }}</h6>
                                        </div>
                                        <p class="timeline-text mb-1 small">{{ $notification['message'] }}</p>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>{{ $notification['time']->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-bell-slash fa-2x mb-3 text-muted opacity-25"></i>
                                    <p class="mb-0">Tidak ada notifikasi terbaru</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables Section -->
        @if ($viewType == 'monthly')
            <!-- Monthly Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-table text-primary me-2 fs-5"></i>
                        <h6 class="mb-0 fw-semibold">
                            Rekap Bulanan {{ $bulanList[$bulan] }} {{ $tahun }}
                        </h6>
                    </div>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-success" onclick="exportExcel()">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="exportPdf()">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Nama Karyawan</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center">Terlambat</th>
                                    <th class="text-center">Izin</th>
                                    <th class="text-center">Sakit</th>
                                    <th class="text-center pe-4">Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyAttendanceData as $key => $data)
                                    <tr class="hover-shadow">
                                        <td class="ps-4">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <span class="fw-medium">{{ $data['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill px-3">{{ $data['total_absen'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success rounded-pill px-3">{{ $data['hadir'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning rounded-pill px-3">{{ $data['terlambat'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info rounded-pill px-3">{{ $data['izin'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary rounded-pill px-3">{{ $data['sakit'] }}</span>
                                        </td>
                                        <td class="pe-4">
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: {{ $data['percentage'] }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted ms-2 fw-semibold">{{ $data['percentage'] }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-3 text-muted opacity-25"></i>
                                            <p class="mb-0">Tidak ada data absensi untuk bulan ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <!-- Daily Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-table text-primary me-2 fs-5"></i>
                        <h6 class="mb-0 fw-semibold">
                            Rekap Harian {{ \Carbon\Carbon::today()->format('d F Y') }}
                        </h6>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="exportDaily()">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Nama Karyawan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Waktu</th>
                                    <th class="text-center pe-4">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensiData as $key => $absensi)
                                    <tr class="hover-shadow">
                                        <td class="ps-4">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <span class="fw-medium">{{ $absensi->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($absensi->status == 'hadir')
                                                <span class="badge bg-success rounded-pill px-3">
                                                    <i class="fas fa-check me-1"></i>Hadir
                                                </span>
                                            @elseif($absensi->status == 'terlambat')
                                                <span class="badge bg-warning rounded-pill px-3">
                                                    <i class="fas fa-clock me-1"></i>Terlambat
                                                </span>
                                            @elseif($absensi->status == 'izin')
                                                <span class="badge bg-info rounded-pill px-3">
                                                    <i class="fas fa-calendar-times me-1"></i>Izin
                                                </span>
                                            @elseif($absensi->status == 'sakit')
                                                <span class="badge bg-secondary rounded-pill px-3">
                                                    <i class="fas fa-user-injured me-1"></i>Sakit
                                                </span>
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Belum Absen
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($absensi->created_at)
                                                <span class="text-muted">{{ $absensi->created_at->format('H:i') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            @if ($absensi->status == 'belum_absen')
                                                <span class="text-danger small">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Perlu Tindak Lanjut
                                                </span>
                                            @else
                                                <span class="text-success small">
                                                    <i class="fas fa-check-circle me-1"></i>Tercatat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-3 text-muted opacity-25"></i>
                                            <p class="mb-0">Tidak ada data absensi untuk hari ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Bottom Row -->
        <div class="row g-4 mb-4">
            <!-- To-Do List -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tasks text-primary me-2 fs-5"></i>
                            <h6 class="mb-0 fw-semibold">To-Do List</h6>
                        </div>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#todoModal">
                            <i class="fas fa-plus me-1"></i> Task Baru
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Task</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="todoTableBody">
                                    <!-- Data akan diisi via JavaScript -->
                                    <tr id="noTasks" style="display: none;">
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-tasks fa-2x mb-3 text-muted opacity-25"></i>
                                            <p class="mb-0">Belum ada task yang dibuat</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-history text-primary me-2 fs-5"></i>
                            <h6 class="mb-0 fw-semibold">Aktivitas Terbaru</h6>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshActivities()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline">
                            @forelse($recentActivities as $activity)
                                <div class="timeline-item mb-3">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content bg-light rounded p-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <h6 class="timeline-title mb-0 fw-semibold">{{ $activity->user->name }}</h6>
                                            <small class="text-muted">{{ $activity->created_at->format('H:i') }}</small>
                                        </div>
                                        <p class="timeline-text mb-0 small">{{ $activity->description }}</p>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-clock fa-2x mb-3 text-muted opacity-25"></i>
                                    <p class="mb-0">Belum ada aktivitas terbaru</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- To-Do Modal -->
        <div class="modal fade" id="todoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Task Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="todoForm">
                        <div class="modal-body pt-0">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Judul Task</label>
                                <input type="text" name="task_title" class="form-control shadow-none"
                                       placeholder="Apa yang perlu dilakukan?" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <textarea name="task_description" class="form-control shadow-none" rows="3"
                                          placeholder="Detail task..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom Styles */
        :root {
            --primary-hover: #0b5ed7;
            --card-shadow: 0 0.125rem 0.375rem rgba(0, 0, 0, 0.05);
            --card-hover-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 0.5rem;
            border: none;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .hover-scale:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-hover-shadow);
        }

        .hover-shadow:hover {
            box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .table-light th {
            background-color: #f8f9fa;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
        }

        .progress {
            border-radius: 1rem;
        }

        .progress-bar {
            border-radius: 1rem;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: rgba(0, 0, 0, 0.05);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1rem;
        }

        .timeline-marker {
            position: absolute;
            left: 0;
            top: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .timeline-content {
            padding-left: 1rem;
        }

        .timeline-title {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .timeline-text {
            font-size: 0.8125rem;
            color: #6c757d;
        }

        /* Avatar Styles */
        .avatar {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        /* Form Control Styles */
        .form-control, .form-select {
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        /* Button Styles */
        .btn {
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
        }

        /* Task Completed Style */
        .task-completed {
            opacity: 0.7;
        }

        .task-completed td {
            text-decoration: line-through;
            color: #6c757d !important;
        }

        /* Print Styles */
        @media print {
            .btn, .card-header .btn-group, #todoModal {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Line Chart
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            const lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Total Absensi',
                        data: @json($chartData['data']),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0d6efd',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
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
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#6c757d',
                            borderColor: '#dee2e6',
                            borderWidth: 1,
                            padding: 12,
                            boxShadow: '0 0.125rem 0.5rem rgba(0, 0, 0, 0.1)',
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.parsed.y + ' absensi';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Pie Chart
            const ctxPie = document.getElementById('statusChart').getContext('2d');
            const pieChart = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: @json($pieChartData['labels']),
                    datasets: [{
                        data: @json($pieChartData['data']),
                        backgroundColor: @json($pieChartData['colors']),
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#6c757d',
                            borderColor: '#dee2e6',
                            borderWidth: 1,
                            padding: 12,
                            boxShadow: '0 0.125rem 0.5rem rgba(0, 0, 0, 0.1)',
                            usePointStyle: true
                        }
                    }
                }
            });

            // Initialize To-Do List
            renderTodos();
        });

        // To-Do List Functionality
        let todos = JSON.parse(localStorage.getItem('todos')) || [];

        function renderTodos() {
            const tbody = document.getElementById('todoTableBody');
            const noTasksRow = document.getElementById('noTasks');

            // Clear existing rows except the "no tasks" row
            while (tbody.firstChild && tbody.firstChild !== noTasksRow) {
                tbody.removeChild(tbody.firstChild);
            }

            if (todos.length === 0) {
                noTasksRow.style.display = '';
                return;
            }

            noTasksRow.style.display = 'none';

            todos.forEach((todo, index) => {
                const tr = document.createElement('tr');
                if (todo.completed) {
                    tr.classList.add('task-completed');
                }
                tr.innerHTML = `
                    <td class="ps-4">${index + 1}</td>
                    <td>${todo.title}</td>
                    <td>${todo.description || '-'}</td>
                    <td class="text-center">
                        <span class="badge ${todo.completed ? 'bg-success' : 'bg-warning'} rounded-pill px-3">
                            ${todo.completed ? 'Selesai' : 'Pending'}
                        </span>
                    </td>
                    <td class="text-center pe-4">
                        <button class="btn btn-sm ${todo.completed ? 'btn-outline-secondary' : 'btn-outline-success'} me-1"
                            onclick="toggleTodo(${index})" title="${todo.completed ? 'Reset' : 'Selesaikan'}">
                            <i class="fas ${todo.completed ? 'fa-undo' : 'fa-check'}"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteTodo(${index})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.insertBefore(tr, noTasksRow);
            });
        }

        function addTodo(title, description) {
            todos.push({
                title,
                description,
                completed: false,
                createdAt: new Date().toISOString()
            });
            saveTodos();

            // Show success notification
            showToast('Task berhasil ditambahkan', 'success');
        }

        function toggleTodo(index) {
            todos[index].completed = !todos[index].completed;
            saveTodos();

            // Show notification
            const status = todos[index].completed ? 'diselesaikan' : 'direset';
            showToast(`Task "${todos[index].title}" ${status}`, 'info');
        }

        function deleteTodo(index) {
            if (confirm(`Apakah Anda yakin ingin menghapus task "${todos[index].title}"?`)) {
                const deletedTitle = todos[index].title;
                todos.splice(index, 1);
                saveTodos();

                // Show notification
                showToast(`Task "${deletedTitle}" dihapus`, 'warning');
            }
        }

        function saveTodos() {
            localStorage.setItem('todos', JSON.stringify(todos));
            renderTodos();
        }

        // Form submission
        document.getElementById('todoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const title = this.task_title.value.trim();
            const description = this.task_description.value.trim();

            if (title) {
                addTodo(title, description);

                // Reset form and close modal
                this.reset();
                bootstrap.Modal.getInstance(document.getElementById('todoModal')).hide();
            }
        });

        // Toast Notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.style.zIndex = '1090';

            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-circle' : 'fa-info-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

            document.body.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            // Remove toast after hiding
            toast.addEventListener('hidden.bs.toast', function() {
                toast.remove();
            });
        }

        // Export Functions
        function exportExcel() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'excel');
            window.location.href = '{{ route('pimpinan.dashboard') }}?' + params.toString();
        }

        function exportPdf() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'pdf');
            window.location.href = '{{ route('pimpinan.dashboard') }}?' + params.toString();
        }

        function exportDaily() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'daily');
            window.location.href = '{{ route('pimpinan.dashboard') }}?' + params.toString();
        }

        function refreshChart() {
            location.reload();
        }

        function refreshNotifications() {
            // Implement AJAX refresh here if needed
            location.reload();
        }

        function refreshActivities() {
            // Implement AJAX refresh here if needed
            location.reload();
        }
    </script>
@endpush
