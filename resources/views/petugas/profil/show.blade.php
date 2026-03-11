@extends('layout.petugas')
@section('title','Profil Saya')
@section('page-title','Profil Saya')

@section('content')
<div class="row g-3">
  <div class="col-lg-5">
    <div class="card p-3 text-center">
      <img src="{{ $petugas->foto_url }}" class="rounded-circle mx-auto mb-3" style="width:80px;height:80px;object-fit:cover">
      <h6 class="fw-bold">{{ $petugas->nama }}</h6>
      <p class="text-muted small">{{ $petugas->jabatan }} &bull; {{ $petugas->satuan }}<br>NIP: {{ $petugas->nip }}</p>
      <span class="sipko-badge {{ $petugas->status==='aktif'?'green':'red' }} mx-auto">{{ ucfirst($petugas->status) }}</span>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card p-3 mb-3">
      <h6 class="fw-bold mb-3">Edit Kontak</h6>
      <form method="POST" action="{{ route('petugas.profil.update') }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}">
        </div>

        <div class="mb-3">
          <label class="form-label">No HP</label>
          <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp',$petugas->no_hp) }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" rows="2">{{ old('alamat',$petugas->alamat) }}</textarea>
        </div>

        <button type="submit" class="btn btn-petugas">Simpan</button>
      </form>
    </div>

    <div class="card p-3">
      <h6 class="fw-bold mb-3">Ubah Password</h6>
      <form method="POST" action="{{ route('petugas.profil.password') }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
          <label class="form-label">Password Lama</label>
          <input type="password" name="current_password" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password" class="form-control">
          <small class="text-muted">Min. 8 karakter</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Konfirmasi</label>
          <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-warning">Ubah Password</button>
      </form>
    </div>
  </div>
</div>
@endsection
