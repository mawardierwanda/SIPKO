@extends('layout.admin')
@section('title','Edit Jadwal')
@section('page-title','Edit Jadwal')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card"><div class="card-body">
  <form method="POST" action="{{ route('admin.jadwal.update',$jadwal) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-md-8">
        <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
        <input type="text" name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror"
               value="{{ old('nama_kegiatan',$jadwal->nama_kegiatan) }}">
        @error('nama_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
        <select name="jenis_kegiatan_id" class="form-select @error('jenis_kegiatan_id') is-invalid @enderror">
          <option value="">-- Pilih --</option>
          @foreach($jenis_list as $j)
          <option value="{{ $j->id }}" {{ $jadwal->jenis_kegiatan_id==$j->id?'selected':'' }}>{{ $j->nama }}</option>
          @endforeach
        </select>
        @error('jenis_kegiatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
               value="{{ old('tanggal',$jadwal->tanggal->format('Y-m-d')) }}">
        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label">Shift <span class="text-danger">*</span></label>
        <select name="shift_id" class="form-select @error('shift_id') is-invalid @enderror">
          <option value="">-- Pilih --</option>
          @foreach($shifts as $s)
          <option value="{{ $s->id }}" {{ $jadwal->shift_id==$s->id?'selected':'' }}>{{ $s->nama }}</option>
          @endforeach
        </select>
        @error('shift_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
        <select name="lokasi_id" class="form-select @error('lokasi_id') is-invalid @enderror">
          <option value="">-- Pilih --</option>
          @foreach($lokasi as $l)
          <option value="{{ $l->id }}" {{ $jadwal->lokasi_id==$l->id?'selected':'' }}>{{ $l->nama }}</option>
          @endforeach
        </select>
        @error('lokasi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="aktif"      {{ $jadwal->status==='aktif'?'selected':'' }}>Aktif</option>
          <option value="selesai"    {{ $jadwal->status==='selesai'?'selected':'' }}>Selesai</option>
          <option value="dibatalkan" {{ $jadwal->status==='dibatalkan'?'selected':'' }}>Dibatalkan</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Tim / Satuan</label>
        <input type="text" name="satuan" class="form-control"
               placeholder="Contoh: Tim Alpha, Tim Bravo..."
               value="{{ old('satuan',$jadwal->satuan) }}">
      </div>
      <div class="col-12">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan',$jadwal->keterangan) }}</textarea>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-sipko">
          <i class="bi bi-check-circle me-1"></i> Update Jadwal
        </button>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">Batal</a>
        {{-- <a href="{{ route('admin.titik-razia.index',$jadwal) }}" class="btn btn-outline-success ms-auto">
          <i class="bi bi-geo-alt me-1"></i> Kelola Titik Razia
        </a> --}}
      </div>
    </div>
  </form>
</div></div>
@endsection
