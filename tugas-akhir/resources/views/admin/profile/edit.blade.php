@extends('layouts.admin-app')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Profil Saya</h3>
                    <p class="text-muted mb-0">Kelola informasi profil dan akun Anda</p>
                </div>
                <div class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ Auth::user()->role ?? 'Karyawan' }}
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Photo Section -->
        <div class="col-lg-4 col-md-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-camera-fill me-2"></i>
                        Foto Profil
                    </h6>
                </div>
                <div class="card-body text-center p-4">
                    <div class="profile-photo-container mb-4 position-relative">
                        @if ($user->foto)
                            <img id="fotoPreview" src="{{ asset('storage/' . $user->foto) }}"
                                 class="img-fluid rounded-circle border border-3 border-white shadow"
                                 style="width: 200px; height: 200px; object-fit: cover;"
                                 alt="Foto Profil">
                        @else
                            <img id="fotoPreview" src="{{ asset('default-profile.png') }}"
                                 class="img-fluid rounded-circle border border-3 border-white shadow"
                                 style="width: 200px; height: 200px; object-fit: cover;"
                                 alt="Foto Profil">
                        @endif

                        <!-- Camera Icon Overlay -->
                        <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle d-flex align-items-center justify-content-center shadow"
                             style="width: 40px; height: 40px; transform: translate(-10px, -10px);">
                            <i class="bi bi-camera-fill text-white"></i>
                        </div>
                    </div>

                    <!-- Hidden file input -->
                    <input type="file" name="foto" id="fotoInput" class="form-control"
                           accept="image/*" style="display:none;" form="profileForm">

                    <button type="button" class="btn btn-outline-primary btn-lg w-100"
                            onclick="document.getElementById('fotoInput').click()">
                        <i class="bi bi-camera me-2"></i>
                        Ubah Foto
                    </button>

                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Format: JPG, PNG. Maksimal 2MB
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="col-lg-8 col-md-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-success text-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-person-lines-fill me-2"></i>
                        Informasi Profil
                    </h6>
                </div>
                <div class="card-body p-4">
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Profile Form -->
                   <form id="profileForm" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingNama" name="nama"
                                           value="{{ old('nama', $user->nama) }}" placeholder="Nama Lengkap">
                                    <label for="floatingNama">
                                        <i class="bi bi-person me-1"></i>Nama Lengkap
                                    </label>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="floatingAlamat" name="alamat"
                                              placeholder="Alamat" style="height: 100px;">{{ old('alamat', $user->alamat) }}</textarea>
                                    <label for="floatingAlamat">
                                        <i class="bi bi-geo-alt me-1"></i>Alamat
                                    </label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="floatingEmail" name="email"
                                           value="{{ old('email', $user->email) }}" placeholder="Email">
                                    <label for="floatingEmail">
                                        <i class="bi bi-envelope me-1"></i>Email
                                    </label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingHP" name="no_hp"
                                           value="{{ old('no_hp', $user->no_hp) }}" placeholder="No. HP">
                                    <label for="floatingHP">
                                        <i class="bi bi-phone me-1"></i>No. HP
                                    </label>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingJabatan" name="jabatan"
                                           value="{{ old('jabatan', $user->jabatan) }}" placeholder="Jabatan">
                                    <label for="floatingJabatan">
                                        <i class="bi bi-briefcase me-1"></i>Jabatan
                                    </label>
                                </div>

                                <div class="form-floating mb-3">
                                    <select class="form-select" id="floatingStatus" name="status">
                                        <option value="Aktif" {{ old('status', $user->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Tidak Aktif" {{ old('status', $user->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="Cuti" {{ old('status', $user->status) == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                    </select>
                                    <label for="floatingStatus">
                                        <i class="bi bi-flag me-1"></i>Status
                                    </label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingUsername" name="username"
                                           value="{{ old('username', $user->username) }}" placeholder="Username">
                                    <label for="floatingUsername">
                                        <i class="bi bi-at me-1"></i>Username
                                    </label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingPassword" name="password"
                                           placeholder="Password Baru">
                                    <label for="floatingPassword">
                                        <i class="bi bi-lock me-1"></i>Password Baru
                                    </label>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Kosongkan jika tidak ingin mengubah password
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4"
                                            onclick="window.history.back()">
                                        <i class="bi bi-arrow-left me-2"></i>
                                        Kembali
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.profile-photo-container {
    position: relative;
    display: inline-block;
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

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border-color: #0d6efd;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

#fotoPreview {
    transition: all 0.3s ease;
    cursor: pointer;
}

#fotoPreview:hover {
    transform: scale(1.05);
    filter: brightness(1.1);
}
</style>

<script>
    // Preview foto yang dipilih
    document.getElementById('fotoInput').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            // Validasi ukuran file (2MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                this.value = '';
                return;
            }

            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.');
                this.value = '';
                return;
            }

            // Preview foto
            const preview = document.getElementById('fotoPreview');
            preview.src = URL.createObjectURL(file);

            // Tambahkan efek loading
            preview.style.opacity = '0.5';
            setTimeout(() => {
                preview.style.opacity = '1';
            }, 200);
        }
    });

    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('alert-success')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);

    // Form validation
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const requiredFields = ['nama', 'email'];
        let isValid = true;

        requiredFields.forEach(function(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi field yang wajib diisi!');
        }
    });
</script>
@endsection
