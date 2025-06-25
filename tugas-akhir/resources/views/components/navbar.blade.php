<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-3 px-lg-4">
    <div class="container-fluid">
        <!-- Brand/Profile Section -->
        <div class="d-flex align-items-center">
            @php
                $user = Auth::user();
                $fotoPath = $user->foto ? 'storage/' . $user->foto : 'default-profile.png';
            @endphp
            <img src="{{ asset($fotoPath) }}"
                 onerror="this.onerror=null; this.src='{{ asset('default-profile.png') }}';"
                 alt="Foto Profil {{ $user->nama ?? $user->name }}"
                 class="rounded-circle me-2"
                 width="35"
                 height="35"
                 style="object-fit: cover;">
            <span class="fs-6 fs-lg-5 d-none d-sm-inline">Hai, {{ $user->nama ?? $user->name }}</span>
            <span class="fs-6 d-inline d-sm-none">{{ Str::limit($user->nama ?? $user->name, 10) }}</span>
        </div>
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Collapsible Navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-2 px-lg-3 py-2" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-1 d-lg-none"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 px-lg-3 py-2" href="{{ route('karyawan.absensi.index') }}">
                        <i class="fas fa-clock me-1 d-lg-none"></i>Presensi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 px-lg-3 py-2" href="{{ route('karyawan.permissions.index') }}">
                        <i class="fas fa-file-alt me-1 d-lg-none"></i>Ajuan Karyawan
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-2 px-lg-3 py-2" href="#" id="navbarDropdownSettings" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-1 d-lg-none"></i>Pengaturan
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownSettings">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('karyawan.profile.edit') }}">
                                <i class="fas fa-user me-2"></i>Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button class="dropdown-item py-2 text-danger" type="submit">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
