<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Pimpinan')</title>

    <!-- CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #f8f9fa;
            transition: transform 0.3s ease;
            z-index: 1030;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            transform: translateX(-250px);
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1020;
            display: none;
        }

        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .mobile-toggle {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1040;
            display: none;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }

        .nav-link.active {
            background-color: #e3f2fd;
            color: #1976d2 !important;
            font-weight: bold;
            border-radius: 8px;
        }

        .nav-link:hover {
            background-color: #f1f1f1;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }
        }

        .sidebar-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .sidebar-header .profile-img {
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .mobile-close {
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
        }
    </style>
</head>

<body>

    <!-- Toggle & Overlay -->
    <button class="btn btn-light mobile-toggle shadow" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
        <i class="fas fa-bars fa-lg"></i>
    </button>
    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar shadow">
        <div class="sidebar-header d-flex align-items-center justify-content-between p-3">
            @php $user = Auth::user(); @endphp
            <div class="d-flex align-items-center">
                <img src="{{ asset($user->foto ? 'storage/' . $user->foto : 'default-profile.png') }}"
                    onerror="this.onerror=null; this.src='{{ asset('default-profile.png') }}';"
                    class="rounded-circle me-2 profile-img" alt="Profile">
                <strong class="d-none d-md-inline">{{ $user->nama ?? $user->name }}</strong>
            </div>
            <i class="fas fa-times d-md-none mobile-close text-white" onclick="toggleSidebar()"></i>
        </div>

        <ul class="nav flex-column px-2 mt-2">
            <li class="nav-item">
                <a href="{{ route('pimpinan.dashboard') }}"
                    class="nav-link {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pimpinan.bonus.index') }}"
                    class="nav-link {{ request()->routeIs('pimpinan.bonus.index') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i> Bonus Karyawan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pimpinan.permissions.index') }}"
                    class="nav-link {{ request()->routeIs('pimpinan.permissions.index') ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i> Persetujuan Ajuan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pimpinan.rekap.bulanan') }}"
                    class="nav-link {{ request()->routeIs('pimpinan.rekap.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Rekap Absensi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pimpinan.permissions.riwayat') }}"
                    class="nav-link {{ request()->routeIs('pimpinan.permissions.riwayat') ? 'active' : '' }}">
                    <i class="fas fa-clock-rotate-left"></i> Riwayat Pengajuan
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('pimpinan.profile.edit') }}"
                    class="nav-link {{ request()->routeIs('pimpinan.profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> Profil
                </a>
            </li>
            <li class="nav-item mt-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link text-danger w-100 text-start">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="main-content">
        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <!-- Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const mainContent = document.getElementById('mainContent');
            const isMobile = window.innerWidth <= 768;

            if (isMobile) {
                sidebar.classList.toggle('show');
                overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : 'auto';
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        }

        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
        });
    </script>

</body>

</html>
