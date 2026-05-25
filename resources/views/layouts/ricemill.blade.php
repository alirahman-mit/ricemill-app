<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Rice Mill</title>

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
            transition: transform .3s ease;
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
            box-shadow: 0 4px 12px rgba(26,58,92,.25);
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
            background: rgba(26,58,92,.04);
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
            background: #f5f8fc;
        }

        .table-clean tbody td {
            padding: 14px 16px;
            font-size: .88rem;
            border-bottom: 1px solid #f0f3f7;
            vertical-align: middle;
            color: var(--text-main);
        }

        .table-clean tbody tr:hover td {
            background: #f5f8fc;
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
        .badge-purple-custom   { background:#f0e8ff; color:#5b21b6; }

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
            box-shadow: 0 0 0 3px rgba(26,58,92,.1);
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
            background: rgba(26,58,92,.02);
        }

        /* ===== STATUS STEPS ===== */
        .status-step {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            background: #f5f8fc;
            border: 1px solid var(--border);
            font-size: .85rem;
        }

        .status-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .dot-menunggu { background: #f59e0b; }
        .dot-diproses { background: #16a34a; }
        .dot-selesai  { background: #16a34a; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="brand flex items-center gap-2" style="display:flex; align-items:center; gap:8px;">
            <span class="iconify text-[#8B5A2B]" data-icon="heroicons:building-office-2" style="width:24px; height:24px;"></span>
            SiMonTani
        </div>
        <div class="sub">Management System</div>
        <div class="role-badge">Rice Mill Operator</div>
    </div>

    <div class="sidebar-user">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div class="username">{{ Auth::user()->name }}</div>
                <div class="role">Rice Mill</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="{{ route('ricemill.dashboard') }}"
           class="nav-link {{ request()->routeIs('ricemill.dashboard') ? 'active' : '' }}">
            <span class="iconify" data-icon="heroicons:squares-2x2"></span> Dashboard
        </a>

        <div class="nav-section-label">Penerimaan Gabah</div>
        <a href="{{ route('ricemill.penerimaan-gabah.index') }}"
           class="nav-link {{ request()->routeIs('ricemill.penerimaan-gabah.*') ? 'active' : '' }}">
            <span class="iconify" data-icon="heroicons:inbox-stack"></span> Penerimaan Gabah
        </a>

        <div class="nav-section-label">Operasional</div>
        <a href="{{ route('ricemill.operasional.index') }}"
           class="nav-link {{ request()->routeIs('ricemill.operasional.*') ? 'active' : '' }}">
            <span class="iconify" data-icon="heroicons:cog-6-tooth"></span> Operasional Penggilingan
        </a>
        <a href="{{ route('ricemill.produksi.index') }}"
           class="nav-link {{ request()->routeIs('ricemill.produksi.*') ? 'active' : '' }}">
            <span class="iconify" data-icon="heroicons:arrow-trending-up"></span> Riwayat Produksi
        </a>

        <div class="nav-section-label">Distribusi</div>
        <a href="{{ route('ricemill.pengiriman.index') }}"
           class="nav-link {{ request()->routeIs('ricemill.pengiriman.*') ? 'active' : '' }}">
            <span class="iconify" data-icon="heroicons:truck"></span> Pengiriman Beras
        </a>

        <div class="nav-section-label">Keuangan</div>
        <a href="{{ route('ricemill.keuangan.index') }}"
           class="nav-link {{ request()->routeIs('ricemill.keuangan.*') ? 'active' : '' }}">
            <span class="iconify" data-icon="heroicons:presentation-chart-bar"></span> Laporan Keuangan
        </a>
    </nav>

    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button" onclick="confirmLogout()" class="nav-link w-100" style="background:none;border:none;cursor:pointer;text-align:left;">
                <span class="iconify" data-icon="heroicons:arrow-left-on-rectangle"></span> Keluar
            </button>
        </form>
    </div>
</aside>

<div class="main-content">

    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-md-none p-1 border-0 shadow-sm" id="mobileMenuBtn" style="background:#fff;">
                <span class="iconify text-slate-700" data-icon="heroicons:bars-3" style="width:24px;height:24px;"></span>
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

        @if(session('warning'))
        <div class="alert-clean mb-4" style="background:#fef9c3;border-color:#fde047;color:#854d0e;">
            <span class="iconify" data-icon="heroicons:exclamation-triangle" style="width:20px;height:20px;color:#ca8a04;"></span>
            <strong>Peringatan:</strong> {{ session('warning') }}
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
    document.addEventListener('DOMContentLoaded', function() {
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        if(mobileBtn && sidebar) {
            mobileBtn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
        }
    });

    function confirmLogout() {
        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Sesi Anda akan diakhiri.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1a5c38',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@stack('scripts')
</body>
</html>
