@extends('layout.admin')
@section('title','Manajemen User')
@section('page-title','Manajemen Akun Pengguna')
@section('content')

{{-- Form Tambah --}}
<div class="card mb-3">
  <div class="card-body">
    <h6 class="fw-bold mb-3"><i class="bi bi-person-plus me-2 text-success"></i>Tambah Akun Baru</h6>
    <form method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                 placeholder="Nama lengkap" value="{{ old('name') }}">
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-2">
          <label class="form-label">Username <span class="text-danger">*</span></label>
          <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                 placeholder="Username" value="{{ old('username') }}">
          @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                 placeholder="email@domain.com" value="{{ old('email') }}">
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-2">
          <label class="form-label">Password <span class="text-danger">*</span></label>
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                 placeholder="Min. 8 karakter">
          @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-2">
          <label class="form-label">Peran <span class="text-danger">*</span></label>
          <select name="role" class="form-select">
            <option value="petugas" {{ old('role','petugas')=='petugas'?'selected':'' }}>Petugas</option>
            <option value="admin"   {{ old('role')=='admin'?'selected':'' }}>Admin</option>
          </select>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-sipko btn-sm px-4">
          <i class="bi bi-plus-circle me-1"></i> Tambah Akun
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Tabel User --}}
<div class="card">
  <div class="card-body">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h6 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Daftar Pengguna</h6>
      <span class="text-muted small">Total: {{ $users->total() }} akun</span>
    </div>
    <div class="table-responsive">
      <table class="table table-borderless table-hover align-middle">
        <thead>
          <tr>
            <th style="width:40px">#</th>
            <th style="min-width:180px">Nama</th>
            <th style="min-width:120px">Username</th>
            <th style="min-width:200px">Email</th>
            <th style="width:110px">Peran</th>
            <th style="width:100px">Status</th>
            <th style="width:120px" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($users as $i => $u)
        <tr>
          <td class="text-muted small">{{ $users->firstItem() + $i }}</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                   style="width:36px;height:36px;font-size:12px;background:{{ $u->role==='admin'?'#d97706':'#2563eb' }}">
                {{ strtoupper(substr($u->name,0,2)) }}
              </div>
              <div>
                <div class="fw-semibold" style="font-size:13px">{{ $u->name }}</div>
                <small class="text-muted">{{ $u->petugas->jabatan ?? '—' }}</small>
              </div>
            </div>
          </td>
          <td>
            <code style="font-size:12px;background:#f1f5f9;padding:2px 8px;border-radius:5px;color:#374151">
              {{ $u->username }}
            </code>
          </td>
          <td style="font-size:13px;color:#475569">{{ $u->email }}</td>
          <td>
            @if($u->role === 'admin')
              <span class="sipko-badge orange"><i class="bi bi-shield-fill"></i> Admin</span>
            @else
              <span class="sipko-badge blue"><i class="bi bi-person-fill"></i> Petugas</span>
            @endif
          </td>
          <td>
            @if($u->is_active)
              <span class="sipko-badge green"><i class="bi bi-check-circle-fill"></i> Aktif</span>
            @else
              <span class="sipko-badge red"><i class="bi bi-x-circle-fill"></i> Nonaktif</span>
            @endif
          </td>
          <td>
            <div class="d-flex gap-1 justify-content-center">
              {{-- Toggle Aktif --}}
              <form method="POST" action="{{ route('admin.users.toggle',$u) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="btn btn-sm btn-outline-{{ $u->is_active?'warning':'success' }}"
                        title="{{ $u->is_active?'Nonaktifkan':'Aktifkan' }}">
                  <i class="bi bi-{{ $u->is_active?'pause-circle':'play-circle' }}"></i>
                </button>
              </form>
              {{-- Reset Password --}}
              <form method="POST" action="{{ route('admin.users.reset-password',$u) }}"
                    onsubmit="return confirm('Reset password akun {{ addslashes($u->name) }}?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Reset Password">
                  <i class="bi bi-key"></i>
                </button>
              </form>
              {{-- Hapus --}}
              @if($u->id !== auth()->id())
              <form method="POST" action="{{ route('admin.users.destroy',$u) }}"
                    onsubmit="return confirm('Hapus akun {{ addslashes($u->name) }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
              @else
              <button class="btn btn-sm btn-outline-secondary" disabled title="Akun Anda sendiri">
                <i class="bi bi-lock"></i>
              </button>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-people fs-3 d-block mb-2"></i>Belum ada data user.
          </td>
        </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    {{ $users->links() }}
  </div>
</div>
@endsection
