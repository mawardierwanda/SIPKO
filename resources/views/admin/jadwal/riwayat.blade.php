@extends('layout.admin')
@section('title','Riwayat Kegiatan')
@section('page-title','Riwayat Kegiatan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">SIPKO</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
<li class="breadcrumb-item active">Riwayat</li>
@endsection

@section('content')

{{-- FILTER BAR --}}
<div class="card mb-3">
  <div class="card-body py-2 px-3">
    <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
      <input type="date" name="dari" class="form-control form-control-sm" style="width:140px" value="{{ request('dari') }}">
      <input type="date" name="sampai" class="form-control form-control-sm" style="width:140px" value="{{ request('sampai') }}">
      <select name="status" class="form-select form-select-sm" style="width:135px">
        <option value="">Semua Status</option>
        <option value="selesai"    {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
        <option value="dibatalkan" {{ request('status')=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
        <option value="aktif"      {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
        <option value="dihapus"    {{ request('status')=='dihapus'?'selected':'' }}>Dihapus</option>
      </select>
      <select name="jenis" class="form-select form-select-sm" style="width:150px">
        <option value="">Semua Jenis</option>
        @foreach($jenis_list as $jk)
        <option value="{{ $jk->id }}" {{ request('jenis')==$jk->id?'selected':'' }}>{{ $jk->nama }}</option>
        @endforeach
      </select>
      <input type="text" name="cari" class="form-control form-control-sm" style="width:155px"
             placeholder="Cari kegiatan..." value="{{ request('cari') }}">
      <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
      <a href="{{ route('admin.jadwal.riwayat') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
      <div class="ms-auto">
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
      </div>
    </form>
  </div>
</div>

{{-- TABEL --}}
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Kegiatan</th>
            <th>Tanggal</th>
            <th>Shift</th>
            <th>Lokasi</th>
            <th>Tim</th>
            <th>Laporan</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($jadwal as $j)
        @php
          $deleted = method_exists($j,'trashed') && $j->trashed();
          $koordinator = $j->penugasan->firstWhere('peran','koordinator');
          $totalOrg    = $j->penugasan->count();
        @endphp
        <tr class="{{ $deleted ? 'table-light' : '' }}">

          {{-- Kegiatan --}}
          <td>
            <div class="fw-semibold d-flex align-items-center gap-1">
              {{ $j->nama_kegiatan }}
              @if($deleted)
              <span class="sipko-badge gray" style="font-size:10px"><i class="bi bi-archive-fill"></i> Dihapus</span>
              @endif
            </div>
            <small class="text-muted">{{ $j->jenisKegiatan->nama ?? '-' }}</small>
          </td>

          {{-- Tanggal --}}
          <td><small>{{ $j->tanggal->format('d M Y') }}</small></td>

          {{-- Shift --}}
          <td><span class="sipko-badge blue">{{ $j->shift->nama ?? '-' }}</span></td>

          {{-- Lokasi --}}
          <td><small>{{ $j->lokasi->nama ?? '-' }}</small></td>

          {{-- Tim --}}
          <td>
            @if($totalOrg)
              <div class="fw-semibold small">
                @if($koordinator)
                  👑 {{ explode(' ',$koordinator->petugas->nama)[0] }}
                @endif
                @if($totalOrg > 1)
                  <span class="text-muted fw-normal">+{{ $totalOrg - 1 }}</span>
                @endif
              </div>
              @if($j->satuan)
              <small class="text-muted">Tim {{ $j->satuan }}</small>
              @endif
            @else
              <span class="text-muted fst-italic small">—</span>
            @endif
          </td>

          {{-- Laporan --}}
          <td>
            @if($j->sudahLaporan())
              <span class="sipko-badge green"><i class="bi bi-check-circle-fill"></i> Ada</span>
            @else
              <span class="sipko-badge red"><i class="bi bi-x-circle-fill"></i> Belum</span>
            @endif
          </td>

          {{-- Status --}}
          <td>
            @php
              $sc = match($j->status) {
                'selesai'    => 'green',
                'dibatalkan' => 'red',
                'aktif'      => 'blue',
                default      => 'gray',
              };
            @endphp
            <span class="sipko-badge {{ $sc }}">{{ ucfirst($j->status) }}</span>
          </td>

          {{-- Aksi --}}
          <td class="text-center">
            <div class="d-flex gap-1 justify-content-center">
              @if($deleted)
                {{-- Pulihkan --}}
                <form method="POST" action="{{ route('admin.jadwal.restore', $j->id) }}">
                  @csrf
                  <button class="btn btn-sm btn-outline-success"
                          style="width:30px;height:30px;padding:0;border-radius:6px" title="Pulihkan">
                    <i class="bi bi-arrow-counterclockwise" style="font-size:11px"></i>
                  </button>
                </form>
                {{-- Hapus Permanen --}}
                <form method="POST" action="{{ route('admin.jadwal.force-delete', $j->id) }}"
                      onsubmit="return confirm('Hapus permanen? Data tidak bisa dipulihkan!')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"
                          style="width:30px;height:30px;padding:0;border-radius:6px" title="Hapus Permanen">
                    <i class="bi bi-trash" style="font-size:11px"></i>
                  </button>
                </form>
              @else
                {{-- Detail --}}
                <a href="{{ route('admin.jadwal.show', $j) }}"
                   class="btn btn-sm btn-outline-primary"
                   style="width:30px;height:30px;padding:0;border-radius:6px" title="Detail">
                  <i class="bi bi-eye" style="font-size:11px"></i>
                </a>
                {{-- Edit --}}
                <a href="{{ route('admin.jadwal.edit', $j) }}"
                   class="btn btn-sm btn-outline-secondary"
                   style="width:30px;height:30px;padding:0;border-radius:6px" title="Edit">
                  <i class="bi bi-pencil" style="font-size:11px"></i>
                </a>
                {{-- Hapus --}}
                <form method="POST" action="{{ route('admin.jadwal.destroy', $j) }}"
                      onsubmit="return confirm('Jadwal akan dipindah ke riwayat. Lanjutkan?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"
                          style="width:30px;height:30px;padding:0;border-radius:6px" title="Hapus">
                    <i class="bi bi-trash" style="font-size:11px"></i>
                  </button>
                </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted py-5">
            <i class="bi bi-journal-x fs-1 d-block mb-2 opacity-25"></i>
            Tidak ada riwayat kegiatan.
          </td>
        </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    @if($jadwal->hasPages())
    <div class="p-3 border-top">{{ $jadwal->links() }}</div>
    @endif
  </div>
</div>
@endsection
