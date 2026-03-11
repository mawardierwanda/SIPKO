@extends('layout.admin')
@section('title','Manajemen Shift')
@section('page-title','Manajemen Shift')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">SIPKO</a></li>
<li class="breadcrumb-item"><a href="#">Master Data</a></li>
<li class="breadcrumb-item active">Shift</li>
@endsection
@section('content')

{{-- TOMBOL TAMBAH --}}
<div class="d-flex justify-content-end mb-4">
  <button class="btn btn-sipko px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle me-1"></i> Tambah Shift
  </button>
</div>

{{-- CARDS --}}
<div class="row g-3">
  @forelse($shifts as $s)
  @php
    $menitMulai   = \Carbon\Carbon::parse($s->jam_mulai)->hour * 60 + \Carbon\Carbon::parse($s->jam_mulai)->minute;
    $menitSelesai = \Carbon\Carbon::parse($s->jam_selesai)->hour * 60 + \Carbon\Carbon::parse($s->jam_selesai)->minute;
    $selisih      = $menitSelesai > $menitMulai ? $menitSelesai - $menitMulai : (1440 - $menitMulai + $menitSelesai);
    $durasi       = $selisih >= 60 ? (int)round($selisih/60).' jam' : $selisih.' menit';

    $namaLower = strtolower($s->nama);
    $cfg = str_contains($namaLower,'pagi')  ? ['emoji'=>'🌅','border'=>'#3a86ff','badge'=>'#dbeafe','badgeTxt'=>'#1d4ed8'] :
          (str_contains($namaLower,'siang') ? ['emoji'=>'☀️','border'=>'#f59e0b','badge'=>'#fef3c7','badgeTxt'=>'#92400e'] :
          (str_contains($namaLower,'malam') ? ['emoji'=>'🌙','border'=>'#7c3aed','badge'=>'#ede9fe','badgeTxt'=>'#5b21b6'] :
                                              ['emoji'=>'⏰','border'=>'#16a34a','badge'=>'#dcfce7','badgeTxt'=>'#166534']));
  @endphp
  <div class="col-sm-6 col-lg-4">
    <div class="card h-100" style="border-top:3px solid {{ $cfg['border'] }};border-radius:12px">
      <div class="card-body p-4">
        {{-- Icon --}}
        <div style="font-size:32px;line-height:1;margin-bottom:12px">{{ $cfg['emoji'] }}</div>

        {{-- Nama --}}
        <div class="fw-bold" style="font-size:18px;color:var(--text-primary)">{{ $s->nama }}</div>

        {{-- Jam --}}
        <div class="text-muted mt-1 mb-3" style="font-size:14px">
          {{ \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') }}
          &mdash;
          {{ \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') }}
          WIB &bull; {{ $durasi }}
        </div>

        {{-- Badge petugas / jadwal --}}
        <div class="d-flex gap-2 mb-4 flex-wrap">
          <span style="background:{{ $cfg['badge'] }};color:{{ $cfg['badgeTxt'] }};padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">
            {{ $s->jadwal_count ?? 0 }} Jadwal
          </span>
          @if($s->keterangan)
          <span class="sipko-badge gray">{{ $s->keterangan }}</span>
          @endif
        </div>

        {{-- Aksi --}}
        <div class="d-flex gap-2">
          <button class="btn btn-sm btn-outline-secondary"
                  style="width:36px;height:36px;padding:0;border-radius:8px"
                  data-bs-toggle="modal"
                  data-bs-target="#editShift{{ $s->id }}"
                  title="Edit">
            <i class="bi bi-pencil-square"></i>
          </button>
          <form method="POST" action="{{ route('admin.shifts.destroy',$s) }}" class="d-inline"
                onsubmit="return confirm('Hapus shift {{ $s->nama }}?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"
                    style="width:36px;height:36px;padding:0;border-radius:8px"
                    title="Hapus">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="card p-5 text-center">
      <i class="bi bi-clock fs-1 text-muted d-block mb-3"></i>
      <h6 class="text-muted">Belum ada shift</h6>
      <div class="mt-2">
        <button class="btn btn-sipko" data-bs-toggle="modal" data-bs-target="#modalTambah">
          <i class="bi bi-plus-circle me-1"></i> Tambah Shift Pertama
        </button>
      </div>
    </div>
  </div>
  @endforelse
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Shift Baru</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.shifts.store') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Shift <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" placeholder="Pagi / Siang / Malam" value="{{ old('nama') }}" required>
          </div>
          <div class="row g-2 mb-3">
            <div class="col">
              <label class="form-label">Jam Mulai</label>
              <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}">
            </div>
            <div class="col">
              <label class="form-label">Jam Selesai</label>
              <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}">
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Opsional" value="{{ old('keterangan') }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sipko"><i class="bi bi-check-circle me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL EDIT --}}
@foreach($shifts as $s)
<div class="modal fade" id="editShift{{ $s->id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i>Edit: {{ $s->nama }}</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.shifts.update',$s) }}">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Shift <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" value="{{ $s->nama }}" required>
          </div>
          <div class="row g-2 mb-3">
            <div class="col">
              <label class="form-label">Jam Mulai</label>
              <input type="time" name="jam_mulai" class="form-control" value="{{ \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') }}">
            </div>
            <div class="col">
              <label class="form-label">Jam Selesai</label>
              <input type="time" name="jam_selesai" class="form-control" value="{{ \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') }}">
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control" value="{{ $s->keterangan }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sipko"><i class="bi bi-check-circle me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endsection
