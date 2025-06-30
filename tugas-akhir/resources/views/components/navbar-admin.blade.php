<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    /* Sidebar Styles */
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

    /* Overlay for mobile */
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

    /* Main content container */
    .main-content {
        margin-left: 250px;
        transition: margin-left 0.3s ease;
        min-height: 100vh;
    }

    .main-content.expanded {
        margin-left: 0;
    }

    /* Mobile toggle button */
    .mobile-toggle {
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1040;
        display: none;
    }

    /* Profile image */
    .profile-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }

    /* Active navigation styles */
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

    /* Responsive behavior */
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

        .sidebar-text {
            display: inline !important;
        }

        .d-none.d-md-inline {
            display: inline !important;
        }
    }

    /* Sidebar header improvements */
    .sidebar-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .sidebar-header .profile-img {
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    /* Navigation improvements */
    .nav-item {
        margin-bottom: 5px;
    }

    .nav-link {
        transition: all 0.3s ease;
        margin: 2px 0;
    }

    /* Dropdown menu positioning */
    .dropdown-menu {
        margin-top: 5px;
    }

    /* Close button for mobile */
    .mobile-close {
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        transition: background 0.3s ease;
    }

    .mobile-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>

<!-- Mobile Toggle Button -->
<!-- Mobile Toggle Button -->
<button class="btn btn-light mobile-toggle shadow" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
    <i class="fas fa-bars fa-lg"></i>
</button>

<!-- Overlay for mobile -->
<div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar" class="sidebar shadow">
    <div class="sidebar-header d-flex align-items-center justify-content-between p-3">
        @php
            $user = Auth::user();
        @endphp
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
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link py-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt me-2"></i>
            <span class="d-none d-md-inline">Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.absensi.index') }}"
           class="nav-link py-2 {{ request()->routeIs('admin.absensi.index') ? 'active' : '' }}">
            <i class="fas fa-user-check me-2"></i>
            <span class="d-none d-md-inline">Presensi</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('verifikasi.permissions') }}"
           class="nav-link py-2 {{ request()->routeIs('verifikasi.permissions') ? 'active' : '' }}">
            <i class="fas fa-envelope-open-text me-2"></i>
            <span class="d-none d-md-inline">Data Izin</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.rekap.index') }}"
           class="nav-link py-2 {{ request()->routeIs('admin.rekap.index') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt me-2"></i>
            <span class="d-none d-md-inline">Rekap Absensi</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}"
           class="nav-link py-2 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users-cog me-2"></i>
            <span class="d-none d-md-inline">Manajemen User</span>
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle py-2" href="#" id="dropdownSettings" data-bs-toggle="dropdown"
           aria-expanded="false">
            <i class="fas fa-cogs me-2"></i>
            <span class="d-none d-md-inline">Pengaturan</span>
        </a>
        <ul class="dropdown-menu shadow">
            <li>
                <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                    <i class="fas fa-user-circle me-2"></i> Profil
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </li>
</ul>
</div>

<!-- Main Content Container -->
<div id="mainContent" class="main-content">
    <!-- Content akan diisi dari blade template yang extend layout ini -->
    <div class="container-fluid p-4">
        @yield('content')
    </div>
</div>

<script>
    // Sidebar toggle functionality
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const mainContent = document.getElementById('mainContent');

        // Check if we're on mobile
        const isMobile = window.innerWidth <= 768;

        if (isMobile) {
            // Mobile behavior
            sidebar.classList.toggle('show');
            if (sidebar.classList.contains('show')) {
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            } else {
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        } else {
            // Desktop behavior
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const mainContent = document.getElementById('mainContent');

        if (window.innerWidth > 768) {
            // Desktop view - reset mobile classes
            sidebar.classList.remove('show');
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';

            // Maintain collapsed state if it was collapsed
            if (!sidebar.classList.contains('collapsed')) {
                mainContent.classList.remove('expanded');
            }
        } else {
            // Mobile view - reset desktop classes
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');

            // Hide sidebar by default on mobile
            if (!sidebar.classList.contains('show')) {
                overlay.style.display = 'none';
            }
        }
    });

    // Close sidebar when clicking on a nav link (mobile only)
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.sidebar .nav-link:not(.dropdown-toggle)');

        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('overlay');

                    sidebar.classList.remove('show');
                    overlay.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });
    });

    // Handle dropdown clicks on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownItems = document.querySelectorAll('.dropdown-item');

        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    setTimeout(() => {
                        const sidebar = document.getElementById('sidebar');
                        const overlay = document.getElementById('overlay');

                        sidebar.classList.remove('show');
                        overlay.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }, 100);
                }
            });
        });
    });
</script>
