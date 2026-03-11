@extends('layout.admin')
@section('title','Rekapitulasi')
@section('page-title','Rekapitulasi Kegiatan')
@section('content')
<div class="card mb-3 p-3">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3"><label class="form-label">Dari Tanggal</label><input type="date" name="dari" class="form-control" value="{{ $dari }}"></div>
    <div class="col-md-3"><label class="form-label">Sampai Tanggal</label><input type="date" name="sampai" class="form-control" value="{{ $sampai }}"></div>
    <div class="col-md-6 d-flex gap-2">
      <button class="btn btn-sipko"><i class="bi bi-search me-1"></i> Tampilkan</button>
      <a href="{{ route('admin.rekap.pdf') }}?dari={{ $dari }}&sampai={{ $sampai }}" target="_blank" class="btn btn-outline-danger"><i class="bi bi-file-pdf me-1"></i> PDF</a>
      <a href="{{ route('admin.rekap.excel') }}?dari={{ $dari }}&sampai={{ $sampai }}" class="btn btn-outline-success"><i class="bi bi-file-excel me-1"></i> Excel</a>
    </div>
  </form>
</div>
<div class="row g-3 mb-3">
  <div class="col-4"><div class="card p-3 text-center"><div class="text-muted small">Total Jadwal</div><div class="fw-bold fs-3">{{ $total_jadwal }}</div></div></div>
  <div class="col-4"><div class="card p-3 text-center"><div class="text-muted small">Sudah Laporan</div><div class="fw-bold fs-3 text-success">{{ $sudah_laporan }}</div></div></div>
  <div class="col-4"><div class="card p-3 text-center"><div class="text-muted small">Belum Laporan</div><div class="fw-bold fs-3 text-danger">{{ $belum_laporan }}</div></div></div>
</div>
<div class="card"><div class="card-body">
  <div class="table-responsive">
  <table class="table table-borderless table-hover">
    <thead><tr><th>No</th><th>Tanggal</th><th>Kegiatan</th><th>Shift</th><th>Lokasi</th><th>Petugas</th><th>Laporan</th><th>Kondisi</th></tr></thead>
    <tbody>
    @foreach($jadwal as $i=>$j)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $j->tanggal->format('d/m/Y') }}</td>
      <td><div class="fw-semibold">{{ $j->nama_kegiatan }}</div><small class="text-muted">{{ $j->jenisKegiatan->nama }}</small></td>
      <td><span class="sipko-badge blue">{{ $j->shift->nama }}</span></td>
      <td>{{ $j->lokasi->nama }}</td>
      <td>{{ $j->penugasan->count() }} org</td>
      <td>@if($j->sudahLaporan())<span class="sipko-badge green">Ada</span>@else<span class="sipko-badge red">Belum</span>@endif</td>
      <td>{{ $j->laporan?->kondisi ?? '-' }}</td>
    </tr>
    @endforeach
    </tbody>
  </table>
  </div>
</div></div>
@endsection
