<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruitment System | PT JagooIT</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-width: 250px; max-width: 250px; min-height: 100vh; background: #1e293b; color: white; transition: all 0.3s; position: sticky; top: 0; z-index: 1000; }
        .nav-link { color: #cbd5e1; padding: 12px 20px; border-radius: 8px; margin: 5px 15px; display: flex; align-items: center; text-decoration: none; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #334155; color: white; }
        .nav-link.active { border-left: 4px solid #0d6efd; }
        .main-content { width: 100%; padding: 30px; min-height: 100vh; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .btn-logout { color: #fb7185; padding: 12px 20px; border-radius: 8px; margin: 5px 15px; transition: 0.3s; display: flex; align-items: center; width: calc(100% - 30px); cursor: pointer; }
        .btn-logout:hover { background: #47171d; color: #f43f5e; }
        .menu-header { font-size: 0.7rem; color: #64748b; padding: 15px 35px 5px; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <div class="p-4 text-center">
            <h4 class="fw-bold text-white mb-0">Jagoo<span class="text-primary">IT</span></h4>
            <small class="text-secondary small">DSS Recruitment SAW</small>
        </div>
        <hr class="mx-3 border-secondary">

        <nav class="nav flex-column flex-grow-1">
            {{-- LOGIKA PENENTUAN ROUTE DASHBOARD BERDASARKAN ROLE --}}
            @php
                $role = strtolower(auth()->user()->role);
                if ($role === 'hcm') {
                    $dashRoute = 'hcm.dashboard';
                } elseif ($role === 'am') {
                    $dashRoute = 'am.dashboard';
                } else {
                    $dashRoute = 'director.dashboard';
                }
            @endphp

            {{-- Link Dashboard Utama --}}
            <a href="{{ route($dashRoute) }}" class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            {{-- MENU KHUSUS HCM --}}
            @if($role == 'hcm')
                <div class="menu-header fw-bold">Manajemen Data</div>
                <a href="{{ route('hcm.positions') }}" class="nav-link {{ request()->routeIs('hcm.positions') ? 'active' : '' }}">
                    <i class="bi bi-briefcase me-2"></i> Posisi Jabatan
                </a>
                <a href="{{ route('hcm.candidates') }}" class="nav-link {{ request()->routeIs('hcm.candidates*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Data Pelamar
                </a>

                <div class="menu-header fw-bold">Proses Seleksi</div>
                <a href="{{ route('hcm.aptitude') }}" class="nav-link {{ request()->routeIs('hcm.aptitude') ? 'active' : '' }}">
                    <i class="bi bi-journal-check me-2"></i> Nilai Aptitude (C1)
                </a>
                <a href="{{ route('hcm.logs') }}" class="nav-link {{ request()->routeIs('hcm.logs') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock me-2"></i> Audit Logs
                </a>

                <div class="menu-header fw-bold">Sistem</div>
                <a href="{{ route('hcm.users') }}" class="nav-link {{ request()->routeIs('hcm.users') ? 'active' : '' }}">
                    <i class="bi bi-person-gear me-2"></i> Manajemen User
                </a>
            @endif

            {{-- MENU KHUSUS ACCOUNT MANAGER (AM) --}}
            @if($role == 'am')
                <div class="menu-header fw-bold">Validasi Teknis</div>
                <a href="{{ route('am.assessment') }}" class="nav-link {{ request()->routeIs('am.assessment') ? 'active' : '' }}">
                    <i class="bi bi-code-square me-2"></i> Technical Test (C3)
                </a>
                <a href="{{ route('am.history') }}" class="nav-link {{ request()->routeIs('am.history') ? 'active' : '' }}">
                    <i class="bi bi-clock-history me-2"></i> Riwayat Penilaian
                </a>
            @endif

            {{-- MENU KHUSUS DIREKTUR --}}
            @if($role == 'direktur' || $role == 'director')
                <div class="menu-header fw-bold">Laporan & Keputusan</div>
                <a href="{{ route('director.assessment') }}" class="nav-link {{ request()->routeIs('director.assessment') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check me-2"></i> Penilaian Final
                </a>
                <a href="{{ route('director.ranking') }}" class="nav-link {{ request()->routeIs('director.ranking') ? 'active' : '' }}">
                    <i class="bi bi-trophy me-2"></i> Ranking SAW
                </a>
            @endif
        </nav>

        <!-- User Profile & Logout -->
        <div class="mt-auto pb-4">
            <hr class="mx-3 border-secondary">
            <div class="px-4 mb-2">
                <p class="text-white small mb-0 fw-bold">{{ auth()->user()->name }}</p>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Role: {{ $role }}</small>
            </div>

            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="btn-logout border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
