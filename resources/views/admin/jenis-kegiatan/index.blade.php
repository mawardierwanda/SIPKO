@extends('layout.admin')
@section('title','Jenis Kegiatan')
@section('page-title','Jenis Kegiatan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">SIPKO</a></li>
<li class="breadcrumb-item"><a href="#">Master Data</a></li>
<li class="breadcrumb-item active">Jenis Kegiatan</li>
@endsection
@section('content')

{{-- HEADER BAR --}}
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
        <div class="d-flex justify-content-end mb">
    <button class="btn btn-sipko px" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me"></i> Tambah Kegiatan</button>
</div>
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:40px">#</th>
            <th style="width:80px">Kode</th>
            <th>Nama Kegiatan</th>
            <th>Deskripsi</th>
            <th>Wajib Laporan</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($jenis as $j)
        <tr>
          <td class="text-muted">{{ $loop->iteration }}</td>
          <td><span class="fw-semibold text-muted">{{ $j->kode ?? '-' }}</span></td>
          <td class="fw-bold">{{ $j->nama }}</td>
          <td><small class="text-muted">{{ $j->deskripsi ?? '-' }}</small></td>
          <td>
            @if($j->aktif)
              <span style="color:#16a34a;font-weight:600;font-size:13px">Wajib</span>
            @else
              <span style="color:#f59e0b;font-weight:600;font-size:13px">Opsional</span>
            @endif
          </td>
          <td class="text-center">
            <div class="d-flex gap-1 justify-content-center">
              <button class="btn btn-sm btn-outline-secondary"
                      style="width:30px;height:30px;padding:0;border-radius:6px"
                      data-bs-toggle="modal" data-bs-target="#editJenis{{ $j->id }}" title="Edit">
                <i class="bi bi-pencil-square" style="font-size:11px"></i>
              </button>
              <form method="POST" action="{{ route('admin.jenis-kegiatan.destroy',$j) }}"
                    class="d-inline" onsubmit="return confirm('Hapus jenis kegiatan {{ $j->nama }}?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"
                        style="width:30px;height:30px;padding:0;border-radius:6px" title="Hapus">
                  <i class="bi bi-trash" style="font-size:11px"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted py-5">
            <i class="bi bi-clipboard fs-1 d-block mb-2 opacity-25"></i>
            Belum ada kegiatan.
          </td>
        </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
    <div class="modal-content">
      <div class="modal-header py-2 border-bottom">
        <h6 class="modal-title fw-bold mb-0">
          <i class="bi bi-plus-circle me-2 text-success"></i>Tambah Kegiatan
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.jenis-kegiatan.store') }}">
        @csrf
        <div class="modal-body px-3 py-2">
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Nama Kegiatan <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-sm" value="{{ old('nama') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Kode</label>
            <input type="text" name="kode" class="form-control form-control-sm" placeholder="PTR / RZI / PAM" maxlength="10" value="{{ old('kode') }}">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Deskripsi</label>
            <textarea name="deskripsi" class="form-control form-control-sm" rows="2">{{ old('deskripsi') }}</textarea>
          </div>
          <div class="form-check mt-1">
            <input type="checkbox" name="aktif" class="form-check-input" value="1" {{ old('aktif') ? 'checked' : '' }}>
            <label class="form-check-label small">Wajib Laporan</label>
          </div>
        </div>
        <div class="modal-footer py-2 justify-content-center border-0">
          <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-success px-4">
            <i class="bi bi-check-circle me-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL EDIT --}}
@foreach($jenis as $j)
<div class="modal fade" id="editJenis{{ $j->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
    <div class="modal-content">
      <div class="modal-header py-2 border-bottom">
        <h6 class="modal-title fw-bold mb-0">
          <i class="bi bi-pencil me-2 text-warning"></i>Edit Jenis Kegiatan
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.jenis-kegiatan.update',$j) }}">
        @csrf
        @method('PUT')
        <div class="modal-body px-3 py-2">
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Nama Kegiatan <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-sm" value="{{ $j->nama }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Kode</label>
            <input type="text" name="kode" class="form-control form-control-sm" value="{{ $j->kode }}" maxlength="10">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Deskripsi</label>
            <textarea name="deskripsi" class="form-control form-control-sm" rows="2">{{ $j->deskripsi }}</textarea>
          </div>
          <div class="form-check mt-1">
            <input type="checkbox" name="aktif" class="form-check-input" value="1" {{ $j->aktif ? 'checked' : '' }}>
            <label class="form-check-label small">Wajib Laporan</label>
          </div>
        </div>
        <div class="modal-footer py-2 justify-content-center border-0">
          <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-success px-4">
            <i class="bi bi-check-circle me-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endsection
