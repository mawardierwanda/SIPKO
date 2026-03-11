@extends('layout.petugas')
@section('title','Jadwal Saya')
@section('page-title','Jadwal Saya')
@section('content')

{{-- Filter --}}
<div class="d-flex gap-2 mb-3 flex-wrap">
  <a href="{{ route('petugas.jadwal.index') }}"
     class="btn btn-sm {{ !request('status') ? 'btn-petugas' : 'btn-outline-secondary' }}">Semua</a>
  <a href="{{ route('petugas.jadwal.index') }}?status=aktif"
     class="btn btn-sm {{ request('status')=='aktif' ? 'btn-petugas' : 'btn-outline-secondary' }}">Aktif</a>
  <a href="{{ route('petugas.jadwal.index') }}?status=selesai"
     class="btn btn-sm {{ request('status')=='selesai' ? 'btn-petugas' : 'btn-outline-secondary' }}">Selesai</a>
</div>

@forelse($jadwal as $j)
@php
  $penugasanSaya = $j->penugasan->firstWhere('petugas_id', $petugas->id);
  $peran         = $penugasanSaya?->peran ?? 'anggota';
@endphp
<div class="card mb-2" style="border-left:3px solid {{ $j->sudahLaporan()?'#16a34a':'#f59e0b' }}">
  <div class="card-body py-2 px-3">
    <div class="d-flex align-items-start justify-content-between gap-2">
      <div class="flex-grow-1">
        <div class="fw-bold" style="font-size:13px">{{ $j->nama_kegiatan }}</div>
        <small class="text-muted">
          <i class="bi bi-calendar3 me-1"></i>{{ $j->tanggal->format('d M Y') }}
          &nbsp;|&nbsp;
          <i class="bi bi-clock me-1"></i>{{ $j->shift->nama ?? '-' }}
          &nbsp;|&nbsp;
          <i class="bi bi-geo-alt me-1"></i>{{ $j->lokasi->nama ?? '-' }}
        </small>
        <div class="mt-1 d-flex flex-wrap gap-1">
          <span class="sipko-badge {{ $peran==='koordinator'?'orange':'blue' }}">
            {{ $peran==='koordinator' ? '👑 Koordinator' : 'Anggota' }}
          </span>
          @if($j->sudahLaporan())
            <span class="sipko-badge green"><i class="bi bi-check-circle-fill"></i> Laporan Terkirim</span>
          @else
            <span class="sipko-badge red">Belum Laporan</span>
          @endif
          @if($j->titikRazia->count())
            <span class="sipko-badge blue">
              <i class="bi bi-geo-alt-fill"></i> {{ $j->titikRazia->count() }} Titik Razia
            </span>
          @endif
        </div>
      </div>

      {{-- Aksi --}}
      <div class="d-flex flex-column gap-1 flex-shrink-0">
        @if($j->titikRazia->count())
        <a href="{{ route('petugas.jadwal.razia', $j) }}"
           class="btn btn-sm btn-outline-primary" style="font-size:12px">
          <i class="bi bi-geo-alt-fill me-1"></i>Razia
        </a>
        @endif
        <a href="{{ route('petugas.jadwal.show', $j) }}"
           class="btn btn-sm btn-outline-secondary" style="font-size:12px">
          <i class="bi bi-eye me-1"></i>Detail
        </a>
        @if(!$j->sudahLaporan() && $j->tanggal <= now())
        <a href="{{ route('petugas.laporan.create', $j) }}"
           class="btn btn-sm btn-petugas" style="font-size:12px">
          <i class="bi bi-file-plus me-1"></i>Laporan
        </a>
        @endif
      </div>
    </div>
  </div>
</div>
@empty
<div class="alert alert-light text-center text-muted py-4">
  <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
  Belum ada jadwal ditugaskan.
</div>
@endforelse

@if($jadwal->hasPages())
<div class="mt-2">{{ $jadwal->links() }}</div>
@endif

@endsection
