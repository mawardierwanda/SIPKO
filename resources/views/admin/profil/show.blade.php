@extends('layout.admin')
@section('title','Profil Admin')
@section('page-title','Profil Administrator')
@section('content')
<div class="row g-3 align-items-stretch">
  <div class="col-lg-6">
    <div class="card p-3 h-100">
      <h6 class="fw-bold mb-3"><i class="bi bi-person-circle me-2 text-primary"></i>Edit Profil</h6>
      <form method="POST" action="{{ route('admin.profil.update') }}" class="d-flex flex-column h-100">
        @csrf
        @method('PATCH')
        <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}"></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}"></div>
        <div class="mb-3"><label class="form-label">Username</label><input type="text" class="form-control bg-light" value="{{ $user->username }}" readonly></div>
        <div class="mb-3"><label class="form-label">Peran</label><input type="text" class="form-control bg-light" value="{{ ucfirst($user->role) }}" readonly></div>
        <div class="mt-auto pt-2"><button type="submit" class="btn btn-sipko w-100">Simpan Perubahan</button></div>
      </form>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card p-3 h-100">
      <h6 class="fw-bold mb-3"><i class="bi bi-lock me-2 text-warning"></i>Ubah Password</h6>
      <form method="POST" action="{{ route('admin.profil.password') }}" class="d-flex flex-column h-100">
        @csrf
        @method('PATCH')

        <div class="mb-3">
          <label class="form-label">Password Saat Ini</label>
          <div class="position-relative">
            <input type="password" name="current_password" class="form-control pe-5" id="pwd_current">
            <i class="bi bi-eye pwd-toggle" onclick="togglePwd('pwd_current',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#94a3b8;font-size:16px"></i>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Password Baru</label>
          <div class="position-relative">
            <input type="password" name="password" class="form-control pe-5" id="pwd_new">
            <i class="bi bi-eye pwd-toggle" onclick="togglePwd('pwd_new',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#94a3b8;font-size:16px"></i>
          </div>
          <small class="text-muted">Minimal 8 karakter</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Konfirmasi Password Baru</label>
          <div class="position-relative">
            <input type="password" name="password_confirmation" class="form-control pe-5" id="pwd_confirm">
            <i class="bi bi-eye pwd-toggle" onclick="togglePwd('pwd_confirm',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#94a3b8;font-size:16px"></i>
          </div>
        </div>

        <div class="mb-3 p-3 rounded" style="background:#fef3c7;border:1px solid #fde68a;font-size:12px;color:#92400e">
          <i class="bi bi-exclamation-triangle-fill me-1"></i>
          Setelah mengubah password, Anda akan diminta login ulang.
        </div>
        <div class="mt-auto pt-2"><button type="submit" class="btn btn-warning w-100 fw-bold">Ubah Password</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function togglePwd(inputId, icon) {
  const input = document.getElementById(inputId);
  input.type  = input.type === 'password' ? 'text' : 'password';
  icon.className = input.type === 'password' ? 'bi bi-eye pwd-toggle' : 'bi bi-eye-slash pwd-toggle';
}
</script>
@endpush
@endsection
