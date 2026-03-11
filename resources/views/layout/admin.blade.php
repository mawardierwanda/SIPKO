<!DOCTYPE html>
<html lang="id" data-theme="{{ session('theme','light') }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','SIPKO') Satpol PP Ketapang</title>
<link rel="icon" type="image/png" href="{{ asset('asset/SATPL.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">
<style>
:root{--role-color:#2d6a4f;--role-light:rgba(45,106,79,.12);}
.role-chip{background:var(--role-light);color:var(--role-color);border:1px solid rgba(45,106,79,.2);border-radius:20px;padding:3px 12px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;}
.btn-sipko{background:#2d6a4f;border-color:#2d6a4f;color:#fff;}.btn-sipko:hover{background:#1e4d38;border-color:#1e4d38;color:#fff;}
.sipko-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.sipko-badge.green{background:#dcfce7;color:#16a34a;}.sipko-badge.red{background:#fee2e2;color:#dc2626;}
.sipko-badge.orange{background:#fef3c7;color:#d97706;}.sipko-badge.blue{background:#dbeafe;color:#2563eb;}.sipko-badge.gray{background:#f3f4f6;color:#6b7280;}
table thead th{background:#f8fafc;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#64748b;border-bottom:2px solid #e2e8f0;}
.form-label{font-weight:600;font-size:13px;color:#374151;}
.search-bar input{transition:all .2s;}
.search-bar input:focus{width:280px!important;box-shadow:0 0 0 3px rgba(45,106,79,.15);}
#searchResults{position:absolute;top:calc(100% + 6px);left:0;right:0;background:var(--bg-card,#fff);border:1px solid #e2e8f0;border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:9999;max-height:320px;overflow-y:auto;display:none;}
#searchResults.show{display:block;}
#searchResults .search-item{padding:10px 14px;cursor:pointer;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px;font-size:13px;color:#374151;transition:background .15s;}
#searchResults .search-item:hover{background:#f0fdf4;}
#searchResults .search-item i{color:#2d6a4f;font-size:15px;flex-shrink:0;}
#searchResults .search-item .search-label{font-weight:600;}
#searchResults .search-item .search-sub{font-size:11px;color:#94a3b8;}
#searchResults .search-empty{padding:16px;text-align:center;color:#94a3b8;font-size:13px;}
#searchResults .search-section{padding:6px 14px;font-size:10px;font-weight:700;text-transform:uppercase;color:#94a3b8;background:#f8fafc;letter-spacing:.5px;}
.notif-dot{position:absolute;top:2px;right:2px;width:8px;height:8px;background:#dc2626;border-radius:50%;border:2px solid #fff;}
.notif-dropdown{min-width:320px;padding:0;border:1px solid #e2e8f0;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);overflow:hidden;}
.notif-header{padding:12px 16px;font-weight:700;font-size:13px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;background:#f8fafc;}
.notif-item{padding:12px 16px;border-bottom:1px solid #f1f5f9;display:flex;gap:12px;align-items:flex-start;cursor:pointer;transition:background .15s;}
.notif-item:hover{background:#f0fdf4;}
.notif-item.unread{background:#f0fdf4;}
.notif-item .notif-icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px;}
.notif-item .notif-text{font-size:12px;color:#374151;line-height:1.5;}
.notif-item .notif-text strong{display:block;margin-bottom:2px;}
.notif-item .notif-time{font-size:10px;color:#94a3b8;margin-top:3px;}
.notif-empty{padding:24px;text-align:center;color:#94a3b8;font-size:13px;}
.notif-footer{padding:10px 16px;text-align:center;border-top:1px solid #e2e8f0;background:#f8fafc;}
.notif-footer a{font-size:12px;color:#2d6a4f;font-weight:600;}
</style>
@stack('styles')
</head>
<body>

{{-- HEADER --}}
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('asset/SATPL.png') }}" alt="Logo" style="width:52px;height:52px;object-fit:contain;border-radius:6px;flex-shrink:0">
      <span class="d-none d-lg-block ms-2">SIPKO<small>Satpol PP Ketapang</small></span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn ms-3" id="toggleSidebarBtn"></i>
  </div>

  <div class="search-bar position-relative ms-3 d-none d-lg-block" style="width:240px">
    <input type="text" id="globalSearch" placeholder="Cari jadwal, petugas..." autocomplete="off"
           style="width:100%;padding:6px 36px 6px 12px;border:1px solid #e2e8f0;border-radius:20px;font-size:13px;background:var(--bg-input,#f6f9ff);color:var(--text-primary,#012970);outline:none;">
    <i class="bi bi-search" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:14px;"></i>
    <div id="searchResults"></div>
  </div>

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center list-unstyled mb-0 gap-1">
      <li><button class="dark-mode-toggle" id="darkModeToggle" onclick="toggleTheme()" title="Toggle Mode"><i class="bi bi-moon-fill"></i></button></li>
      <li class="nav-item dropdown">
        <a class="nav-link nav-icon position-relative px-2" href="#" data-bs-toggle="dropdown" id="notifToggle">
          <i class="bi bi-bell" style="font-size:20px;color:var(--text-primary,#012970)"></i>
          @php
            $belumLaporan = \App\Models\Jadwal::whereDate('tanggal','<',today())
              ->where('status','aktif')
              ->whereDoesntHave('laporan')->count();
            $laporanBaru = \App\Models\Laporan::where('status','diterima')
              ->whereDate('created_at',today())->count();
            $totalNotif = $belumLaporan + $laporanBaru;
          @endphp
          @if($totalNotif > 0)<span class="notif-dot"></span>@endif
        </a>
        <div class="dropdown-menu dropdown-menu-end notif-dropdown">
          <div class="notif-header">
            <span><i class="bi bi-bell me-1"></i> Notifikasi</span>
            @if($totalNotif > 0)<span class="badge bg-danger">{{ $totalNotif }}</span>@endif
          </div>
          @if($belumLaporan > 0)
          <a href="{{ route('admin.laporan.belum') }}" class="text-decoration-none">
            <div class="notif-item unread">
              <div class="notif-icon" style="background:#fee2e2;color:#dc2626"><i class="bi bi-exclamation-triangle-fill"></i></div>
              <div>
                <div class="notif-text"><strong>{{ $belumLaporan }} Jadwal Belum Laporan</strong>Jadwal yang sudah lewat belum ada laporan masuk.</div>
                <div class="notif-time"><i class="bi bi-clock me-1"></i>Hari ini</div>
              </div>
            </div>
          </a>
          @endif
          @if($laporanBaru > 0)
          <a href="{{ route('admin.laporan.index') }}" class="text-decoration-none">
            <div class="notif-item unread">
              <div class="notif-icon" style="background:#dcfce7;color:#16a34a"><i class="bi bi-file-earmark-check-fill"></i></div>
              <div>
                <div class="notif-text"><strong>{{ $laporanBaru }} Laporan Baru Masuk</strong>Laporan kegiatan hari ini sudah tersedia.</div>
                <div class="notif-time"><i class="bi bi-clock me-1"></i>Hari ini</div>
              </div>
            </div>
          </a>
          @endif
          @if($totalNotif === 0)
          <div class="notif-empty"><i class="bi bi-check-circle text-success d-block mb-2" style="font-size:24px"></i>Semua jadwal tertangani</div>
          @endif
          <div class="notif-footer"><a href="{{ route('admin.status.index') }}">Lihat Status Jadwal →</a></div>
        </div>
      </li>
      <li class="nav-item dropdown pe-2">
        <a class="nav-link nav-profile d-flex align-items-center pe-0 gap-2" href="#" data-bs-toggle="dropdown">
          <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;font-size:13px;flex-shrink:0;background:#2d6a4f">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
          <span class="d-none d-md-block dropdown-toggle" style="font-size:13px;font-weight:600">{{ auth()->user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header"><h6>{{ auth()->user()->name }}</h6><span>Administrator</span></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="{{ route('admin.profil') }}"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
  <div class="me-3 ms-1 d-none d-md-block"><span class="role-chip"><i class="bi bi-shield-fill-check"></i> Administrator</span></div>
</header>

{{-- SIDEBAR --}}
<aside id="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-grid"></i><span>Dashboard</span>
      </a>
    </li>

    <li class="nav-heading">Master Data</li>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-database"></i><span>Master Data</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="master-nav" class="nav-content collapse {{ request()->routeIs('admin.petugas.*','admin.shifts.*','admin.lokasi.*','admin.jenis-kegiatan.*') ? 'show' : '' }}">
        <li><a href="{{ route('admin.petugas.index') }}" class="{{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Data Petugas</span></a></li>
        <li><a href="{{ route('admin.shifts.index') }}" class="{{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Manajemen Shift</span></a></li>
        <li><a href="{{ route('admin.lokasi.index') }}" class="{{ request()->routeIs('admin.lokasi.*') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Data Lokasi</span></a></li>
        <li><a href="{{ route('admin.jenis-kegiatan.index') }}" class="{{ request()->routeIs('admin.jenis-kegiatan.*') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Jenis Kegiatan</span></a></li>
      </ul>
    </li>

    <li class="nav-heading">Jadwal & Penugasan</li>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#jadwal-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-calendar-check"></i><span>Jadwal Operasional</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="jadwal-nav" class="nav-content collapse {{ request()->routeIs('admin.jadwal.*','admin.penugasan.*','admin.titik-razia.*') ? 'show' : '' }}">
        <li><a href="{{ route('admin.jadwal.index') }}" class="{{ request()->routeIs('admin.jadwal.index') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Kelola Jadwal</span></a></li>
        <li><a href="{{ route('admin.penugasan.index') }}" class="{{ request()->routeIs('admin.penugasan.*') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Penugasan Tim</span></a></li>
        <li><a href="{{ route('admin.titik-razia.index') }}" class="{{ request()->routeIs('admin.titik-razia.*') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Razia Multi-Lokasi</span></a></li>
        <li><a href="{{ route('admin.jadwal.riwayat') }}" class="{{ request()->routeIs('admin.jadwal.riwayat') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Riwayat Jadwal</span></a></li>
      </ul>
    </li>

    <li class="nav-heading">Laporan</li>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#laporan-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-earmark-text"></i><span>Laporan Kegiatan</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="laporan-nav" class="nav-content collapse {{ request()->routeIs('admin.laporan.*') ? 'show' : '' }}">
        <li><a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Laporan Masuk</span></a></li>
        <li><a href="{{ route('admin.laporan.belum') }}" class="{{ request()->routeIs('admin.laporan.belum') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Belum Laporan</span>@if($belumLaporan > 0)<span class="badge bg-danger ms-1" style="font-size:10px">{{ $belumLaporan }}</span>@endif</a></li>
      </ul>
    </li>

    <li class="nav-heading">Pemantauan</li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.status.*') ? '' : 'collapsed' }}" href="{{ route('admin.status.index') }}"><i class="bi bi-bar-chart-line"></i><span>Status Jadwal</span></a></li>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#rekap-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-earmark-bar-graph"></i><span>Rekapitulasi</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="rekap-nav" class="nav-content collapse {{ request()->routeIs('admin.rekap.*') ? 'show' : '' }}">
        <li><a href="{{ route('admin.rekap.index') }}"><i class="bi bi-circle"></i><span>Rekap Periode</span></a></li>
        <li><a href="{{ route('admin.rekap.pdf') }}" target="_blank"><i class="bi bi-circle"></i><span>Export PDF</span></a></li>
        <li><a href="{{ route('admin.rekap.excel') }}"><i class="bi bi-circle"></i><span>Export Excel</span></a></li>
      </ul>
    </li>

    <li class="nav-heading">Manajemen</li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users.*') ? '' : 'collapsed' }}" href="{{ route('admin.users.index') }}"><i class="bi bi-person-gear"></i><span>Manajemen User</span></a></li>

    <li class="nav-heading">Pengaturan</li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.profil*') ? '' : 'collapsed' }}" href="{{ route('admin.profil') }}"><i class="bi bi-person-circle"></i><span>Profil Admin</span></a></li>
    <li class="nav-item">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-link collapsed text-danger w-100 border-0 bg-transparent text-start">
          <i class="bi bi-box-arrow-right" style="color:#dc2626"></i><span>Logout</span>
        </button>
      </form>
    </li>
  </ul>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- MAIN --}}
<main id="main" class="main">
  <div class="pagetitle">
    <h1>@yield('page-title','Dashboard')</h1>
    <nav><ol class="breadcrumb">@yield('breadcrumb')</ol></nav>
  </div>
  <section class="section">
    @if(session('success'))<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if(session('error'))<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @yield('content')
  </section>
</main>

{{-- MOBILE BOTTOM NAV --}}
<nav class="mobile-bottom-nav">
  <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-grid"></i><span>Dashboard</span></a>
  <a href="{{ route('admin.jadwal.index') }}" class="{{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}"><i class="bi bi-calendar-check"></i><span>Jadwal</span></a>
  <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}"><i class="bi bi-file-text"></i><span>Laporan</span></a>
  <a href="{{ route('admin.status.index') }}" class="{{ request()->routeIs('admin.status.*') ? 'active' : '' }}"><i class="bi bi-bar-chart"></i><span>Status</span></a>
  <a href="{{ route('admin.profil') }}" class="{{ request()->routeIs('admin.profil*') ? 'active' : '' }}"><i class="bi bi-person"></i><span>Profil</span></a>
</nav>

<footer id="footer" class="footer"><div class="copyright">&copy; {{ date('Y') }} <strong>SIPKO</strong> — Satpol PP Kab. Ketapang</div></footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('asset/js/main.js') }}"></script>
<script>
(function() {
  const notifToggle = document.getElementById('notifToggle');
  if (!notifToggle) return;
  const dropdownEl = notifToggle.closest('.dropdown');
  if (dropdownEl) {
    dropdownEl.addEventListener('show.bs.dropdown', function() {
      dropdownEl.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
      const dot = dropdownEl.querySelector('.notif-dot');
      if (dot) dot.style.display = 'none';
    });
  }
})();
</script>
<script>
(function() {
  const input = document.getElementById('globalSearch');
  const results = document.getElementById('searchResults');
  if (!input) return;
  const menuItems = [
    { label: 'Dashboard',         sub: 'Halaman utama admin',          url: '{{ route("admin.dashboard") }}',             icon: 'bi-grid' },
    { label: 'Data Petugas',      sub: 'Kelola data petugas lapangan', url: '{{ route("admin.petugas.index") }}',         icon: 'bi-people' },
    { label: 'Manajemen Shift',   sub: 'Atur shift kerja',             url: '{{ route("admin.shifts.index") }}',          icon: 'bi-clock' },
    { label: 'Data Lokasi',       sub: 'Kelola lokasi operasional',    url: '{{ route("admin.lokasi.index") }}',          icon: 'bi-geo-alt' },
    { label: 'Jenis Kegiatan',    sub: 'Patroli, Razia, Piket, dll',   url: '{{ route("admin.jenis-kegiatan.index") }}',  icon: 'bi-tags' },
    { label: 'Kelola Jadwal',     sub: 'Kelola semua jadwal',          url: '{{ route("admin.jadwal.index") }}',          icon: 'bi-calendar-check' },
    { label: 'Buat Jadwal',       sub: 'Tambah jadwal baru',           url: '{{ route("admin.jadwal.create") }}',         icon: 'bi-calendar-plus' },
    { label: 'Riwayat Jadwal',    sub: 'Jadwal yang sudah dihapus',    url: '{{ route("admin.jadwal.riwayat") }}',        icon: 'bi-archive' },
    { label: 'Penugasan Tim',     sub: 'Assign petugas ke jadwal',     url: '{{ route("admin.penugasan.index") }}',       icon: 'bi-people-fill' },
    { label: 'Razia Multi-Lokasi', sub: 'Atur titik razia per jadwal',  url: '{{ route("admin.titik-razia.index") }}',     icon: 'bi-geo-alt-fill' },
    { label: 'Razia Multi-Lokasi', sub: 'Atur titik razia per jadwal',  url: '{{ route("admin.titik-razia.index") }}',     icon: 'bi-geo-alt-fill' },
    { label: 'Laporan Masuk',     sub: 'Semua laporan kegiatan',       url: '{{ route("admin.laporan.index") }}',         icon: 'bi-file-earmark-text' },
    { label: 'Belum Laporan',     sub: 'Jadwal tanpa laporan',         url: '{{ route("admin.laporan.belum") }}',         icon: 'bi-exclamation-triangle' },
    { label: 'Status Jadwal',     sub: 'Status hari ini per shift',    url: '{{ route("admin.status.index") }}',          icon: 'bi-bar-chart-line' },
    { label: 'Rekapitulasi',      sub: 'Rekap periode kegiatan',       url: '{{ route("admin.rekap.index") }}',           icon: 'bi-file-earmark-bar-graph' },
    { label: 'Export PDF',        sub: 'Download rekap PDF',           url: '{{ route("admin.rekap.pdf") }}',             icon: 'bi-file-pdf' },
    { label: 'Export Excel',      sub: 'Download rekap Excel',         url: '{{ route("admin.rekap.excel") }}',           icon: 'bi-file-excel' },
    { label: 'Manajemen User',    sub: 'Kelola akun pengguna',         url: '{{ route("admin.users.index") }}',           icon: 'bi-person-gear' },
    { label: 'Profil Admin',      sub: 'Edit profil & password',       url: '{{ route("admin.profil") }}',                icon: 'bi-person-circle' },
  ];
  let timer;
  input.addEventListener('input', function() {
    clearTimeout(timer);
    timer = setTimeout(() => doSearch(this.value.trim()), 200);
  });
  input.addEventListener('focus', function() {
    if (this.value.trim().length > 1) results.classList.add('show');
  });
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.search-bar')) results.classList.remove('show');
  });
  function doSearch(q) {
    if (q.length < 2) { results.classList.remove('show'); return; }
    const filtered = menuItems.filter(i => i.label.toLowerCase().includes(q.toLowerCase()) || i.sub.toLowerCase().includes(q.toLowerCase()));
    results.innerHTML = filtered.length === 0
      ? '<div class="search-empty"><i class="bi bi-search d-block mb-1" style="font-size:20px"></i>Tidak ditemukan untuk "' + q + '"</div>'
      : '<div class="search-section">Menu & Halaman</div>' + filtered.map(i => `<a href="${i.url}" class="text-decoration-none"><div class="search-item"><i class="bi ${i.icon}"></i><div><div class="search-label">${i.label}</div><div class="search-sub">${i.sub}</div></div></div></a>`).join('');
    results.classList.add('show');
  }
})();
</script>
@stack('scripts')
</body>
</html>
