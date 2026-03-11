@extends('layout.petugas')
@section('title','Laporan Saya')
@section('page-title','Laporan Kegiatan Saya')
@section('content')

@forelse($laporan as $l)
<div class="card mb-2" style="border-left:3px solid {{ $l->status==='diterima'?'#16a34a':($l->status==='terlambat'?'#d97706':'#2563eb') }}">
  <div class="card-body py-2 px-3">
    <div class="d-flex align-items-start justify-content-between gap-2">
      <div class="flex-grow-1">
        <div class="fw-bold" style="font-size:13px">
          {{ $l->jadwal->nama_kegiatan ?? '(Jadwal dihapus)' }}
        </div>
        <small class="text-muted">
          {{ $l->jadwal->jenisKegiatan->nama ?? '-' }}
          &bull;
          {{ $l->waktu_laporan->format('d M Y H:i') }}
        </small>
        <div class="mt-1 d-flex flex-wrap gap-1">
          <span class="sipko-badge {{ $l->kondisi==='kondusif'?'green':($l->kondisi==='tidak kondusif'?'red':'orange') }}">
            {{ $l->kondisi }}
          </span>
          <span class="sipko-badge {{ $l->status==='diterima'?'green':($l->status==='terlambat'?'orange':'blue') }}">
            {{ ucfirst($l->status) }}
          </span>
        </div>
      </div>
      <a href="{{ route('petugas.laporan.show', $l) }}"
         class="btn btn-sm btn-outline-primary flex-shrink-0" style="font-size:12px">
        <i class="bi bi-eye me-1"></i>Detail
      </a>
    </div>
  </div>
</div>
@empty
<div class="alert alert-light text-center text-muted py-4">
  <i class="bi bi-file-earmark-x fs-3 d-block mb-2"></i>
  Belum pernah mengirim laporan.
  <br>
  <a href="{{ route('petugas.jadwal.index') }}" class="btn btn-sm btn-petugas mt-2">
    Lihat Jadwal
  </a>
</div>
@endforelse

@if($laporan->hasPages())
<div class="mt-2">{{ $laporan->links() }}</div>
@endif

@endsection
