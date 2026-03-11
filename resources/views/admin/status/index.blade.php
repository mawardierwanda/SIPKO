@extends('layout.admin')
@section('title','Status Jadwal')
@section('page-title','Status Jadwal Hari Ini')
@section('content')
<div class="alert alert-info d-flex gap-2 align-items-center">
  <i class="bi bi-calendar3 fs-5"></i>
  <strong>{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
</div>
@foreach($shifts as $shift)
@php $jadwalShift = $jadwal->where('shift_id',$shift->id); @endphp
<div class="card mb-3">
  <div class="card-header fw-bold" style="background:#f8fafc">
    <i class="bi bi-clock me-2 text-primary"></i>Shift {{ $shift->nama }} — {{ $shift->jam_mulai }}–{{ $shift->jam_selesai }}
    <span class="badge bg-primary ms-2">{{ $jadwalShift->count() }} jadwal</span>
  </div>
  <div class="card-body p-0">
    @if($jadwalShift->isEmpty())
    <p class="text-muted p-3 mb-0">Tidak ada jadwal pada shift ini.</p>
    @else
    <div class="table-responsive">
    <table class="table table-borderless table-hover mb-0">
      <thead><tr><th>Kegiatan</th><th>Lokasi</th><th>Tim</th><th>Petugas</th><th>Laporan</th><th>Status</th></tr></thead>
      <tbody>
      @foreach($jadwalShift as $j)
      <tr>
        <td><div class="fw-semibold">{{ $j->nama_kegiatan }}</div><small class="text-muted">{{ $j->jenisKegiatan->nama }}</small></td>
        <td>{{ $j->lokasi->nama }}</td>
        <td>{{ $j->satuan ?: '-' }}</td>
        <td>{{ $j->penugasan->count() }} org</td>
        <td>
          @if($j->sudahLaporan())
            <span class="sipko-badge green"><i class="bi bi-check-circle-fill"></i> {{ $j->laporan->kondisi }}</span>
          @else
            <span class="sipko-badge red"><i class="bi bi-x-circle-fill"></i> Belum</span>
          @endif
        </td>
        <td><span class="sipko-badge {{ $j->status==='aktif'?'green':'gray' }}">{{ ucfirst($j->status) }}</span></td>
      </tr>
      @endforeach
      </tbody>
    </table>
    </div>
    @endif
  </div>
</div>
@endforeach
@if($jadwal->isEmpty())
<div class="alert alert-light text-center text-muted py-4"><i class="bi bi-calendar-x fs-3 d-block mb-2"></i>Tidak ada jadwal hari ini.</div>
@endif
@endsection
