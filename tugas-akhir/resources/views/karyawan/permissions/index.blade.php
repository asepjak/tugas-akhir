@extends('layouts.app')

@section('title', 'Ajuan Karyawan')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold text-uppercase text-center mb-4">Ajuan Karyawan</h4>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Ajuan Karyawan</h5>
            </div>
            <div class="card-body">
                <!-- Template Download -->
                <div class="mb-3 text-end">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="templateDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download"></i> Unduh Template Surat
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="templateDropdown">
                            <li><a class="dropdown-item" href="{{ asset('assets/templates/template-surat-sakit.pdf') }}"
                                    target="_blank">
                                    <i class="bi bi-file-earmark-medical"></i> Template Surat Sakit
                                </a></li>
                            <li><a class="dropdown-item" href="{{ asset('assets/templates/template-surat-izin.pdf') }}"
                                    target="_blank">
                                    <i class="bi bi-file-earmark-text"></i> Template Surat Izin
                                </a></li>
                            <li><a class="dropdown-item" href="{{ asset('assets/templates/template-surat-CUTI.pdf') }}"
                                    target="_blank">
                                    <i class="bi bi-calendar-x"></i> Template Surat Cuti
                                </a></li>
                            <li><a class="dropdown-item"
                                    href="{{ asset('assets/templates/template-surat-PERJALANAN.pdf') }}" target="_blank">
                                    <i class="bi bi-geo-alt"></i> Template Perjalanan Keluar Kota
                                </a></li>
                        </ul>
                    </div>
                </div>
                <!-- Main Form -->
                <form action="{{ route('karyawan.permission.store') }}" method="POST" enctype="multipart/form-data"
                    id="permissionForm">
                    @csrf
                    <!-- Baris pertama -->
                    <div class="row g-3">
                        <!-- Keterangan -->
                        <div class="col-md-6 col-lg-2">
                            <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <select name="keterangan" id="keterangan"
                                class="form-select @error('keterangan') is-invalid @enderror" required>
                                <option value="">-- Pilih Keterangan --</option>
                                <option value="Sakit" {{ old('keterangan') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="Izin" {{ old('keterangan') == 'Izin' ? 'selected' : '' }}>Izin</option>
                                <option value="Cuti" {{ old('keterangan') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                <option value="Perjalanan Keluar Kota"
                                    {{ old('keterangan') == 'Perjalanan Keluar Kota' ? 'selected' : '' }}>Perjalanan Keluar
                                    Kota</option>
                            </select>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Alasan -->
                        <div class="col-md-6 col-lg-2">
                            <label for="alasan" class="form-label">Alasan</label>
                            <input type="text" name="alasan" id="alasan"
                                class="form-control @error('alasan') is-invalid @enderror" placeholder="Alasan (opsional)"
                                value="{{ old('alasan') }}" maxlength="255">
                            @error('alasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor Unit -->
                        <div class="col-md-6 col-lg-2">
                            <label for="nomor_unit" class="form-label">Nomor Unit</label>
                            <input type="text" name="nomor_unit" id="nomor_unit"
                                class="form-control @error('nomor_unit') is-invalid @enderror"
                                placeholder="Nomor Unit (opsional)" value="{{ old('nomor_unit') }}">
                            @error('nomor_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="col-md-6 col-lg-2">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}">
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="col-md-6 col-lg-2">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai') }}" min="{{ date('Y-m-d') }}">
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Baris kedua -->
                    <div class="row g-3 mt-1">
                        <!-- Muatan -->
                        <div class="col-md-6 col-lg-2">
                            <label for="muatan" class="form-label">Muatan</label>
                            <input type="text" name="muatan" id="muatan"
                                class="form-control @error('muatan') is-invalid @enderror" placeholder="Muatan (opsional)"
                                value="{{ old('muatan') }}">
                            @error('muatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Merek Muatan -->
                        <div class="col-md-6 col-lg-2">
                            <label for="merek_muatan" class="form-label">Merek Muatan</label>
                            <input type="text" name="merek_muatan" id="merek_muatan"
                                class="form-control @error('merek_muatan') is-invalid @enderror"
                                placeholder="Merek Muatan (opsional)" value="{{ old('merek_muatan') }}">
                            @error('merek_muatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tujuan -->
                        <div class="col-md-6 col-lg-2">
                            <label for="perjalanan_keluar_kota" class="form-label">Tujuan</label>
                            <input type="text" name="perjalanan_keluar_kota" id="perjalanan_keluar_kota"
                                class="form-control @error('perjalanan_keluar_kota') is-invalid @enderror"
                                placeholder="Tujuan (opsional)" value="{{ old('perjalanan_keluar_kota') }}">
                            @error('perjalanan_keluar_kota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="col-md-6 col-lg-3">
                            <label for="file_surat" class="form-label">Lampiran Surat</label>
                            <input type="file" name="file_surat" id="file_surat"
                                class="form-control @error('file_surat') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png" onchange="validateFileSize(this)">
                            <div class="form-text">Format: PDF, JPG, PNG (Max: 2MB)</div>
                            @error('file_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-md-6 col-lg-3 d-flex align-items-end">
                            <div class="w-100">
                                <button type="reset" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-success" id="submitBtn">
                                    <i class="bi bi-send"></i> Kirim Ajuan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- History Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Riwayat Ajuan Karyawan</h5>
            </div>
            <div class="card-body">
                @if ($permissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Keterangan</th>
                                    <th>Detail</th>
                                    <th>Periode</th>
                                    <th>Tujuan</th>
                                    <th>Status</th>
                                    <th>Lampiran</th>
                                    <th>Tanggal Ajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $index => $izin)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $izin->keterangan }}</span>
                                        </td>
                                        <td>
                                            @if ($izin->keterangan == 'Perjalanan Keluar Kota')
                                                <small>
                                                    <strong>Unit:</strong> {{ $izin->nomor_unit ?? '-' }}<br>
                                                    <strong>Muatan:</strong> {{ $izin->muatan ?? '-' }}<br>
                                                    <strong>Merek:</strong> {{ $izin->merek_muatan ?? '-' }}
                                                </small>
                                            @else
                                                {{ Str::limit($izin->alasan ?? '-', 30) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($izin->tanggal_mulai && $izin->tanggal_selesai)
                                                <small>
                                                    <strong>Mulai:</strong>
                                                    {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }}<br>
                                                    <strong>Selesai:</strong>
                                                    {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}
                                                    <br><span
                                                        class="text-muted">({{ \Carbon\Carbon::parse($izin->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($izin->tanggal_selesai)) + 1 }}
                                                        hari)</span>
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $izin->perjalanan_keluar_kota ?? '-' }}</td>
                                        <td>
                                            @if ($izin->status == 'Menunggu')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock"></i> Menunggu
                                                </span>
                                            @elseif ($izin->status == 'Disetujui')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Diterima
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($izin->file_surat)
                                                <a href="{{ asset('storage/' . $izin->file_surat) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary" title="Lihat Lampiran">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($izin->created_at)->format('d M Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if ($izin->status == 'Disetujui')
                                                    <a href="{{ route('karyawan.permissions.print', $izin->id) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-secondary"
                                                        title="Cetak Surat">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                @endif
                                                @if ($izin->status == 'Menunggu')
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
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
                        <div class="d-flex justify-content-center mt-3">
                            {{ $permissions->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Belum ada data ajuan izin</h5>
                        <p class="text-muted">Ajuan izin yang Anda buat akan muncul di sini</p>
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
                        alert('Harap pilih keterangan');
                        document.getElementById('keterangan').focus();
                        return false;
                    }

                    // Disable submit button to prevent double submission
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
                });
            }
        });

        // File size validation
        function validateFileSize(input) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (input.files && input.files.length > 0) {
                const fileSize = input.files[0].size;
                if (fileSize > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    input.value = '';
                    return false;
                }
            }
            return true;
        }

        // Reset form function
        function resetForm() {
            const form = document.getElementById('permissionForm');
            if (form) {
                form.reset();

                // Re-enable submit button
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send"></i> Kirim Ajuan';
                }
            }
        }

        // Delete confirmation
        function deletePermission(id) {
            if (confirm('Apakah Anda yakin ingin menghapus ajuan ini?')) {
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
        .table th {
            white-space: nowrap;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .form-label {
            font-weight: 600;
        }

        .badge {
            font-size: 0.75em;
        }

        .btn-group .btn {
            border-radius: 0.25rem;
            margin: 0 2px;
        }

        /* Highlight required fields */
        .form-control:required {
            border-left: 3px solid #007bff;
        }

        .form-select:required {
            border-left: 3px solid #007bff;
        }

        /* Optional fields styling */
        .form-control:not(:required) {
            border-left: 3px solid #6c757d;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }

            .btn-group {
                flex-direction: column;
                gap: 2px;
            }

            .col-lg-2,
            .col-lg-3 {
                margin-bottom: 1rem;
            }
        }

        /* Form layout improvements */
        .row.g-3 .col-md-6,
        .row.g-3 .col-lg-2,
        .row.g-3 .col-lg-3 {
            margin-bottom: 1rem;
        }

        /* Better visual separation between required and optional fields */
        .form-control:required:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-control:not(:required):focus {
            border-color: #6c757d;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
        }
    </style>
@endpush
