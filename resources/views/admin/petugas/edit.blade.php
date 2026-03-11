@extends('layout.admin')
@section('title','Edit Petugas')
@section('page-title','Edit Data Petugas')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.petugas.index') }}">Data Petugas</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.petugas.update',$petugas) }}" enctype="multipart/form-data">
@csrf
@method('PUT')
<div class="row g-3">
  <div class="col-md-6"><label class="form-label">NIP *</label><input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip',$petugas->nip) }}">@error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="col-md-6"><label class="form-label">Nama Lengkap *</label><input type="text" name="nama" class="form-control" value="{{ old('nama',$petugas->nama) }}"></div>
  <div class="col-md-6"><label class="form-label">Jabatan *</label><input type="text" name="jabatan" class="form-control" value="{{ old('jabatan',$petugas->jabatan) }}"></div>
  <div class="col-md-6"><label class="form-label">Pangkat</label><input type="text" name="pangkat" class="form-control" value="{{ old('pangkat',$petugas->pangkat) }}"></div>
  <div class="col-md-4">
  <label class="form-label">Tim / Satuan</label>
  <input type="text" name="satuan" class="form-control"
         placeholder="Tim Alpha, "
         value="{{ old('satuan', $petugas->satuan) }}">
</div>
  <div class="col-md-4"><label class="form-label">No HP</label><input type="text" name="no_hp" class="form-control" value="{{ old('no_hp',$petugas->no_hp) }}"></div>
  <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="aktif" {{ $petugas->status==='aktif'?'selected':'' }}>Aktif</option><option value="nonaktif" {{ $petugas->status==='nonaktif'?'selected':'' }}>Nonaktif</option></select></div>
  <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ old('alamat',$petugas->alamat) }}</textarea></div>
  <div class="col-md-6">
    <label class="form-label">Foto Baru</label><input type="file" name="foto" class="form-control" accept="image/*">
    @if($petugas->foto)<div class="mt-2"><img src="{{ $petugas->foto_url }}" alt="" class="rounded" style="height:60px"></div>@endif
  </div>
  <div class="col-12 d-flex gap-2">
    <button type="submit" class="btn btn-sipko"><i class="bi bi-check-circle me-1"></i> Update</button>
    <a href="{{ route('admin.petugas.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</div>
</form>
</div></div>
@endsection
