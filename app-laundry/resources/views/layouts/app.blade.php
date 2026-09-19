<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laundry newci')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <style>
      :root {
        --primary-color: #0d6efd;
        --sidebar-color: #1a2e4a;
        --sidebar-width: 260px;
        --topbar-height: 64px;
        --transition-speed: 0.3s;
      }

      * { margin: 0; padding: 0; box-sizing: border-box; }

      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fa;
        overflow-x: hidden;
      }

      /* ── Topbar ── */
      .topbar {
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        box-shadow: 0 2px 8px rgba(0,0,0,.1);
        position: fixed;
        top: 0; left: 0; right: 0;
        height: var(--topbar-height);
        z-index: 1030;
        padding: 0 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .topbar .navbar-brand {
        font-size: 1.4rem;
        font-weight: 700;
        color: #fff !important;
        display: flex;
        align-items: center;
        gap: .5rem;
        text-decoration: none;
      }

      .topbar .navbar-brand i { font-size: 1.7rem; }

      /* hamburger — visible only on mobile */
      .sidebar-toggle {
        display: none;
        background: rgba(255,255,255,.15);
        border: none;
        border-radius: .5rem;
        color: #fff;
        font-size: 1.3rem;
        padding: .35rem .6rem;
        cursor: pointer;
        transition: background var(--transition-speed);
      }
      .sidebar-toggle:hover { background: rgba(255,255,255,.25); }

      /* ── Sidebar ── */
      .sidebar {
        position: fixed;
        top: var(--topbar-height);
        left: 0;
        height: calc(100vh - var(--topbar-height));
        width: var(--sidebar-width);
        background: linear-gradient(180deg, var(--sidebar-color) 0%, #2d4563 100%);
        overflow-y: auto;
        overflow-x: hidden;
        padding-top: 1.5rem;
        z-index: 1020;
        transition: transform var(--transition-speed);
      }

      .sidebar .nav-link {
        color: #b8c6db !important;
        padding: .7rem 1.5rem;
        margin: .2rem .75rem;
        border-radius: .5rem;
        transition: all var(--transition-speed);
        display: flex;
        align-items: center;
        gap: .75rem;
        font-size: .93rem;
      }

      .sidebar .nav-link i { width: 20px; text-align: center; font-size: 1rem; }

      .sidebar .nav-link:hover,
      .sidebar .nav-link.active {
        background: rgba(255,255,255,.12);
        color: #fff !important;
        padding-left: 1.75rem;
      }

      /* ── Overlay (mobile) ── */
      .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 1015;
        opacity: 0;
        transition: opacity var(--transition-speed);
      }

      .sidebar-overlay.show {
        display: block;
        opacity: 1;
      }

      /* ── Main Content ── */
      .main-content {
        margin-left: var(--sidebar-width);
        margin-top: var(--topbar-height);
        min-height: calc(100vh - var(--topbar-height));
        transition: margin-left var(--transition-speed);
      }

      .container-fluid-custom { padding: 1.75rem; }

      /* ── Cards ── */
      .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
        transition: all var(--transition-speed);
        border-radius: .75rem;
      }
      .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,.12);
        transform: translateY(-2px);
      }
      .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        border-radius: .75rem .75rem 0 0 !important;
      }

      /* ── Alerts ── */
      .alert { border: none; border-radius: .75rem; box-shadow: 0 2px 8px rgba(0,0,0,.08); }

      /* ── Buttons ── */
      .btn {
        border-radius: .5rem;
        padding: .45rem 1rem;
        transition: all var(--transition-speed);
        font-weight: 500;
      }
      .btn-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        border: none;
      }
      .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(13,110,253,.3); }

      /* ── Tables ── */
      .table { border-collapse: separate; border-spacing: 0 .5rem; }
      .table tbody tr { box-shadow: 0 2px 4px rgba(0,0,0,.05); transition: all var(--transition-speed); }
      .table tbody tr:hover { box-shadow: 0 4px 8px rgba(0,0,0,.1); }

      /* ── Dropdown ── */
      .dropdown-menu { border: none; box-shadow: 0 4px 12px rgba(0,0,0,.15); border-radius: .5rem; }
      .dropdown-item:hover { background-color: #f0f4ff; color: var(--primary-color); }

      /* ── Select2 ── */
      .select2-container--bootstrap-5 .select2-selection {
        min-height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        border-radius: .375rem;
        border: 1px solid #ced4da;
      }
      .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered { line-height: 1.5; }
      .select2-container--bootstrap-5 .select2-selection__arrow { height: calc(1.5em + .75rem + 2px); }

      /* ── Badge ── */
      .badge { padding: .45rem .7rem; border-radius: .5rem; font-weight: 500; }

      /* ── Page header ── */
      .page-header { margin-bottom: 1.5rem; }
      .page-header h1 { font-weight: 700; color: #1a2e4a; font-size: 1.6rem; margin-bottom: .25rem; }
      .page-header p { color: #6c757d; font-size: .92rem; }

      .rounded-cs { border-radius: 10px; }

      /* ══════════════════════════════
         RESPONSIVE — Mobile & Tablet
         ══════════════════════════════ */
      @media (max-width: 991.98px) {

        .sidebar-toggle { display: inline-flex; align-items: center; }

        /* Hide sidebar off-screen by default */
        .sidebar {
          transform: translateX(-100%);
          width: min(var(--sidebar-width), 80vw);
          z-index: 1025;
        }

        /* Show when toggled */
        .sidebar.show { transform: translateX(0); }

        /* Full-width content on mobile */
        .main-content { margin-left: 0; }

        .container-fluid-custom { padding: 1rem; }

        /* Tighten topbar brand on very small screens */
        .topbar .navbar-brand span { display: none; }
      }

      @media (max-width: 575.98px) {
        .topbar { padding: 0 .9rem; }
        .container-fluid-custom { padding: .75rem; }
        .page-header h1 { font-size: 1.3rem; }
      }
    </style>
  </head>
  <body>

    <!-- ── Topbar ── -->
    <div class="topbar">
      <div class="d-flex align-items-center gap-2">
        <!-- Hamburger (mobile only) -->
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
          <i class="bi bi-list"></i>
        </button>

        <a class="navbar-brand" href="{{ route('dashboard') }}">
          <i class="bi bi-shop"></i>
          <span> laundry newci</span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        @auth
          <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 py-1 px-2"
                    type="button" id="adminDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i>
              <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
              <li>
                <span class="dropdown-item-text">
                  <strong>
                    {{ auth()->user()->role === 'admin' ? 'Administrator'
                      : (auth()->user()->role === 'operator' ? 'Operator' : 'Kasir') }}
                  </strong>
                </span>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                  @csrf
                  <button type="submit" class="dropdown-item">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login</a>
        @endauth
      </div>
    </div>

    <!-- ── Overlay (mobile backdrop) ── -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ── Sidebar ── -->
    <nav class="sidebar" id="sidebar">
      <ul class="nav flex-column pb-4">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
             href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
             href="{{ route('transactions.index') }}">
            <i class="bi bi-receipt"></i><span>Transaksi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
             href="{{ route('customers.index') }}">
            <i class="bi bi-people"></i><span>Pelanggan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}"
             href="{{ route('services.index') }}">
            <i class="bi bi-tags"></i><span>Layanan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}"
             href="{{ route('expenses.index') }}">
            <i class="bi bi-wallet2"></i><span>Pengeluaran</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('operations.*') ? 'active' : '' }}"
             href="{{ route('operations.index') }}">
            <i class="bi bi-gear"></i><span>Operasional</span>
          </a>
        </li>
        @if(auth()->check() && auth()->user()->role === 'admin')
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
               href="{{ route('reports.index') }}">
              <i class="bi bi-graph-up"></i><span>Laporan</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
               href="{{ route('users.index') }}">
              <i class="bi bi-shield-lock"></i><span>Pengguna</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
               href="{{ route('settings.index') }}">
              <i class="bi bi-gear-fill"></i><span>Pengaturan</span>
            </a>
          </li>
        @endif
      </ul>
    </nav>

    <!-- ── Main Content ── -->
    <div class="main-content" id="mainContent">
      <div class="container-fluid-custom">

        @if(session('status'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0 mt-2">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @yield('content')
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
      // ── Sidebar toggle ──
      const sidebar      = document.getElementById('sidebar');
      const overlay      = document.getElementById('sidebarOverlay');
      const toggleBtn    = document.getElementById('sidebarToggle');

      function openSidebar() {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
      }

      function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
      }

      toggleBtn.addEventListener('click', () => {
        sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
      });

      overlay.addEventListener('click', closeSidebar);

      // Close sidebar when a nav link is tapped on mobile
      sidebar.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
          if (window.innerWidth < 992) closeSidebar();
        });
      });

      // ── DataTable ──
      $(document).ready(function () {
        $('.dataTable').DataTable({
          language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' },
          responsive: true,
          paging: true,
          searching: true,
          ordering: true,
          pageLength: 10,
          lengthMenu: [10, 25, 50, 100]
        });
      });
    </script>
    @stack('scripts')
  </body>
</html>