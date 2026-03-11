@extends('layout.petugas')
@section('title','Dashboard Petugas')
@section('page-title','Dashboard')
@section('content')

{{-- PEMBERITAHUAN --}}
@php
  $notifs = [];
  foreach($jadwalHariIni as $j) {
    if (!$j->sudahLaporan()) {
      $notifs[] = [
        'type'  => 'warning',
        'icon'  => 'exclamation-triangle-fill',
        'text'  => 'Jadwal <strong>' . $j->nama_kegiatan . '</strong> belum ada laporan.',
        'link'  => route('petugas.laporan.create', $j),
        'label' => 'Buat Laporan',
      ];
    }
  }
  foreach(($jadwalMendatang ?? collect())->where('tanggal', today()->addDay()->toDateString()) as $j) {
    $notifs[] = [
      'type'  => 'success',
      'icon'  => 'calendar-event-fill',
      'text'  => 'Besok: <strong>' . $j->nama_kegiatan . '</strong> — ' . $j->shift->nama . ' di ' . $j->lokasi->nama,
      'link'  => null,
      'label' => null,
    ];
  }
@endphp

@if(count($notifs) > 0)
<div class="mb-3" id="notifBox">
  <div class="d-flex align-items-center justify-content-between mb-2">
    <span class="fw-bold" style="font-size:13px">
      <i class="bi bi-bell-fill text-warning me-1"></i>
      Pemberitahuan
      <span class="badge bg-danger ms-1" style="font-size:10px">{{ count($notifs) }}</span>
    </span>
    <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px"
            onclick="document.getElementById('notifBox').style.display='none'">
      <i class="bi bi-x"></i> Tutup
    </button>
  </div>
  @foreach($notifs as $n)
  <div class="d-flex align-items-center gap-2 p-2 mb-2 rounded-3"
       style="background:{{ $n['type']==='warning'?'#fefce8':($n['type']==='info'?'#eff6ff':'#f0fdf4') }};
              border:1px solid {{ $n['type']==='warning'?'#fde047':($n['type']==='info'?'#93c5fd':'#86efac') }}">
    <i class="bi bi-{{ $n['icon'] }} flex-shrink-0"
       style="font-size:15px;color:{{ $n['type']==='warning'?'#ca8a04':($n['type']==='info'?'#2563eb':'#16a34a') }}"></i>
    <div class="flex-grow-1" style="font-size:12px">{!! $n['text'] !!}</div>
    @if($n['link'])
    <a href="{{ $n['link'] }}" class="btn btn-sm flex-shrink-0"
       style="font-size:11px;padding:3px 10px;background:{{ $n['type']==='warning'?'#ca8a04':($n['type']==='info'?'#2563eb':'#16a34a') }};color:#fff;border-radius:20px;white-space:nowrap">
      {{ $n['label'] }}
    </a>
    @endif
  </div>
  @endforeach
</div>
@endif

{{-- PROFIL CARD --}}
<div class="card mb-3 overflow-hidden" style="border:none">
  <div class="p-4 d-flex align-items-center gap-3"
       style="background:linear-gradient(135deg,#1d4ed8,#2563eb,#3b82f6);color:#fff;position:relative;overflow:hidden">
    {{-- Decorative circles --}}
    <div style="position:absolute;right:-30px;top:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.08)"></div>
    <div style="position:absolute;right:40px;bottom:-40px;width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,.06)"></div>

    <img src="{{ $petugas->foto_url }}" class="rounded-circle flex-shrink-0"
         style="width:68px;height:68px;object-fit:cover;border:3px solid rgba(255,255,255,.5);position:relative;z-index:1">
    <div style="position:relative;z-index:1;flex:1">
      <div class="fw-bold" style="font-size:17px">{{ $petugas->nama }}</div>
      <div style="opacity:.85;font-size:13px">{{ $petugas->jabatan }} &bull; {{ $petugas->satuan }}</div>
      <div style="font-size:11px;opacity:.65;margin-top:2px">NIP: {{ $petugas->nip }}</div>
    </div>
    <div class="text-end flex-shrink-0" style="position:relative;z-index:1">
      <div style="font-size:32px;font-weight:800;line-height:1">{{ $totalTugas }}</div>
      <div style="font-size:11px;opacity:.75">Laporan Dikirim</div>
    </div>
  </div>

  {{-- Stat strip --}}
  <div class="d-flex border-top" style="background:#f8fafc">
    <div class="text-center py-2 flex-fill border-end">
      <div class="fw-bold text-primary">{{ $jadwalHariIni->count() }}</div>
      <div class="text-muted" style="font-size:11px">Jadwal Hari Ini</div>
    </div>
    <div class="text-center py-2 flex-fill border-end">
      <div class="fw-bold text-success">{{ $jadwalHariIni->filter(fn($j)=>$j->sudahLaporan())->count() }}</div>
      <div class="text-muted" style="font-size:11px">Sudah Laporan</div>
    </div>
    <div class="text-center py-2 flex-fill">
      <div class="fw-bold text-warning">{{ $jadwalMendatang->count() }}</div>
      <div class="text-muted" style="font-size:11px">Jadwal Mendatang</div>
    </div>
  </div>
</div>

{{-- JADWAL HARI INI --}}
<h6 class="fw-bold mt-1 mb-2" style="font-size:13px">
  <i class="bi bi-calendar-check text-primary me-2"></i>Jadwal Hari Ini
</h6>
@forelse($jadwalHariIni as $j)
<div class="card mb-2" style="border-left:3px solid {{ $j->sudahLaporan()?'#16a34a':'#f59e0b' }}">
  <div class="card-body py-2 px-3">
    <div class="d-flex align-items-center gap-2">
      <div class="flex-grow-1">
        <div class="fw-bold" style="font-size:13px">{{ $j->nama_kegiatan }}</div>
        <div class="text-muted" style="font-size:11px">
          <i class="bi bi-clock me-1"></i>{{ $j->shift->jam_mulai }}–{{ $j->shift->jam_selesai }}
          &nbsp;&bull;&nbsp;
          <i class="bi bi-geo-alt me-1"></i>{{ $j->lokasi->nama }}
        </div>
      </div>
      <div class="d-flex gap-1 flex-shrink-0">
        @if($j->titikRazia->count())
        <a href="{{ route('petugas.jadwal.razia',$j) }}"
           class="btn btn-sm btn-outline-primary" style="font-size:11px;padding:3px 8px">
          <i class="bi bi-geo-alt-fill me-1"></i>Razia
        </a>
        @endif
        @if($j->sudahLaporan())
          <span class="sipko-badge green" style="font-size:11px"><i class="bi bi-check-circle-fill"></i> Terkirim</span>
        @else
          <a href="{{ route('petugas.laporan.create',$j) }}" class="btn btn-sm btn-petugas" style="font-size:12px">
            <i class="bi bi-file-plus me-1"></i>Laporan
          </a>
        @endif
      </div>
    </div>
  </div>
</div>
@empty
<div class="text-center py-4 text-muted">
  <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
  <small>Tidak ada jadwal hari ini.</small>
</div>
@endforelse

{{-- JADWAL MENDATANG --}}
<h6 class="fw-bold mt-3 mb-2" style="font-size:13px">
  <i class="bi bi-calendar3 text-success me-2"></i>Jadwal Mendatang
</h6>
@forelse($jadwalMendatang as $j)
<div class="card mb-2 border-0" style="background:#f8fafc">
  <div class="card-body py-2 px-3">
    <div class="d-flex align-items-center gap-3">
      <div class="text-center rounded-3 flex-shrink-0"
           style="background:#dbeafe;min-width:46px;padding:6px 0">
        <div class="fw-bold text-primary" style="font-size:16px;line-height:1">{{ $j->tanggal->format('d') }}</div>
        <div class="text-muted" style="font-size:10px">{{ $j->tanggal->format('M') }}</div>
      </div>
      <div>
        <div class="fw-semibold" style="font-size:13px">{{ $j->nama_kegiatan }}</div>
        <div class="text-muted" style="font-size:11px">{{ $j->shift->nama }} &bull; {{ $j->lokasi->nama }}</div>
      </div>
      <div class="ms-auto">
        <span class="sipko-badge blue" style="font-size:10px">{{ $j->tanggal->diffForHumans() }}</span>
      </div>
    </div>
  </div>
</div>
@empty
<p class="text-muted small">Tidak ada jadwal mendatang.</p>
@endforelse

{{-- LAPORAN TERAKHIR --}}
<h6 class="fw-bold mt-3 mb-2" style="font-size:13px">
  <i class="bi bi-clock-history text-warning me-2"></i>Laporan Terakhir
</h6>
@forelse($riwayat as $l)
<div class="d-flex align-items-center gap-2 mb-2 p-2 border rounded-3"
     style="background:#fff">
  <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
       style="width:32px;height:32px;font-size:11px;background:#2d6a4f">
    {{ strtoupper(substr($l->jadwal->nama_kegiatan ?? '?', 0, 2)) }}
  </div>
  <div class="flex-grow-1">
    <div class="fw-semibold" style="font-size:12px">{{ $l->jadwal->nama_kegiatan ?? '(Jadwal dihapus)' }}</div>
    <small class="text-muted">{{ $l->waktu_laporan->format('d M Y H:i') }}</small>
  </div>
  <span class="sipko-badge {{ $l->kondisi==='kondusif'?'green':($l->kondisi==='tidak kondusif'?'red':'orange') }}"
        style="font-size:10px">{{ $l->kondisi }}</span>
</div>
@empty
<div class="text-center py-3 text-muted">
  <i class="bi bi-file-x fs-2 d-block mb-2"></i>
  <small>Belum pernah mengirim laporan.</small>
</div>
@endforelse

@endsection
