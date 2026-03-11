@extends('layout.admin')
@section('title','Laporan Masuk')
@section('page-title','Laporan Kegiatan Masuk')
@section('content')

<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
  <a href="{{ route('admin.laporan.index') }}"
     class="btn btn-{{ !request('kondisi') ? 'sipko' : 'outline-secondary' }} btn-sm">Semua</a>
  <a href="{{ route('admin.laporan.index') }}?kondisi=kondusif"
     class="btn btn-{{ request('kondisi')=='kondusif' ? 'success' : 'outline-success' }} btn-sm">Kondusif</a>
  <a href="{{ route('admin.laporan.index') }}?kondisi=tidak+kondusif"
     class="btn btn-{{ request('kondisi')=='tidak kondusif' ? 'danger' : 'outline-danger' }} btn-sm">Tidak Kondusif</a>
  <a href="{{ route('admin.laporan.index') }}?kondisi=perlu+tindak+lanjut"
     class="btn btn-{{ request('kondisi')=='perlu tindak lanjut' ? 'warning' : 'outline-warning' }} btn-sm">Perlu Tindak Lanjut</a>
  <div class="ms-auto">
    <a href="{{ route('admin.laporan.belum') }}" class="btn btn-outline-danger btn-sm">
      <i class="bi bi-exclamation-triangle me-1"></i>Belum Laporan
    </a>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Kegiatan</th>
            <th>Tanggal</th>
            <th>Pelapor</th>
            <th>Kondisi</th>
            <th>Waktu</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($laporan as $l)
        <tr>
          <td>
            <div class="fw-semibold">{{ $l->jadwal->nama_kegiatan ?? '(Jadwal dihapus)' }}</div>
            <small class="text-muted">
              {{ $l->jadwal->jenisKegiatan->nama ?? '-' }}
              &bull;
              {{ $l->jadwal->shift->nama ?? '-' }}
            </small>
          </td>
          <td><small>{{ $l->jadwal?->tanggal->format('d/m/Y') ?? '-' }}</small></td>
          <td>{{ $l->petugas->nama ?? '-' }}</td>
          <td>
            <span class="sipko-badge {{ $l->kondisi==='kondusif'?'green':($l->kondisi==='tidak kondusif'?'red':'orange') }}">
              {{ $l->kondisi }}
            </span>
          </td>
          <td><small>{{ $l->waktu_laporan->format('d/m H:i') }}</small></td>
          <td>
            <span class="sipko-badge {{ $l->status==='diterima'?'green':($l->status==='terlambat'?'orange':'blue') }}">
              {{ ucfirst($l->status) }}
            </span>
          </td>
          <td class="text-center">
            <div class="d-flex gap-1 justify-content-center">
              {{-- Detail --}}
              <a href="{{ route('admin.laporan.show', $l) }}"
                 class="btn btn-sm btn-outline-primary"
                 style="width:30px;height:30px;padding:0;border-radius:6px" title="Detail">
                <i class="bi bi-eye" style="font-size:11px"></i>
              </a>
              {{-- Edit --}}
              <button class="btn btn-sm btn-outline-warning"
                      style="width:30px;height:30px;padding:0;border-radius:6px" title="Edit"
                      onclick="bukaModalEdit(
                        {{ $l->id }},
                        '{{ $l->kondisi }}',
                        '{{ $l->status }}',
                        '{{ addslashes($l->catatan_admin ?? '') }}'
                      )">
                <i class="bi bi-pencil" style="font-size:11px"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-file-earmark-x fs-1 d-block mb-2 opacity-25"></i>
            Belum ada laporan masuk.
          </td>
        </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    @if($laporan->hasPages())
    <div class="p-3 border-top">{{ $laporan->links() }}</div>
    @endif
  </div>
</div>

{{-- MODAL EDIT LAPORAN --}}
<div class="modal fade" id="modalEditLaporan" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
    <div class="modal-content">
      <div class="modal-header py-2 px-3">
        <h6 class="modal-title fw-bold mb-0">
          <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Laporan
        </h6>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEditLaporan" method="POST">
        @csrf @method('PATCH')
        <div class="modal-body px-3 py-3">

          {{-- Kondisi --}}
          <div class="mb-3">
            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
            <select name="kondisi" id="editKondisi" class="form-select form-select-sm" required>
              <option value="kondusif">Kondusif</option>
              <option value="tidak kondusif">Tidak Kondusif</option>
              <option value="perlu tindak lanjut">Perlu Tindak Lanjut</option>
            </select>
          </div>

          {{-- Status --}}
          <div class="mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status" id="editStatus" class="form-select form-select-sm" required>
              <option value="diterima">Diterima</option>
              <option value="terlambat">Terlambat</option>
              <option value="pending">Pending</option>
            </select>
          </div>

          {{-- Catatan Admin --}}
          <div class="mb-3">
            <label class="form-label">Catatan Admin</label>
            <textarea name="catatan_admin" id="editCatatanAdmin"
                      class="form-control form-control-sm" rows="3"
                      placeholder="Catatan atau komentar untuk petugas..."></textarea>
          </div>

        </div>
        <div class="modal-footer py-2 px-3">
          <button type="button" class="btn btn-outline-secondary btn-sm"
                  data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sipko btn-sm px-4">
            <i class="bi bi-save me-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function bukaModalEdit(id, kondisi, status, catatanAdmin) {
  document.getElementById('editKondisi').value      = kondisi;
  document.getElementById('editStatus').value       = status;
  document.getElementById('editCatatanAdmin').value = catatanAdmin;
  document.getElementById('formEditLaporan').action = `/admin/laporan/${id}/edit`;
  new bootstrap.Modal(document.getElementById('modalEditLaporan')).show();
}
</script>
@endpush
