@extends('layout.admin')
@section('title','Detail Laporan')
@section('page-title','Detail Laporan Kegiatan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.laporan.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="row g-3">
  <div class="col-lg-7">
    <div class="card p-3">
      <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-text text-primary me-2"></i>Isi Laporan</h6>
      <div class="mb-3 p-3 rounded" style="background:#f8fafc;border-left:4px solid #2d6a4f"><p class="mb-0">{{ $laporan->isi_laporan }}</p></div>
      @if($laporan->foto && count($laporan->foto))
      <h6 class="fw-bold mb-2">Foto Dokumentasi</h6>
      <div class="d-flex flex-wrap gap-2">
        @foreach($laporan->foto as $f)
        <a href="{{ asset('storage/'.$f) }}" target="_blank"><img src="{{ asset('storage/'.$f) }}" alt="" class="rounded" style="height:100px;width:100px;object-fit:cover"></a>
        @endforeach
      </div>
      @endif
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card p-3 mb-3">
      <h6 class="fw-bold mb-3">Info Jadwal</h6>
      <table class="table table-borderless mb-0 small">
        <tr><td class="text-muted">Kegiatan</td><td class="fw-semibold">{{ $laporan->jadwal->nama_kegiatan }}</td></tr>
        <tr><td class="text-muted">Tanggal</td><td>{{ $laporan->jadwal->tanggal->format('d M Y') }}</td></tr>
        <tr><td class="text-muted">Shift</td><td>{{ $laporan->jadwal->shift->nama }}</td></tr>
        <tr><td class="text-muted">Lokasi</td><td>{{ $laporan->jadwal->lokasi->nama }}</td></tr>
        <tr><td class="text-muted">Pelapor</td><td>{{ $laporan->petugas->nama }}</td></tr>
        <tr><td class="text-muted">Kondisi</td><td><span class="sipko-badge {{ $laporan->kondisi==='kondusif'?'green':($laporan->kondisi==='tidak kondusif'?'red':'orange') }}">{{ $laporan->kondisi }}</span></td></tr>
        <tr><td class="text-muted">Pelanggaran</td><td>{{ $laporan->jumlah_pelanggaran }}</td></tr>
        <tr><td class="text-muted">Waktu</td><td>{{ $laporan->waktu_laporan->format('d/m/Y H:i') }}</td></tr>
      </table>
    </div>
    <div class="card p-3">
      <h6 class="fw-bold mb-3">Review Laporan</h6>
      <form method="POST" action="{{ route('admin.laporan.review',$laporan) }}">
        @csrf
        @method('PATCH')
        <div class="mb-2"><label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="diterima" {{ $laporan->status==='diterima'?'selected':'' }}>Diterima</option>
            <option value="terlambat" {{ $laporan->status==='terlambat'?'selected':'' }}>Terlambat</option>
            <option value="review" {{ $laporan->status==='review'?'selected':'' }}>Perlu Review</option>
          </select>
        </div>
        <div class="mb-3"><label class="form-label">Catatan Admin</label><textarea name="catatan_admin" class="form-control" rows="3">{{ $laporan->catatan_admin }}</textarea></div>
        <button type="submit" class="btn btn-sipko w-100">Simpan Review</button>
      </form>
    </div>
  </div>
</div>
@endsection
