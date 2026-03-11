@extends('layout.admin')
@section('title','Buat Jadwal')
@section('page-title','Buat Jadwal Baru')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
<li class="breadcrumb-item active">Buat Baru</li>
@endsection
@section('content')
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.jadwal.store') }}">@csrf
<div class="row g-3">
  <div class="col-md-8"><label class="form-label">Nama Kegiatan *</label><input type="text" name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror" value="{{ old('nama_kegiatan') }}" placeholder="Patroli Malam Wilayah Barat">@error('nama_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="col-md-4"><label class="form-label">Jenis Kegiatan *</label><select name="jenis_kegiatan_id" class="form-select @error('jenis_kegiatan_id') is-invalid @enderror"><option value="">-- Pilih Jenis --</option>@foreach($jenis_list as $j)<option value="{{ $j->id }}" {{ old('jenis_kegiatan_id')==$j->id?'selected':'' }}>{{ $j->nama }}</option>@endforeach</select>@error('jenis_kegiatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="col-md-4"><label class="form-label">Tanggal *</label><input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal',today()->toDateString()) }}">@error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="col-md-4"><label class="form-label">Shift *</label><select name="shift_id" class="form-select @error('shift_id') is-invalid @enderror"><option value="">-- Pilih Shift --</option>@foreach($shifts as $s)<option value="{{ $s->id }}" {{ old('shift_id')==$s->id?'selected':'' }}>{{ $s->nama }} ({{ $s->jam_mulai }}–{{ $s->jam_selesai }})</option>@endforeach</select>@error('shift_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="col-md-4"><label class="form-label">Lokasi *</label><select name="lokasi_id" class="form-select @error('lokasi_id') is-invalid @enderror"><option value="">-- Pilih Lokasi --</option>@foreach($lokasi as $l)<option value="{{ $l->id }}" {{ old('lokasi_id')==$l->id?'selected':'' }}>{{ $l->nama }}</option>@endforeach</select>@error('lokasi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
 <div class="col-md-4"><label class="form-label">Tim / Satuan</label><input type="text" name="satuan" class="form-control" placeholder="Tim Alpha, Tim Bravo..." value="{{ old('satuan') }}"></div>
  <div class="col-12 d-flex gap-2">
    <button type="submit" class="btn btn-sipko"><i class="bi bi-calendar-plus me-1"></i> Buat Jadwal</button>
    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</div>
</form>
</div></div>
@endsection
