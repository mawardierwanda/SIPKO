@extends('layout.admin')
@section('title','Kelola Jadwal')
@section('page-title','Jadwal Operasional')
@section('content')
<div class="card"><div class="card-body">
  <div class="d-flex flex-wrap gap-2 mb-3 mt-2 align-items-center">
    <form method="GET" class="d-flex flex-wrap gap-2">
      <input type="date" name="tanggal" class="form-control" style="width:150px" value="{{ request('tanggal') }}">
      <select name="jenis" class="form-select" style="width:150px"><option value="">Semua Jenis</option>@foreach($jenis_list as $j)<option value="{{ $j->id }}" {{ request('jenis')==$j->id?'selected':'' }}>{{ $j->nama }}</option>@endforeach</select>
      <select name="status" class="form-select" style="width:130px"><option value="">Semua Status</option><option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option><option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option><option value="dibatalkan" {{ request('status')=='dibatalkan'?'selected':'' }}>Dibatalkan</option></select>
      <button class="btn btn-outline-secondary"><i class="bi bi-funnel"></i> Filter</button>
      <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">Reset</a>
    </form>
    <div class="ms-auto"><a href="{{ route('admin.jadwal.create') }}" class="btn btn-sipko"><i class="bi bi-plus-circle"></i> Buat Jadwal</a></div>
  </div>
  <div class="table-responsive">
  <table class="table table-borderless table-hover">
    <thead><tr><th>Kegiatan</th><th>Tanggal</th><th>Shift</th><th>Lokasi</th><th>Tim</th><th>Laporan</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
    <tbody>
    @forelse($jadwal as $j)
    <tr>
      <td><div class="fw-semibold">{{ $j->nama_kegiatan }}</div><small class="text-muted">{{ $j->jenisKegiatan->nama }}</small></td>
      <td>{{ $j->tanggal->format('d M Y') }}</td>
      <td><span class="sipko-badge blue">{{ $j->shift->nama }}</span></td>
      <td>{{ $j->lokasi->nama }}</td>
      <td>{{ $j->satuan ?: '-' }}</td>
      <td>@if($j->sudahLaporan())<span class="sipko-badge green"><i class="bi bi-check-circle-fill"></i> Ada</span>@else<span class="sipko-badge red"><i class="bi bi-x-circle-fill"></i> Belum</span>@endif</td>
      <td><span class="sipko-badge {{ $j->status==='aktif'?'green':($j->status==='selesai'?'blue':'red') }}">{{ ucfirst($j->status) }}</span></td>
      <td>
        <div class="d-flex gap-1 justify-content-center">
          <a href="{{ route('admin.jadwal.show',$j) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
          <a href="{{ route('admin.jadwal.edit',$j) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
          <form method="POST" action="{{ route('admin.jadwal.destroy',$j) }}" onsubmit="return confirm('Hapus jadwal ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada jadwal.</td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
  {{ $jadwal->links() }}
</div></div>
@endsection
