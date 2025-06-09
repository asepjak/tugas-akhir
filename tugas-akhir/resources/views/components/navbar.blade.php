<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            @php
                $user = Auth::user();
                // Gunakan path foto yang sudah lengkap (misal: "foto/namafile.jpg")
                // Jika $user->foto sudah menyimpan 'foto/namafile.jpg', cukup:
                $fotoPath = $user->foto ? 'storage/' . $user->foto : 'default-profile.png';
            @endphp
            <img src="{{ asset($fotoPath) }}"
                 onerror="this.onerror=null; this.src='{{ asset('default-profile.png') }}';"
                 alt="Foto Profil {{ $user->nama ?? $user->name }}"
                 class="rounded-circle me-2"
                 width="40"
                 height="40"
                 style="object-fit: cover;">
            <span class="fs-5">Hai, {{ $user->nama ?? $user->name }}</span>
        </div>

        <ul class="nav">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('karyawan.absensi.index') }}">Absensi</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('karyawan.permission.index') }}">Perizinan</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSettings" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Pengaturan
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownSettings">
                    <li><a class="dropdown-item" href="{{ route('profile') }}">Profil</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
