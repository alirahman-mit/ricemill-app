<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Packager</title>

    <!-- Google Fonts: DM Sans + DM Serif Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Iconify (Heroicons) -->
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary:      #1a5c38;
            --primary-light:#2d7a50;
            --accent:       #e8b84b;
            --accent2:      #e8b84b;
            --bg-main:      #f4f6f3;
            --bg-card:      #ffffff;
            --text-main:    #1c2b1e;
            --text-muted:   #6b7c6e;
            --border:       #dde5de;
            --sidebar-w:    265px;
            --sidebar-mini: 68px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-main);
            color: var(--text-main);
            margin: 0;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--primary);
            padding: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow: hidden;
            transition: width .3s ease;
        }

        .sidebar-logo {
            padding: 24px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .sidebar-logo .brand {
            font-family: 'DM Serif Display', serif;
            font-size: 1.3rem;
            color: #fff;
            letter-spacing: .02em;
        }

        .sidebar-logo .sub {
            font-size: .72rem;
            color: rgba(255,255,255,.55);
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .sidebar-logo .role-badge {
            display: inline-block;
            margin-top: 6px;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.2);
            color: #fff;
            font-size: .68rem;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .sidebar-user {
            padding: 18px 24px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-user .avatar {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600;
            color: var(--primary);
            font-size: .85rem;
        }

        .sidebar-user .username {
            font-size: .85rem;
            color: rgba(255,255,255,.9);
            font-weight: 500;
        }

        .sidebar-user .role {
            font-size: .72rem;
            color: rgba(255,255,255,.45);
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: .68rem;
            color: rgba(255,255,255,.35);
            text-transform: uppercase;
            letter-spacing: .12em;
            padding: 8px 12px 4px;
            margin-top: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: rgba(255,255,255,.7);
            text-decoration: none;
            font-size: .88rem;
            font-weight: 400;
            transition: all .18s ease;
            margin-bottom: 2px;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
        }

        .nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
            font-weight: 500;
        }

        .nav-link .iconify { width: 20px; height: 20px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar .page-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
            margin: 0;
        }

        .topbar .breadcrumb {
            font-size: .78rem;
            color: var(--text-muted);
            margin: 0;
        }

        .content-area {
            padding: 28px 32px;
            flex: 1;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }

        .card-header-clean {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-clean h5 {
            font-size: .95rem;
            font-weight: 600;
            color: var(--text-main);
            margin: 0;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px 24px;
            position: relative;
            overflow: hidden;
            transition: transform .18s, box-shadow .18s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(22,163,74,.04);
            transform: translate(20px, -20px);
        }

        .stat-card .stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-card .stat-label {
            font-size: .8rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .stat-card .stat-trend {
            font-size: .75rem;
            font-weight: 500;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-trend.up { color: #16a34a; }
        .stat-trend.neutral { color: var(--text-muted); }

        /* ===== BUTTONS ===== */
        .btn-primary-custom {
            background: var(--primary);
            border: none;
            color: #fff;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: .88rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .18s;
            text-decoration: none;
        }

        .btn-primary-custom:hover {
            background: var(--primary-light);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26,92,56,.25);
        }

        .btn-outline-custom {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
            border-radius: 10px;
            padding: 8px 16px;
            font-size: .85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all .18s;
            text-decoration: none;
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(26,92,56,.04);
        }

        /* ===== TABLE ===== */
        .table-clean thead th {
            font-size: .75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
            background: #f0fdf4;
        }

        .table-clean tbody td {
            padding: 14px 16px;
            font-size: .88rem;
            border-bottom: 1px solid #f0f2f0;
            vertical-align: middle;
            color: var(--text-main);
        }

        .table-clean tbody tr:hover td {
            background: #f0fdf4;
        }

        .table-clean tbody tr:last-child td {
            border-bottom: none;
        }

        /* ===== BADGES ===== */
        .badge-custom {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 500;
        }

        .badge-success-custom  { background:#e8f5ee; color:#1a5c38; }
        .badge-warning-custom  { background:#fef6e0; color:#a0720f; }
        .badge-info-custom     { background:#e8f5e9; color:#2e7d32; }
        .badge-danger-custom   { background:#fde8e8; color:#8b1a1a; }
        .badge-purple-custom   { background:#e8f5e9; color:#2e7d32; }

        /* ===== FORM ===== */
        .form-label-custom {
            font-size: .83rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .form-control-custom, .form-select-custom {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .88rem;
            color: var(--text-main);
            background: #fff;
            transition: border .18s;
            width: 100%;
        }

        .form-control-custom:focus, .form-select-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,92,56,.1);
            outline: none;
        }

        .alert-clean {
            border-radius: 12px;
            padding: 14px 18px;
            font-size: .88rem;
            border: 1px solid;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success-clean { background:#e8f5ee; border-color:#b2dcc4; color:#1a5c38; }
        .alert-danger-clean  { background:#fde8e8; border-color:#f5b8b8; color:#8b1a1a; }

        .upload-zone {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 28px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
        }

        .upload-zone:hover {
            border-color: var(--primary);
            background: rgba(26,92,56,.02);
        }

        /* ===== MINI SIDEBAR (DESKTOP COLLAPSED) ===== */
        .sidebar.collapsed {
            width: var(--sidebar-mini);
        }

        .main-content {
            transition: margin-left .3s ease;
        }

        body.sidebar-collapsed .main-content {
            margin-left: var(--sidebar-mini);
        }

        /* Hide text when mini */
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .nav-section-label,
        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .sidebar-logo .sub,
        .sidebar.collapsed .sidebar-logo .role-badge,
        .sidebar.collapsed .user-info {
            opacity: 0;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
            pointer-events: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 11px 0;
            gap: 0;
        }

        .sidebar.collapsed .sidebar-logo {
            padding: 20px 0;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .sidebar.collapsed .sidebar-logo .brand {
            justify-content: center;
            width: 100%;
            gap: 0 !important;
        }

        .sidebar.collapsed .sidebar-user {
            padding: 14px 0;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-user .d-flex {
            justify-content: center;
            gap: 0;
        }

        .sidebar.collapsed .sidebar-footer .nav-link {
            justify-content: center;
            padding: 11px 0;
        }

        .nav-text, .brand-text, .user-info {
            transition: opacity .2s ease, width .2s ease;
        }

        .sidebar.collapsed .nav-link {
            position: relative;
        }

        .sidebar.collapsed .nav-link:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(var(--sidebar-mini) + 8px);
            top: 50%;
            transform: translateY(-50%);
            background: #1c2b1e;
            color: #fff;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: .8rem;
            white-space: nowrap;
            z-index: 200;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0,0,0,.2);
        }

        /* Toggle button */
        .sidebar-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            cursor: pointer;
            flex-shrink: 0;
            transition: all .18s;
            color: var(--text-main);
        }

        .sidebar-toggle-btn:hover {
            border-color: var(--primary);
            background: rgba(26,92,56,.05);
            color: var(--primary);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 99;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.visible { display: block; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: var(--sidebar-w) !important; }
            .sidebar.open { transform: translateX(0); }
            .sidebar.collapsed { transform: translateX(-100%); }
            .main-content { margin-left: 0 !important; }
        }
    </style>

    @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="brand" style="display:flex; align-items:center; gap:8px; white-space:nowrap;">
            <span class="iconify" data-icon="heroicons:archive-box" style="width:26px; height:26px; color:#60a5fa; flex-shrink:0;"></span>
            <span class="brand-text" style="font-family:'DM Serif Display',serif;font-size:1.15rem;color:#fff;letter-spacing:.02em;">SiMonTani</span>
        </div>
        <div class="sub" style="font-size:.72rem;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.1em;margin-top:4px;">Management System</div>
        <div class="role-badge user-info">Pengemas Beras</div>
    </div>

    <div class="sidebar-user">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar" style="flex-shrink:0;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info" style="overflow:hidden;white-space:nowrap;">
                <div class="username">{{ Auth::user()->name }}</div>
                <div class="role">Packager</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="{{ route('packager.dashboard') }}"
           class="nav-link {{ request()->routeIs('packager.dashboard') ? 'active' : '' }}"
           data-tooltip="Dashboard">
            <span class="iconify" data-icon="heroicons:squares-2x2" style="flex-shrink:0;"></span>
            <span class="nav-text"> Dashboard</span>
        </a>

        <div class="nav-section-label">Penerimaan</div>
        <a href="{{ route('packager.penerimaan-beras.index') }}"
           class="nav-link {{ request()->routeIs('packager.penerimaan-beras.*') ? 'active' : '' }}"
           data-tooltip="Penerimaan Beras">
            <span class="iconify" data-icon="heroicons:inbox-stack" style="flex-shrink:0;"></span>
            <span class="nav-text"> Penerimaan Beras Putih</span>
        </a>

        <div class="nav-section-label">Pengemasan</div>
        <a href="{{ route('packager.pengemasan.index') }}"
           class="nav-link {{ request()->routeIs('packager.pengemasan.*') ? 'active' : '' }}"
           data-tooltip="Hasil Pengemasan">
            <span class="iconify" data-icon="heroicons:cube" style="flex-shrink:0;"></span>
            <span class="nav-text"> Hasil Pengemasan</span>
        </a>

        <div class="nav-section-label">Pesanan</div>
        <a href="{{ route('packager.pesanan.index') }}"
           class="nav-link {{ request()->routeIs('packager.pesanan.*') ? 'active' : '' }}"
           data-tooltip="Pesanan Masuk">
            <span class="iconify" data-icon="heroicons:shopping-cart" style="flex-shrink:0;"></span>
            <span class="nav-text"> Pesanan Masuk</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button" onclick="confirmLogout()" class="nav-link w-100"
                    data-tooltip="Keluar"
                    style="background:none;border:none;cursor:pointer;text-align:left;">
                <span class="iconify" data-icon="heroicons:arrow-left-on-rectangle" style="flex-shrink:0;"></span>
                <span class="nav-text"> Keluar</span>
            </button>
        </form>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-content" id="mainContent">

    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Buka/Tutup Sidebar">
                <span class="iconify" data-icon="heroicons:bars-3" style="width:20px;height:20px;"></span>
            </button>
            <div>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                <nav class="breadcrumb">@yield('breadcrumb', 'Home')</nav>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            @yield('topbar-actions')
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
        <div class="alert-clean alert-success-clean mb-4">
            <span class="iconify" data-icon="heroicons:check-circle" style="width:20px;height:20px;"></span>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert-clean alert-danger-clean mb-4">
            <span class="iconify" data-icon="heroicons:x-circle" style="width:20px;height:20px;"></span>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar     = document.getElementById('sidebar');
        const toggleBtn   = document.getElementById('sidebarToggleBtn');
        const overlay     = document.getElementById('sidebarOverlay');
        const body        = document.body;
        const isMobile    = () => window.innerWidth <= 768;
        const STORAGE_KEY = 'simonTaniSidebarOpen_packager';

        function applyState(open) {
            if (isMobile()) {
                sidebar.classList.toggle('open', open);
                sidebar.classList.remove('collapsed');
                overlay.classList.toggle('visible', open);
                body.classList.remove('sidebar-collapsed');
            } else {
                sidebar.classList.toggle('collapsed', !open);
                sidebar.classList.remove('open');
                overlay.classList.remove('visible');
                body.classList.toggle('sidebar-collapsed', !open);
            }
        }

        const savedOpen = localStorage.getItem(STORAGE_KEY);
        const isOpen = savedOpen === null ? true : savedOpen === 'true';
        applyState(isOpen);

        toggleBtn.addEventListener('click', function () {
            const currentlyOpen = isMobile()
                ? sidebar.classList.contains('open')
                : !sidebar.classList.contains('collapsed');
            const newOpen = !currentlyOpen;
            applyState(newOpen);
            if (!isMobile()) localStorage.setItem(STORAGE_KEY, newOpen);
        });

        overlay.addEventListener('click', function () { applyState(false); });

        window.addEventListener('resize', function () {
            const saved = localStorage.getItem(STORAGE_KEY);
            const open  = saved === null ? true : saved === 'true';
            if (!isMobile()) {
                overlay.classList.remove('visible');
                sidebar.classList.remove('open');
                applyState(open);
            } else {
                sidebar.classList.remove('collapsed');
                body.classList.remove('sidebar-collapsed');
            }
        });
    });

    function confirmLogout() {
        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: 'Sesi Anda akan diakhiri.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1a5c38',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('logout-form').submit();
        });
    }
</script>
@stack('scripts')
</body>
</html>
