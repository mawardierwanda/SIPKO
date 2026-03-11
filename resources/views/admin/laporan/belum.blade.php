@extends('layout.admin')
@section('title','Belum Laporan')
@section('page-title','Jadwal Belum Ada Laporan')
@section('content')
<div class="alert alert-warning d-flex gap-2"><i class="bi bi-exclamation-triangle-fill fs-5"></i><div><strong>{{ $jadwal->count() }} jadwal</strong> belum memiliki laporan dan sudah melewati tanggal pelaksanaan.</div></div>
<div class="card"><div class="card-body">
  <div class="table-responsive">
  <table class="table table-borderless table-hover">
    <thead><tr><th>Kegiatan</th><th>Tanggal</th><th>Shift</th><th>Lokasi</th><th>Koordinator</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($jadwal as $j)
    <tr>
      <td><div class="fw-semibold">{{ $j->nama_kegiatan }}</div><small class="text-muted">{{ $j->jenisKegiatan->nama }}</small></td>
      <td>{{ $j->tanggal->format('d M Y') }}</td>
      <td><span class="sipko-badge blue">{{ $j->shift->nama }}</span></td>
      <td>{{ $j->lokasi->nama }}</td>
      <td>{{ $j->penugasan->where('peran','koordinator')->first()?->petugas->nama ?? '-' }}</td>
      <td><a href="{{ route('admin.jadwal.show',$j) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
    </tr>
    @empty
    <tr><td colspan="6" class="text-center text-success py-4"><i class="bi bi-check-circle-fill me-2"></i>Semua jadwal sudah memiliki laporan!</td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
</div></div>
@endsection
