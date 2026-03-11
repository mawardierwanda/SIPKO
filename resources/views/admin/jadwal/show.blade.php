@extends('layout.admin')
@section('title','Detail Jadwal')
@section('page-title','Detail Jadwal')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="row g-3">
  <div class="col-lg-6">
    <div class="card p-3">
      <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Jadwal</h6>
      <table class="table table-borderless mb-0">
        <tr><td class="text-muted" style="width:40%">Nama Kegiatan</td><td class="fw-semibold">{{ $jadwal->nama_kegiatan }}</td></tr>
        <tr><td class="text-muted">Jenis</td><td>{{ $jadwal->jenisKegiatan->nama }}</td></tr>
        <tr><td class="text-muted">Tanggal</td><td>{{ $jadwal->tanggal->format('l, d F Y') }}</td></tr>
        <tr><td class="text-muted">Shift</td><td><span class="sipko-badge blue">{{ $jadwal->shift->nama }} ({{ $jadwal->shift->jam_mulai }}–{{ $jadwal->shift->jam_selesai }})</span></td></tr>
        <tr><td class="text-muted">Lokasi</td><td>{{ $jadwal->lokasi->nama }}</td></tr>
        <tr><td class="text-muted">Tim</td><td>{{ $jadwal->satuan ?: '-' }}</td></tr>
        <tr><td class="text-muted">Status</td><td><span class="sipko-badge {{ $jadwal->status==='aktif'?'green':'gray' }}">{{ ucfirst($jadwal->status) }}</span></td></tr>
        <tr><td class="text-muted">Keterangan</td><td>{{ $jadwal->keterangan ?: '-' }}</td></tr>
      </table>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card p-3">
      <h6 class="fw-bold mb-3"><i class="bi bi-people text-success me-2"></i>Tim Bertugas ({{ $jadwal->penugasan->count() }} org)</h6>
      @forelse($jadwal->penugasan as $p)
      <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#f8fafc">
        <img src="{{ $p->petugas->foto_url }}" class="rounded-circle" style="width:34px;height:34px;object-fit:cover">
        <div class="flex-grow-1">
          <div class="fw-semibold small">{{ $p->petugas->nama }}</div>
          <small class="text-muted">{{ $p->petugas->jabatan }}</small>
        </div>
        <span class="sipko-badge {{ $p->peran==='koordinator'?'orange':'blue' }}">{{ ucfirst($p->peran) }}</span>
      </div>
      @empty
      <p class="text-muted small">Belum ada penugasan.</p>
      @endforelse
      <a href="{{ route('admin.penugasan.index') }}?jadwal_id={{ $jadwal->id }}" class="btn btn-outline-success btn-sm mt-2 w-100"><i class="bi bi-person-plus me-1"></i> Atur Penugasan</a>
    </div>
    @if($jadwal->sudahLaporan())
    <div class="card p-3 mt-3">
      <h6 class="fw-bold mb-3"><i class="bi bi-file-check text-success me-2"></i>Laporan</h6>
      <div class="d-flex gap-2 flex-wrap">
        <div><span class="text-muted small">Kondisi:</span><br><span class="sipko-badge {{ $jadwal->laporan->kondisi==='kondusif'?'green':($jadwal->laporan->kondisi==='tidak kondusif'?'red':'orange') }}">{{ $jadwal->laporan->kondisi }}</span></div>
        <div><span class="text-muted small">Pelanggaran:</span><br><strong>{{ $jadwal->laporan->jumlah_pelanggaran }}</strong></div>
        <div><span class="text-muted small">Pelapor:</span><br><strong>{{ $jadwal->laporan->petugas->nama }}</strong></div>
      </div>
      <a href="{{ route('admin.laporan.show',$jadwal->laporan) }}" class="btn btn-outline-primary btn-sm mt-2">Lihat Detail Laporan</a>
    </div>
    @endif
  </div>
</div>
@endsection
