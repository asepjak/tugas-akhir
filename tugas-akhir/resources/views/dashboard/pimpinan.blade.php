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
                            <option value="monthly" {{ $viewType == 'monthly' ? 'selected' : '' }}>Total per Bulan</option>
                            <option value="daily" {{ $viewType == 'daily' ? 'selected' : '' }}>Hari Ini</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small text-muted">Cari Nama</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="nama" class="form-control" placeholder="Nama karyawan"
                                value="{{ $nama }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="hadir" {{ $status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="terlambat" {{ $status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="izin" {{ $status == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ $status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            @if ($viewType == 'daily')
                                <option value="belum_absen" {{ $status == 'belum_absen' ? 'selected' : '' }}>Belum Absen
                                </option>
                            @endif
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

            @if ($viewType == 'monthly')
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
                                    <small class="text-muted">{{ $statistik['unique_users'] }} karyawan</small>
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
                                    <small class="text-muted">{{ $statistik['attendance_rate'] }}% tingkat
                                        kehadiran</small>
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
                                    <small class="text-muted">{{ $statistik['avg_per_day'] }} avg/hari</small>
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
                                    <small class="text-muted">{{ $statistik['attendance_rate'] }}% dari total</small>
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
                                    <small class="text-muted">Perlu perhatian</small>
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
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-heart text-info fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Izin/Sakit Hari Ini</h6>
                                    <h3 class="mb-0 fw-bold text-info">{{ $statistik['izin'] + $statistik['sakit'] }}</h3>
                                    <small class="text-muted">{{ $statistik['izin'] }} izin, {{ $statistik['sakit'] }}
                                        sakit</small>
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
                                        <i class="fas fa-user-clock text-danger fa-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 text-muted">Belum Absen</h6>
                                    <h3 class="mb-0 fw-bold text-danger">{{ $statistik['belum_absen'] }}</h3>
                                    <small class="text-muted">Perlu tindak lanjut</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Line Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-chart-line me-2"></i>
                                Grafik Absensi
                                @if ($viewType == 'monthly')
                                    {{ $bulanList[$bulan] }} {{ $tahun }}
                                @else
                                    Hari Ini
                                @endif
                            </h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshChart()">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-chart-pie me-2"></i>Distribusi Status
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables -->
        @if ($viewType == 'monthly')
            <!-- Monthly Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-table me-2"></i>Data Absensi Bulanan {{ $bulanList[$bulan] }}
                            {{ $tahun }}
                        </h6>
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-success btn-sm" onclick="exportExcel()">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="exportPdf()">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th class="text-center">Total Absen</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center">Terlambat</th>
                                    <th class="text-center">Izin</th>
                                    <th class="text-center">Sakit</th>
                                    <th class="text-center">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyAttendanceData as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <span class="fw-medium">{{ $data['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $data['total_absen'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $data['hadir'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $data['terlambat'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $data['izin'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $data['sakit'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $data['percentage'] }}%">
                                                    {{ $data['percentage'] }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
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
            <!-- Daily Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-table me-2"></i>Data Absensi Hari Ini
                            ({{ \Carbon\Carbon::today()->format('d F Y') }})
                        </h6>
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-success btn-sm" onclick="exportDaily()">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Waktu</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensiData as $key => $absensi)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
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
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Hadir
                                                </span>
                                            @elseif($absensi->status == 'terlambat')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Terlambat
                                                </span>
                                            @elseif($absensi->status == 'izin')
                                                <span class="badge bg-info">
                                                    <i class="fas fa-calendar-times me-1"></i>Izin
                                                </span>
                                            @elseif($absensi->status == 'sakit')
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-user-injured me-1"></i>Sakit
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Belum Absen
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($absensi->created_at)
                                                <span class="text-muted">{{ $absensi->created_at->format('H:i:s') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($absensi->status == 'belum_absen')
                                                <span class="text-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Perlu Tindak Lanjut
                                                </span>
                                            @else
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Tercatat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Tidak ada data absensi untuk hari ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- To-Do List Section -->
        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-tasks me-2"></i>To-Do List
                            </h6>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#todoModal">
                                <i class="fas fa-plus me-1"></i> Tambah Task
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Task</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="todoTableBody">
                                    <!-- Data akan diisi via JavaScript -->
                                    <tr id="noTasks" style="display: none;">
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-tasks fa-2x mb-2 d-block"></i>
                                            Belum ada task
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
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @forelse($recentActivities as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">{{ $activity->user->name }}</h6>
                                        <p class="timeline-text">{{ $activity->description }}</p>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-clock fa-2x mb-2 d-block"></i>
                                    Belum ada aktivitas terbaru
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- To-Do Modal -->
        <div class="modal fade" id="todoModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Task Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="todoForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Judul Task</label>
                                <input type="text" name="task_title" class="form-control"
                                    placeholder="Apa yang perlu dilakukan?" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="task_description" class="form-control" rows="3" placeholder="Detail task..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Task
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
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -22px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-content {
            padding-left: 20px;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .timeline-text {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .progress {
            background-color: #f8f9fa;
        }

        .table th {
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge {
            font-size: 11px;
            font-weight: 500;
            padding: 6px 10px;
        }

        .task-completed {
            text-decoration: line-through;
            opacity: 0.7;
        }

        @media print {

            .btn,
            .card-header .btn-group {
                display: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Line Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Total Absensi',
                    data: @json($chartData['data']),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
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
        const ctxPie = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: @json($pieChartData['labels']),
                datasets: [{
                    data: @json($pieChartData['data']),
                    backgroundColor: @json($pieChartData['colors']),
                    borderWidth: 0
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
                    <td>${index + 1}</td>
                    <td>${todo.title}</td>
                    <td>${todo.description || '-'}</td>
                    <td class="text-center">
                        <span class="badge ${todo.completed ? 'bg-success' : 'bg-warning'}">
                            ${todo.completed ? 'Selesai' : 'Pending'}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm ${todo.completed ? 'btn-outline-secondary' : 'btn-outline-success'}"
                            onclick="toggleTodo(${index})">
                            <i class="fas ${todo.completed ? 'fa-undo' : 'fa-check'}"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteTodo(${index})">
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
        }

        function toggleTodo(index) {
            todos[index].completed = !todos[index].completed;
            saveTodos();
        }

        function deleteTodo(index) {
            if (confirm('Apakah Anda yakin ingin menghapus task ini?')) {
                todos.splice(index, 1);
                saveTodos();
            }
        }

        function saveTodos() {
            localStorage.setItem('todos', JSON.stringify(todos));
            renderTodos();
        }

        // Form submission
        document.getElementById('todoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const title = this.task_title.value;
            const description = this.task_description.value;

            addTodo(title, description);

            // Reset form and close modal
            this.reset();
            bootstrap.Modal.getInstance(document.getElementById('todoModal')).hide();
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', renderTodos);

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
    </script>
@endpush
