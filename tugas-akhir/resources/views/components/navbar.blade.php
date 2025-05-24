<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <span class="fs-4 fw-bold me-3">👥</span>
            <span class="fs-5">Hai, {{ Auth::user()->name }}</span>
        </div>
        <ul class="nav">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Home</a></li>
            {{-- <li class="nav-item"><a class="nav-link" href="{{ route('absensi.index') }}">Absensi</a></li> --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('karyawan.permission.index') }}">Perizinan</a></li>
            {{-- <li class="nav-item"><a class="nav-link" href="{{ route('riwayat.absen') }}">Riwayat Absensi</a></li> --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Pengaturan</a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile') }}">Profil</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
