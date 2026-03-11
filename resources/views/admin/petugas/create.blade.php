@extends('layout.admin')
@section('title','Tambah Petugas')
@section('page-title','Tambah Petugas Baru')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.petugas.index') }}">Data Petugas</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.petugas.store') }}" enctype="multipart/form-data">
@csrf
<div class="row g-3">
  <div class="col-md-6"><label class="form-label">NIP *</label><input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}">@error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="col-md-6"><label class="form-label">Nama Lengkap *</label><input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="col-md-6"><label class="form-label">Jabatan *</label><input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}"></div>
  <div class="col-md-6"><label class="form-label">Pangkat</label><input type="text" name="pangkat" class="form-control" value="{{ old('pangkat') }}"></div>
 <div class="col-md-4"><label class="form-label">Tim / Satuan</label><input type="text" name="satuan" class="form-control" placeholder="Tim Alpha, Tim Bravo..." value="{{ old('satuan') }}"></div>
  <div class="col-md-4"><label class="form-label">No HP</label><input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}"></div>
  <div class="col-md-4"><label class="form-label">Status *</label><select name="status" class="form-select"><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select></div>
  <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea></div>
  <div class="col-md-6"><label class="form-label">Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
  <div class="col-12"><hr><h6 class="fw-bold text-primary">Akun Login</h6></div>
  <div class="col-md-4"><label class="form-label">Username *</label><input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">@error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="col-md-4"><label class="form-label">Email *</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="col-md-4"><label class="form-label">Password *</label><input type="password" name="password" class="form-control"><small class="text-muted">Min. 8 karakter</small></div>
  <div class="col-12 d-flex gap-2">
    <button type="submit" class="btn btn-sipko"><i class="bi bi-check-circle me-1"></i> Simpan</button>
    <a href="{{ route('admin.petugas.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</div>
</form>
</div></div>
@endsection
