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
        /* Salin semua style dari sidebar admin yang kamu lampirkan sebelumnya */
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
            transform: translateX(0);
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
            background: rgba(0, 0, 0, 0.5);
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
            background-color: #f5f5f5;
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

            .sidebar-text,
            .d-none.d-md-inline {
                display: inline !important;
            }
        }

        .sidebar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .sidebar-header .profile-img {
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            transition: all 0.3s ease;
            margin: 2px 0;
        }

        .dropdown-menu {
            margin-top: 5px;
        }

        .mobile-close {
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .mobile-close:hover {
            background: rgba(255, 255, 255, 0.2);
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
            <i class="fas fa-times d-md-none mobile-close" onclick="toggleSidebar()"></i>
        </div>
        <ul class="nav flex-column px-2 mt-2">
            <li class="nav-item">
                <a href="{{ route('pimpinan.dashboard') }}"
                    class="nav-link py-2 {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> <span class="d-none d-md-inline">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pimpinan.bonus.index') }}"
                    class="nav-link py-2 {{ request()->routeIs('pimpinan.bonus.index') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    <span class="d-none d-md-inline">Bonus Karyawan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pimpinan.permissions.index') }}"
                    class="nav-link py-2 {{ request()->routeIs('pimpinan.permissions.index') ? 'active' : '' }}">
                    <i class="fas fa-check-circle me-2"></i>
                    <span class="d-none d-md-inline">Approval Cuti</span>
                </a>
            </li>

            {{-- <li class="nav-item">
            <a href="{{ route('pimpinan.laporan') }}" class="nav-link py-2 {{ request()->routeIs('pimpinan.laporan') ? 'active' : '' }}">
                <i class="fas fa-chart-pie me-2"></i> <span class="d-none d-md-inline">Laporan</span>
            </a>
        </li> --}}
            <li class="nav-item">
                <a href="{{ route('pimpinan.profile.edit') }}"
                    class="nav-link py-2 {{ request()->routeIs('pimpinan.profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-circle me-2"></i> <span class="d-none d-md-inline">Profil</span>
                </a>

            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" class="d-block">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link py-2 w-100 text-start text-danger"
                        style="text-decoration: none;">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        <span class="d-none d-md-inline">Logout</span>
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

        window.addEventListener('resize', function() {
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
