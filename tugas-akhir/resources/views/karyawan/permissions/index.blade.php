@extends('layouts.app')

@section('title', 'Ajuan Karyawan')

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="text-center mb-5">
            <div class="header-icon mb-3">
                <i class="bi bi-file-earmark-person-fill text-primary display-4"></i>
            </div>
            <h2 class="fw-bold text-dark mb-2">Pengajuan Karyawan</h2>
            <p class="text-muted">Kelola pengajuan izin, cuti, dan perjalanan dinas dengan mudah</p>
        </div>

        <!-- Alert Messages with Animation -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                <div class="d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5 mt-1"></i>
                    <div>
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Card with Enhanced Design -->
        <div class="card custom-card mb-5">
            <div class="card-header custom-card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-pencil-square me-2 fs-5"></i>
                        <h5 class="mb-0 fw-bold">Form Pengajuan Karyawan</h5>
                    </div>
                    <!-- Template Download with Enhanced Design -->
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle custom-btn-template" type="button" id="templateDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download me-1"></i> Template Surat
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="templateDropdown">
                            <li>
                                <a class="dropdown-item custom-dropdown-item" href="{{ asset('assets/templates/template-surat-sakit.pdf') }}" target="_blank">
                                    <div class="template-item">
                                        <i class="bi bi-file-earmark-medical text-danger"></i>
                                        <span>Template Surat Sakit</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item custom-dropdown-item" href="{{ asset('assets/templates/template-surat-izin.pdf') }}" target="_blank">
                                    <div class="template-item">
                                        <i class="bi bi-file-earmark-text text-primary"></i>
                                        <span>Template Surat Izin</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item custom-dropdown-item" href="{{ asset('assets/templates/template-surat-CUTI.pdf') }}" target="_blank">
                                    <div class="template-item">
                                        <i class="bi bi-calendar-x text-warning"></i>
                                        <span>Template Surat Cuti</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item custom-dropdown-item" href="{{ asset('assets/templates/template-surat-PERJALANAN.pdf') }}" target="_blank">
                                    <div class="template-item">
                                        <i class="bi bi-geo-alt text-success"></i>
                                        <span>Template Perjalanan</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Main Form with Enhanced Layout -->
                <form action="{{ route('karyawan.permission.store') }}" method="POST" enctype="multipart/form-data" id="permissionForm">
                    @csrf

                    <!-- Form Section 1: Basic Information -->
                    <div class="form-section mb-4">
                        <h6 class="section-title mb-3">
                            <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                        </h6>
                        <div class="row g-3">
                            <!-- Keterangan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keterangan" class="form-label">
                                        <i class="bi bi-tag me-1"></i>Jenis Pengajuan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="keterangan" id="keterangan" class="form-select custom-select @error('keterangan') is-invalid @enderror" required>
                                        <option value="">-- Pilih Jenis Pengajuan --</option>
                                        <option value="Sakit" {{ old('keterangan') == 'Sakit' ? 'selected' : '' }}>
                                            <i class="bi bi-heart-pulse"></i> Sakit
                                        </option>
                                        <option value="Izin" {{ old('keterangan') == 'Izin' ? 'selected' : '' }}>
                                            <i class="bi bi-person-check"></i> Izin
                                        </option>
                                        <option value="Cuti" {{ old('keterangan') == 'Cuti' ? 'selected' : '' }}>
                                            <i class="bi bi-calendar-event"></i> Cuti
                                        </option>
                                        <option value="Perjalanan Keluar Kota" {{ old('keterangan') == 'Perjalanan Keluar Kota' ? 'selected' : '' }}>
                                            <i class="bi bi-geo-alt"></i> Perjalanan Keluar Kota
                                        </option>
                                    </select>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alasan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="alasan" class="form-label">
                                        <i class="bi bi-chat-left-text me-1"></i>Alasan
                                    </label>
                                    <input type="text" name="alasan" id="alasan"
                                           class="form-control custom-input @error('alasan') is-invalid @enderror"
                                           placeholder="Masukkan alasan (opsional)"
                                           value="{{ old('alasan') }}" maxlength="255">
                                    @error('alasan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Section 2: Details -->
                    <div class="form-section mb-4">
                        <h6 class="section-title mb-3">
                            <i class="bi bi-calendar-check me-2"></i>Detail Pengajuan
                        </h6>
                        <div class="row g-3">
                            <!-- Nomor Surat -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_surat" class="form-label">
                                        <i class="bi bi-file-earmark-text me-1"></i>Nomor Surat
                                    </label>
                                    <input type="text" name="no_surat" id="no_surat"
                                           class="form-control custom-input @error('no_surat') is-invalid @enderror"
                                           placeholder="Nomor surat (opsional)"
                                           value="{{ old('no_surat') }}">
                                    @error('no_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tanggal Mulai -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal_mulai" class="form-label">
                                        <i class="bi bi-calendar-plus me-1"></i>Tanggal Mulai
                                    </label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                           class="form-control custom-input @error('tanggal_mulai') is-invalid @enderror"
                                           value="{{ old('tanggal_mulai') }}"
                                           min="{{ date('Y-m-d') }}">
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal_selesai" class="form-label">
                                        <i class="bi bi-calendar-x me-1"></i>Tanggal Selesai
                                    </label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                           class="form-control custom-input @error('tanggal_selesai') is-invalid @enderror"
                                           value="{{ old('tanggal_selesai') }}"
                                           min="{{ date('Y-m-d') }}">
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Section 3: Additional Info -->
                    <div class="form-section mb-4">
                        <h6 class="section-title mb-3">
                            <i class="bi bi-paperclip me-2"></i>Informasi Tambahan
                        </h6>
                        <div class="row g-3">
                            <!-- Tujuan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="perjalanan_keluar_kota" class="form-label">
                                        <i class="bi bi-geo-alt me-1"></i>Tujuan
                                    </label>
                                    <input type="text" name="perjalanan_keluar_kota" id="perjalanan_keluar_kota"
                                           class="form-control custom-input @error('perjalanan_keluar_kota') is-invalid @enderror"
                                           placeholder="Tujuan perjalanan (opsional)"
                                           value="{{ old('perjalanan_keluar_kota') }}">
                                    @error('perjalanan_keluar_kota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_surat" class="form-label">
                                        <i class="bi bi-cloud-upload me-1"></i>Lampiran Surat
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="file_surat" id="file_surat"
                                               class="form-control custom-file-input @error('file_surat') is-invalid @enderror"
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               onchange="validateFileSize(this)">
                                        <div class="file-upload-info">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Format: PDF, JPG, PNG (Maksimal: 2MB)
                                            </small>
                                        </div>
                                    </div>
                                    @error('file_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                            <button type="reset" class="btn btn-outline-secondary custom-btn-reset" onclick="resetForm()">
                                <i class="bi bi-arrow-clockwise me-1"></i> Atur Ulang
                            </button>
                            <button type="submit" class="btn btn-primary custom-btn-submit" id="submitBtn">
                                <i class="bi bi-send me-1"></i> Kirim Pengajuan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- History Table -->
        <div class="card custom-card">
            <div class="card-header custom-card-header-light">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history me-2 fs-5"></i>
                    <h5 class="mb-0 fw-bold">Riwayat Pengajuan Karyawan</h5>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($permissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover custom-table mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Keterangan</th>
                                    <th>Detail</th>
                                    <th>Periode</th>
                                    <th>Tujuan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Lampiran</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $index => $izin)
                                    <tr class="table-row-hover">
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge custom-badge-info">
                                                <i class="bi bi-tag me-1"></i>{{ $izin->keterangan }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($izin->keterangan == 'Perjalanan Keluar Kota')
                                                <small class="text-muted">
                                                    <div><strong>Unit:</strong> {{ $izin->nomor_unit ?? '-' }}</div>
                                                    <div><strong>Muatan:</strong> {{ $izin->muatan ?? '-' }}</div>
                                                    <div><strong>Merek:</strong> {{ $izin->merek_muatan ?? '-' }}</div>
                                                </small>
                                            @else
                                                <span class="text-dark">{{ Str::limit($izin->alasan ?? '-', 30) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($izin->tanggal_mulai && $izin->tanggal_selesai)
                                                <div class="date-range">
                                                    <small>
                                                        <div><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }}</div>
                                                        <div><strong>Selesai:</strong> {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}</div>
                                                        <div class="text-muted">
                                                            <i class="bi bi-calendar-date me-1"></i>
                                                            {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($izin->tanggal_selesai)) + 1 }} hari
                                                        </div>
                                                    </small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-dark">{{ $izin->perjalanan_keluar_kota ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($izin->status == 'Menunggu')
                                                <span class="badge custom-badge-warning">
                                                    <i class="bi bi-clock me-1"></i> Menunggu
                                                </span>
                                            @elseif ($izin->status == 'Disetujui')
                                                <span class="badge custom-badge-success">
                                                    <i class="bi bi-check-circle me-1"></i> Diterima
                                                </span>
                                            @else
                                                <span class="badge custom-badge-danger">
                                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($izin->file_surat)
                                                <a href="{{ asset('storage/' . $izin->file_surat) }}" target="_blank"
                                                   class="btn btn-sm btn-outline-primary custom-btn-file" title="Lihat Lampiran">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($izin->created_at)->format('d M Y H:i') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group-vertical btn-group-sm" role="group">
                                                @if ($izin->status == 'Disetujui')
                                                    <a href="{{ route('karyawan.permissions.print', $izin->id) }}"
                                                       target="_blank" class="btn btn-outline-success custom-btn-print"
                                                       title="Cetak Surat">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                @endif
                                                @if ($izin->status == 'Menunggu')
                                                    <button type="button" class="btn btn-outline-danger custom-btn-delete"
                                                            onclick="deletePermission({{ $izin->id }})" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            <!-- Hidden Delete Form -->
                                            <form id="delete-form-{{ $izin->id }}"
                                                  action="{{ route('karyawan.permissions.destroy', $izin->id) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($permissions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center p-3">
                            {{ $permissions->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state text-center py-5">
                        <div class="empty-icon mb-3">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-2">Belum ada data pengajuan</h5>
                        <p class="text-muted">Pengajuan yang Anda buat akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Form script initialized');

            // Get form elements
            const form = document.getElementById('permissionForm');
            const submitBtn = document.getElementById('submitBtn');
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalSelesai = document.getElementById('tanggal_selesai');

            // Date validation
            if (tanggalMulai && tanggalSelesai) {
                tanggalMulai.addEventListener('change', function() {
                    if (this.value) {
                        tanggalSelesai.min = this.value;
                        if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                            tanggalSelesai.value = this.value;
                        }
                    }
                });

                tanggalSelesai.addEventListener('change', function() {
                    if (this.value && tanggalMulai.value && this.value < tanggalMulai.value) {
                        alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
                        this.value = tanggalMulai.value;
                    }
                });
            }

            // Form submission handling
            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    const keterangan = document.getElementById('keterangan').value;

                    // Basic validation - only keterangan is required now
                    if (!keterangan) {
                        e.preventDefault();
                        alert('Harap pilih jenis pengajuan');
                        document.getElementById('keterangan').focus();
                        return false;
                    }

                    // Disable submit button to prevent double submission
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Memproses...';
                });
            }

            // Add input animation
            const inputs = document.querySelectorAll('.custom-input, .custom-select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('input-focused');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('input-focused');
                });
            });
        });

        // File size validation
        function validateFileSize(input) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (input.files && input.files.length > 0) {
                const fileSize = input.files[0].size;
                const fileInfo = input.parentElement.querySelector('.file-upload-info');

                if (fileSize > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    input.value = '';
                    fileInfo.innerHTML = '<small class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>File terlalu besar!</small>';
                    return false;
                } else {
                    const fileName = input.files[0].name;
                    fileInfo.innerHTML = `<small class="text-success"><i class="bi bi-check-circle me-1"></i>File dipilih: ${fileName}</small>`;
                }
            }
            return true;
        }

        // Reset form function
        function resetForm() {
            const form = document.getElementById('permissionForm');
            if (form) {
                form.reset();

                // Reset file info
                const fileInfo = document.querySelector('.file-upload-info');
                if (fileInfo) {
                    fileInfo.innerHTML = '<small class="text-muted"><i class="bi bi-info-circle me-1"></i>Format: PDF, JPG, PNG (Maksimal: 2MB)</small>';
                }

                // Re-enable submit button
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-1"></i> Kirim Pengajuan';
                }
            }
        }

        // Delete confirmation
        function deletePermission(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')) {
                const form = document.getElementById('delete-form-' + id);
                if (form) form.submit();
            }
        }

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    </script>
@endpush

@push('styles')
<style>
    /* Modern Color Palette */
    :root {
        --primary-color: #4f46e5;
        --primary-light: #818cf8;
        --primary-dark: #3730a3;
        --secondary-color: #64748b;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #06b6d4;
        --light-bg: #f8fafc;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --border-radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Global Styles */
    body {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .container {
        max-width: 1200px;
    }

    /* Header Section */
    .header-icon {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    /* Custom Cards */
    .custom-card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        transition: var(--transition);
        overflow: hidden;
    }

    .custom-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-2px);
    }

    .custom-card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        color: white;
        border: none;
        padding: 1.25rem 1.5rem;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .custom-card-header-light {
        background: linear-gradient(135deg, var(--light-bg) 0%, #e2e8f0 100%);
        color: var(--secondary-color);
        border: none;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Custom Alerts */
    .custom-alert {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Form Sections */
    .form-section {
        position: relative;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: rgba(248, 250, 252, 0.5);
        border-radius: var(--border-radius);
        border-left: 4px solid var(--primary-color);
    }

    .section-title {
        color: var(--primary-dark);
        font-weight: 600;
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(79, 70, 229, 0.1);
    }

    /* Form Groups */
    .form-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .form-group.input-focused .form-label {
        color: var(--primary-color);
        transform: scale(1.02);
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        transition: var(--transition);
    }

    .form-label i {
        color: var(--primary-color);
    }

    /* Custom Inputs */
    .custom-input, .custom-select {
        border: 2px solid #e2e8f0;
        border-radius: var(--border-radius);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: var(--transition);
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .custom-input:focus, .custom-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        transform: translateY(-1px);
    }

    .custom-input:hover, .custom-select:hover {
        border-color: var(--primary-light);
    }

    /* File Upload */
    .file-upload-wrapper {
        position: relative;
    }

    .custom-file-input {
        border: 2px dashed #cbd5e1;
        border-radius: var(--border-radius);
        padding: 1rem;
        background: rgba(248, 250, 252, 0.5);
        transition: var(--transition);
    }

    .custom-file-input:hover {
        border-color: var(--primary-color);
        background: rgba(79, 70, 229, 0.05);
    }

    .file-upload-info {
        margin-top: 0.5rem;
        padding: 0.25rem 0;
    }

    /* Template Dropdown */
    .custom-btn-template {
        border-radius: var(--border-radius);
        font-weight: 500;
        transition: var(--transition);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .custom-btn-template:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .custom-dropdown {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .custom-dropdown-item {
        padding: 0.75rem 1rem;
        transition: var(--transition);
        border: none;
    }

    .custom-dropdown-item:hover {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(129, 140, 248, 0.1) 100%);
        transform: translateX(5px);
    }

    .template-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .template-item i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    /* Action Buttons */
    .form-actions {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .custom-btn-reset {
        border: 2px solid var(--secondary-color);
        color: var(--secondary-color);
        border-radius: var(--border-radius);
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: var(--transition);
    }

    .custom-btn-reset:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(100, 116, 139, 0.3);
    }

    .custom-btn-submit {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border: none;
        border-radius: var(--border-radius);
        font-weight: 600;
        padding: 0.75rem 2rem;
        transition: var(--transition);
        box-shadow: 0 4px 8px rgba(79, 70, 229, 0.3);
    }

    .custom-btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(79, 70, 229, 0.4);
    }

    .custom-btn-submit:disabled {
        opacity: 0.7;
        transform: none;
        box-shadow: none;
    }

    /* Custom Table */
    .custom-table {
        font-size: 0.9rem;
    }

    .custom-table thead th {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem 0.75rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table tbody td {
        padding: 1rem 0.75rem;
        border-color: #f1f5f9;
        vertical-align: middle;
    }

    .table-row-hover {
        transition: var(--transition);
    }

    .table-row-hover:hover {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(129, 140, 248, 0.05) 100%);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Custom Badges */
    .custom-badge-info {
        background: linear-gradient(135deg, var(--info-color) 0%, #67e8f9 100%);
        color: white;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
    }

    .custom-badge-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #fbbf24 100%);
        color: white;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
    }

    .custom-badge-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #34d399 100%);
        color: white;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
    }

    .custom-badge-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #f87171 100%);
        color: white;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
    }

    /* Action Buttons in Table */
    .custom-btn-file {
        border-radius: 8px;
        transition: var(--transition);
        font-size: 0.85rem;
        padding: 0.5rem;
    }

    .custom-btn-file:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }

    .custom-btn-print {
        border-radius: 8px;
        transition: var(--transition);
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
        margin-bottom: 0.25rem;
    }

    .custom-btn-print:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .custom-btn-delete {
        border-radius: 8px;
        transition: var(--transition);
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
    }

    .custom-btn-delete:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    /* Date Range Display */
    .date-range div {
        margin-bottom: 0.25rem;
    }

    .date-range div:last-child {
        margin-bottom: 0;
    }

    /* Empty State */
    .empty-state .empty-icon {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0, -30px, 0);
        }
        70% {
            transform: translate3d(0, -15px, 0);
        }
        90% {
            transform: translate3d(0, -4px, 0);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .form-section {
            padding: 1rem;
        }

        .custom-card-header,
        .custom-card-header-light {
            padding: 1rem;
        }

        .section-title {
            font-size: 1rem;
        }

        .custom-table {
            font-size: 0.8rem;
        }

        .custom-table thead th,
        .custom-table tbody td {
            padding: 0.5rem;
        }

        .form-actions .d-flex {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .template-item {
            gap: 0.5rem;
        }

        .btn-group-vertical .btn {
            margin-bottom: 0.25rem;
        }
    }

    @media (max-width: 576px) {
        .header-icon {
            font-size: 2rem;
        }

        .custom-card {
            margin: 0 -15px;
            border-radius: 0;
        }

        .table-responsive {
            border-radius: 0;
        }
    }

    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Focus visible for accessibility */
    .btn:focus-visible,
    .form-control:focus-visible,
    .form-select:focus-visible {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }

    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Custom scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--primary-dark);
    }
</style>
