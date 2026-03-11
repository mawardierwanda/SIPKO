<!DOCTYPE html>
<html lang="id" data-theme="{{ session('theme','light') }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','SIPKO Petugas') — Satpol PP Ketapang</title>
<link rel="icon" type="image/png" href="{{ asset('asset/SATPL.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">
<style>
:root{--role-color:#1d4ed8;--role-light:rgba(29,78,216,.1);}
.role-chip{background:var(--role-light);color:var(--role-color);border:1px solid rgba(29,78,216,.2);border-radius:20px;padding:3px 12px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;}
.btn-petugas{background:#1d4ed8;border-color:#1d4ed8;color:#fff;}.btn-petugas:hover{background:#1e40af;border-color:#1e40af;color:#fff;}
.sipko-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.sipko-badge.green{background:#dcfce7;color:#16a34a;}.sipko-badge.red{background:#fee2e2;color:#dc2626;}
.sipko-badge.orange{background:#fef3c7;color:#d97706;}.sipko-badge.blue{background:#dbeafe;color:#2563eb;}.sipko-badge.gray{background:#f3f4f6;color:#6b7280;}
.form-step{display:none;}.form-step.active{display:block;}
.step-header{display:flex;gap:16px;margin-bottom:24px;}
.step-indicator{display:flex;align-items:center;gap:8px;font-size:13px;color:#94a3b8;font-weight:600;}
.step-indicator span{width:28px;height:28px;border-radius:50%;background:#e2e8f0;color:#64748b;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;}
.step-indicator.active{color:#1d4ed8;}.step-indicator.active span{background:#1d4ed8;color:#fff;}
</style>
@stack('styles')
</head>
<body>

{{-- HEADER --}}
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('petugas.dashboard') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('asset/SATPL.png') }}" alt="Logo"
           style="width:52px;height:52px;object-fit:contain;border-radius:6px;flex-shrink:0">
      <span class="d-none d-lg-block ms-2">SIPKO<small>Petugas — Satpol PP Ketapang</small></span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn ms-3" id="toggleSidebarBtn"></i>
  </div>
  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center list-unstyled mb-0 gap-2">
      <li>
        <button class="dark-mode-toggle" id="darkModeToggle" onclick="toggleTheme()">
          <i class="bi bi-moon-fill"></i>
        </button>
      </li>
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0 gap-2" href="#" data-bs-toggle="dropdown">
          <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold"
               style="width:36px;height:36px;font-size:13px;flex-shrink:0;background:#1d4ed8">
            {{ strtoupper(substr(auth()->user()->name,0,2)) }}
          </div>
          <span class="d-none d-md-block dropdown-toggle">
            {{ auth()->user()->petugas?->nama ?? auth()->user()->name }}
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ auth()->user()->petugas?->nama ?? auth()->user()->name }}</h6>
            <span>{{ auth()->user()->petugas?->jabatan ?? 'Petugas' }}</span>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item" href="{{ route('petugas.profil') }}">
              <i class="bi bi-person me-2"></i>Profil Saya
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item text-danger">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
              </button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
  <div class="me-3 ms-1">
    <span class="role-chip"><i class="bi bi-person-badge-fill"></i> Petugas</span>
  </div>
</header>

{{-- SIDEBAR --}}
<aside id="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    {{-- Dashboard --}}
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('petugas.dashboard') ? '' : 'collapsed' }}"
         href="{{ route('petugas.dashboard') }}">
        <i class="bi bi-grid"></i><span>Dashboard</span>
      </a>
    </li>

    {{-- Tugas Saya --}}
    <li class="nav-heading">Tugas Saya</li>
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('petugas.jadwal.*') ? '' : 'collapsed' }}"
         href="{{ route('petugas.jadwal.index') }}">
        <i class="bi bi-calendar-check"></i><span>Jadwal Saya</span>
      </a>
    </li>

    {{-- Laporan --}}
    <li class="nav-heading">Laporan</li>
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('petugas.laporan.*') ? '' : 'collapsed' }}"
         href="{{ route('petugas.laporan.index') }}">
        <i class="bi bi-file-earmark-text"></i><span>Laporan Kegiatan</span>
      </a>
    </li>

    {{-- Pengaturan --}}
    <li class="nav-heading">Pengaturan</li>
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('petugas.profil*') ? '' : 'collapsed' }}"
         href="{{ route('petugas.profil') }}">
        <i class="bi bi-person-circle"></i><span>Profil Saya</span>
      </a>
    </li>
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
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @yield('content')
  </section>
</main>

{{-- MOBILE NAV --}}
<nav class="mobile-bottom-nav">
  <a href="{{ route('petugas.dashboard') }}"
     class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid"></i><span>Dashboard</span>
  </a>
  <a href="{{ route('petugas.jadwal.index') }}"
     class="{{ request()->routeIs('petugas.jadwal.*') ? 'active' : '' }}">
    <i class="bi bi-calendar-check"></i><span>Jadwal</span>
  </a>
  <a href="{{ route('petugas.laporan.index') }}"
     class="{{ request()->routeIs('petugas.laporan.*') ? 'active' : '' }}">
    <i class="bi bi-file-text"></i><span>Laporan</span>
  </a>
  <a href="{{ route('petugas.profil') }}"
     class="{{ request()->routeIs('petugas.profil*') ? 'active' : '' }}">
    <i class="bi bi-person"></i><span>Profil</span>
  </a>
</nav>

<footer id="footer" class="footer">
  <div class="copyright">&copy; {{ date('Y') }} <strong>SIPKO</strong> — Satpol PP Kab. Ketapang</div>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('asset/js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
