@extends('layout.petugas')
@section('title','Detail Laporan')
@section('page-title','Detail Laporan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('petugas.laporan.index') }}">Laporan Saya</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')

<div class="card p-3 mb-3">
  <h6 class="fw-bold mb-2">{{ $laporan->jadwal->nama_kegiatan ?? '(Jadwal dihapus)' }}</h6>
  @if($laporan->jadwal)
  <small class="text-muted">
    {{ $laporan->jadwal->tanggal->format('d M Y') }}
    &bull; Shift {{ $laporan->jadwal->shift->nama ?? '-' }}
    &bull; {{ $laporan->jadwal->lokasi->nama ?? '-' }}
  </small>
  @endif
  <hr>
  <table class="table table-borderless mb-0 small">
    <tr>
      <td class="text-muted" style="width:35%">Kondisi</td>
      <td>
        <span class="sipko-badge {{ $laporan->kondisi==='kondusif'?'green':($laporan->kondisi==='tidak kondusif'?'red':'orange') }}">
          {{ $laporan->kondisi }}
        </span>
      </td>
    </tr>
    <tr><td class="text-muted">Jumlah Personil</td><td>{{ $laporan->jumlah_personil ?? '-' }}</td></tr>
    <tr><td class="text-muted">Waktu Laporan</td><td>{{ $laporan->waktu_laporan->format('d M Y, H:i') }}</td></tr>
    <tr>
      <td class="text-muted">Status</td>
      <td>
        <span class="sipko-badge {{ $laporan->status==='diterima'?'green':($laporan->status==='terlambat'?'orange':'blue') }}">
          {{ ucfirst($laporan->status) }}
        </span>
      </td>
    </tr>
  </table>
</div>

@if($laporan->catatan)
<div class="card p-3 mb-3">
  <h6 class="fw-bold mb-2">Isi Laporan</h6>
  <p class="mb-0">{{ $laporan->catatan }}</p>
</div>
@endif

@if($laporan->foto)
<div class="card p-3 mb-3">
  <h6 class="fw-bold mb-2">Foto Dokumentasi</h6>
  <a href="{{ asset('storage/'.$laporan->foto) }}" target="_blank">
    <img src="{{ asset('storage/'.$laporan->foto) }}"
         style="height:120px;border-radius:8px;object-fit:cover">
  </a>
</div>
@endif

@if($laporan->catatan_admin)
<div class="alert alert-info mt-3">
  <i class="bi bi-chat-square-text me-2"></i>
  <strong>Catatan Admin:</strong> {{ $laporan->catatan_admin }}
</div>
@endif

<a href="{{ route('petugas.laporan.index') }}" class="btn btn-outline-secondary btn-sm">
  <i class="bi bi-arrow-left me-1"></i>Kembali
</a>

@endsection
