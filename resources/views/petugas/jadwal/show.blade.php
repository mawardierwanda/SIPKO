@extends('layout.petugas')
@section('title','Detail Jadwal')
@section('page-title','Detail Jadwal')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('petugas.jadwal.index') }}">Jadwal Saya</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="card p-3 mb-3">
  <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Detail Kegiatan</h6>
  <table class="table table-borderless mb-0">
    <tr><td class="text-muted" style="width:35%">Kegiatan</td><td class="fw-semibold">{{ $jadwal->nama_kegiatan }}</td></tr>
    <tr><td class="text-muted">Jenis</td><td>{{ $jadwal->jenisKegiatan->nama }}</td></tr>
    <tr><td class="text-muted">Tanggal</td><td>{{ $jadwal->tanggal->format('l, d F Y') }}</td></tr>
    <tr><td class="text-muted">Shift</td><td><span class="sipko-badge blue">{{ $jadwal->shift->nama }} ({{ $jadwal->shift->jam_mulai }}–{{ $jadwal->shift->jam_selesai }})</span></td></tr>
    <tr><td class="text-muted">Lokasi</td><td>{{ $jadwal->lokasi->nama }}<br><small class="text-muted">{{ $jadwal->lokasi->alamat }}</small></td></tr>
    <tr><td class="text-muted">Peran Anda</td><td><span class="sipko-badge {{ $penugasanSaya?->peran==='koordinator'?'orange':'blue' }}">{{ ucfirst($penugasanSaya?->peran ?? '-') }}</span></td></tr>
    <tr><td class="text-muted">Keterangan</td><td>{{ $jadwal->keterangan ?: '-' }}</td></tr>
  </table>
</div>
<div class="card p-3 mb-3">
  <h6 class="fw-bold mb-3"><i class="bi bi-people text-success me-2"></i>Tim Bertugas</h6>
  @foreach($jadwal->penugasan as $p)
  <div class="d-flex align-items-center gap-2 mb-2">
    <img src="{{ $p->petugas->foto_url }}" class="rounded-circle" style="width:34px;height:34px;object-fit:cover">
    <div class="flex-grow-1"><div class="fw-semibold small">{{ $p->petugas->nama }}</div><small class="text-muted">{{ $p->petugas->jabatan }}</small></div>
    <span class="sipko-badge {{ $p->peran==='koordinator'?'orange':'gray' }}">{{ ucfirst($p->peran) }}</span>
  </div>
  @endforeach
</div>
@if(!$jadwal->sudahLaporan() && $jadwal->tanggal <= now())
<a href="{{ route('petugas.laporan.create',$jadwal) }}" class="btn btn-petugas w-100"><i class="bi bi-file-plus me-1"></i> Buat Laporan Kegiatan Ini</a>
@elseif($jadwal->sudahLaporan())
<a href="{{ route('petugas.laporan.show',$jadwal->laporan) }}" class="btn btn-outline-success w-100"><i class="bi bi-file-check me-1"></i> Lihat Laporan</a>
@endif
@endsection
