@extends('layout.admin')
@section('title','Penugasan Tim')
@section('page-title','Penugasan Tim')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">SIPKO</a></li>
<li class="breadcrumb-item active">Penugasan Tim</li>
@endsection

@push('styles')
<style>
.tim-badge {
  display:inline-block;
  padding:4px 14px;
  border-radius:20px;
  font-size:12px;
  font-weight:700;
  color:#fff;
}
.tim-alpha   { background:#2563eb; }
.tim-bravo   { background:#dc2626; }
.tim-charlie { background:#16a34a; }
.tim-delta   { background:#9333ea; }
.tim-echo    { background:#d97706; }
.tim-default { background:#64748b; }
</style>
@endpush

@section('content')

{{-- FILTER BAR --}}
<div class="card mb-3">
  <div class="card-body py-2 px-3">
    <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
      <input type="date" name="tanggal" class="form-control form-control-sm" style="width:145px" value="{{ request('tanggal') }}">
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">Semua Status</option>
        <option value="aktif"      {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
        <option value="selesai"    {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
        <option value="dibatalkan" {{ request('status')=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
      </select>
      <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
      <a href="{{ route('admin.penugasan.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
      <div class="ms-auto">
        <button type="button" class="btn btn-sipko btn-sm px-3" onclick="bukaFormTambah()">
          <i class="bi bi-plus-circle me-1"></i>Tambah Penugasan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- TABEL --}}
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Jadwal</th>
            <th>Tim</th>
            <th>Petugas Ditugaskan</th>
            <th>Jabatan di Tim</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($jadwal as $j)
        @php
          $namaTim = $j->satuan ?? null;
          $timClass = match(strtolower($namaTim ?? '')) {
            'alpha'   => 'tim-alpha',
            'bravo'   => 'tim-bravo',
            'charlie' => 'tim-charlie',
            'delta'   => 'tim-delta',
            'echo'    => 'tim-echo',
            default   => 'tim-default',
          };
          $koordinator = $j->penugasan->firstWhere('peran','koordinator');
          $anggota     = $j->penugasan->where('peran','anggota');
          $totalOrg    = $j->penugasan->count();
        @endphp
        <tr>
          {{-- Jadwal --}}
          <td>
            <div class="fw-semibold" style="max-width:180px">
              {{ $j->nama_kegiatan }}
              @if($j->lokasi)
              <span class="text-muted"> — {{ $j->lokasi->nama }}</span>
              @endif
            </div>
            <small class="text-muted">
              {{ $j->tanggal->format('d M Y') }} &nbsp;•&nbsp; {{ $j->shift->nama ?? '-' }}
            </small>
          </td>

          {{-- Tim --}}
          <td>
            @if($namaTim)
              <span class="tim-badge {{ $timClass }}">{{ $namaTim }}</span>
            @else
              <span class="text-muted small fst-italic">—</span>
            @endif
          </td>

          {{-- Petugas --}}
          <td>
            @if($totalOrg)
              @php
                $namaList = $j->penugasan->map(fn($p) => explode(' ', $p->petugas->nama)[0]);
                $tampil   = $namaList->take(3)->implode(', ');
                $sisa     = $totalOrg - 3;
              @endphp
              <span class="fw-semibold small">
                {{ $tampil }}{{ $sisa > 0 ? ' +' . $sisa . ' petugas' : '' }}
              </span>
            @else
              <span class="text-muted fst-italic small">Belum ditugaskan</span>
            @endif
          </td>

          {{-- Jabatan di Tim --}}
          <td>
            @if($koordinator)
              <div class="fw-semibold small" style="color:#a16207">
                👑 Ketua
                @if($anggota->count())
                <span class="text-muted fw-normal">+{{ $anggota->count() }} Anggota</span>
                @endif
              </div>
            @elseif($totalOrg)
              <small class="text-muted">{{ $totalOrg }} Anggota</small>
            @else
              <span class="text-muted small fst-italic">—</span>
            @endif
          </td>

          {{-- Aksi --}}
          <td class="text-center">
            <div class="d-flex gap-1 justify-content-center">
              <button type="button"
                      class="btn btn-sm btn-outline-success"
                      style="width:30px;height:30px;padding:0;border-radius:6px"
                      title="{{ $totalOrg ? 'Edit Penugasan' : 'Tugaskan' }}"
                      onclick="bukaModal_Penugasan({{ $j->id }},'{{ $j->tanggal->format('Y-m-d') }}','{{ addslashes($j->nama_kegiatan) }}','{{ addslashes($j->satuan ?? '') }}')">
                <i class="bi bi-people-fill" style="font-size:11px"></i>
              </button>
              @if($totalOrg)
              <form method="POST"
                    action="{{ route('admin.penugasan.destroyAll', $j->id) }}"
                    onsubmit="return confirm('Hapus semua penugasan jadwal ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"
                        style="width:30px;height:30px;padding:0;border-radius:6px"
                        title="Hapus Penugasan">
                  <i class="bi bi-person-x" style="font-size:11px"></i>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-5">
            <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
            Tidak ada jadwal.
          </td>
        </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    @if($jadwal->hasPages())
    <div class="p-3 border-top">{{ $jadwal->links() }}</div>
    @endif
  </div>
</div>

{{-- ══════════════════════ MODAL PENUGASAN ══════════════════════ --}}
<div class="modal fade" id="modalPenugasan" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
    <div class="modal-content">
      <div class="modal-header py-2 px-3">
        <h6 class="modal-title fw-bold mb-0">
          <i class="bi bi-people-fill me-2 text-success"></i>
          <span id="modalPenugasanJudul">Penugasan Tim</span>
        </h6>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-3 py-3">
        <form method="POST" action="{{ route('admin.penugasan.store') }}" id="formPenugasan">
          @csrf
          <input type="hidden" name="jadwal_id" id="inputJadwalId">

          {{-- Info jadwal --}}
          <div class="rounded p-2 mb-3" style="background:#f0fdf4;border:1px solid #bbf7d0;font-size:13px">
            <i class="bi bi-calendar2-check me-1 text-success"></i>
            <span id="infoJadwalNama" class="fw-semibold text-success">-</span>
          </div>

          {{-- Nama Tim (dari jadwal) --}}
          <div class="mb-3">
            <label class="form-label">Tim</label>
            <div class="form-control form-control-sm" style="background:#f8fafc;color:#374151">
              <span id="displayNamaTim" class="fw-semibold">—</span>
            </div>
            <input type="hidden" name="nama_tim" id="inputNamaTim">
          </div>

          {{-- Koordinator --}}
          <div class="mb-3">
            <label class="form-label">Koordinator <span class="text-danger">*</span></label>
            <div class="d-flex gap-2">
              <div id="displayKoordinator"
                   class="form-control form-control-sm flex-grow-1 text-muted fst-italic"
                   style="cursor:pointer;min-height:32px;line-height:1.8"
                   onclick="bukaModalPilih('koordinator')">
                Klik untuk pilih koordinator...
              </div>
              <button type="button" class="btn btn-outline-secondary btn-sm px-2"
                      onclick="bukaModalPilih('koordinator')">
                <i class="bi bi-search"></i>
              </button>
            </div>
            <input type="hidden" name="koordinator_id" id="inputKoordinator" required>
          </div>

          {{-- Anggota --}}
          <div class="mb-3">
            <label class="form-label">Anggota Tim</label>
            <div class="d-flex gap-2 mb-2">
              <button type="button" class="btn btn-outline-success btn-sm flex-grow-1"
                      onclick="bukaModalPilih('anggota')">
                <i class="bi bi-person-plus me-1"></i>Pilih Anggota
              </button>
              <button type="button" class="btn btn-outline-danger btn-sm px-2"
                      onclick="hapusSemuaAnggota()">
                <i class="bi bi-trash"></i>
              </button>
            </div>
            <div id="chipAnggota"
                 class="d-flex flex-wrap gap-1 p-2 border rounded"
                 style="min-height:40px;border-color:#e2e8f0!important">
              <span class="text-muted small fst-italic">Belum ada anggota</span>
            </div>
            <div id="inputAnggotaContainer"></div>
          </div>

          {{-- Catatan --}}
          <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control form-control-sm" rows="2"
                      placeholder="Instruksi khusus..."></textarea>
          </div>

          <button type="submit" class="btn btn-sipko btn-sm w-100">
            <i class="bi bi-save me-1"></i>Simpan Penugasan
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════ MODAL PILIH PETUGAS ══════════════════════ --}}
<div class="modal fade" id="modalPilihPetugas" tabindex="-1" style="z-index:1060">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content">
      <div class="modal-header py-2 px-3">
        <h6 class="modal-title fw-bold mb-0" id="modalPilihJudul">Pilih Petugas</h6>
        <button type="button" class="btn-close btn-sm" onclick="tutupModalPilih()"></button>
      </div>
      <div class="modal-body p-0">
        <div class="p-3 border-bottom">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="modalSearch" class="form-control border-start-0"
                   placeholder="Ketik nama atau NIP..." autocomplete="off">
            <button class="btn btn-outline-secondary" type="button"
                    onclick="document.getElementById('modalSearch').value='';filterModal()">
              <i class="bi bi-x"></i>
            </button>
          </div>
          <div class="mt-2 d-flex align-items-center justify-content-between">
            <small class="text-muted">
              <span style="display:inline-block;width:9px;height:9px;background:#fef9c3;border:1px solid #fbbf24;border-radius:2px;margin-right:4px"></span>
              Sudah ada jadwal hari ini
            </small>
            <small id="modalHitungTerpilih" class="text-success fw-semibold"></small>
          </div>
        </div>
        <div id="modalListPetugas" style="max-height:320px;overflow-y:auto">
          <div class="text-center text-muted py-4">
            <i class="bi bi-hourglass-split d-block mb-1" style="font-size:22px"></i>Memuat...
          </div>
        </div>
      </div>
      <div class="modal-footer py-2 px-3">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="tutupModalPilih()">Batal</button>
        <button type="button" class="btn btn-sipko btn-sm px-4" onclick="konfirmasiPilihan()">
          <i class="bi bi-check-lg me-1"></i>Pilih
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal-backdrop fade" id="backdropPilih" style="display:none;z-index:1055"></div>

@endsection

@push('scripts')
<script>
@php
$petugasJson = $petugas->map(function($p) {
    return ['id'=>$p->id,'nama'=>$p->nama,'nip'=>$p->nip??'-','jabatan'=>$p->jabatan??'-'];
});
@endphp
const semuaPetugas = {!! json_encode($petugasJson) !!};
const URL_CARI     = '{{ route("admin.penugasan.cari") }}';
const CSRF         = document.querySelector('meta[name="csrf-token"]').content;

let modeTerpilih    = 'koordinator';
let anggotaTerpilih = new Map();
let petugasModal    = [];
let sementaraPilih  = new Set();
let modalPenugasan  = null;
let modalPilih      = null;
let tanggalAktif    = '';

const timColors = {
  alpha:'#2563eb', bravo:'#dc2626', charlie:'#16a34a',
  delta:'#9333ea', echo:'#d97706', default:'#64748b'
};

function getTimColor(nama) {
  return timColors[(nama||'').toLowerCase()] || timColors.default;
}

function pilihTim(nama) {
  document.getElementById('inputNamaTim').value = nama;
  document.querySelectorAll('.tim-btn').forEach(b => {
    b.classList.toggle('active', b.dataset.tim === nama);
    const c = getTimColor(b.dataset.tim);
    if (b.dataset.tim === nama) {
      b.style.background = c;
      b.style.color = '#fff';
      b.style.borderColor = c;
    } else {
      b.style.background = '';
      b.style.color = '';
      b.style.borderColor = '';
    }
  });
}

function bukaModal_Penugasan(jadwalId, tanggal, namaJadwal, namaTim) {
  document.getElementById('inputJadwalId').value = jadwalId;
  document.getElementById('infoJadwalNama').textContent = namaJadwal;
  document.getElementById('modalPenugasanJudul').textContent = namaJadwal;
  tanggalAktif = tanggal;
  resetForm();
  // Isi nama tim dari kolom satuan jadwal
  if (namaTim) {
    document.getElementById('inputNamaTim').value = namaTim;
    const disp = document.getElementById('displayNamaTim');
    disp.textContent = namaTim;
    disp.style.color = getTimColor(namaTim);
  }
  modalPenugasan = new bootstrap.Modal(document.getElementById('modalPenugasan'));
  modalPenugasan.show();
}

function bukaFormTambah() {
  document.getElementById('inputJadwalId').value = '';
  document.getElementById('infoJadwalNama').textContent = '— Pilih dari tombol 👥 di tabel —';
  document.getElementById('modalPenugasanJudul').textContent = 'Tambah Penugasan';
  tanggalAktif = '';
  resetForm();
  modalPenugasan = new bootstrap.Modal(document.getElementById('modalPenugasan'));
  modalPenugasan.show();
}

function resetForm() {
  document.getElementById('inputKoordinator').value = '';
  document.getElementById('displayKoordinator').innerHTML = 'Klik untuk pilih koordinator...';
  document.getElementById('displayKoordinator').className = 'form-control form-control-sm flex-grow-1 text-muted fst-italic';
  document.getElementById('inputNamaTim').value = '';
  const disp = document.getElementById('displayNamaTim');
  if (disp) { disp.textContent = '—'; disp.style.color = ''; }
  anggotaTerpilih.clear();
  renderChipAnggota();
}

function bukaModalPilih(mode) {
  modeTerpilih = mode;
  document.getElementById('modalPilihJudul').textContent =
    mode === 'koordinator' ? '👑 Pilih Koordinator' : '👥 Pilih Anggota Tim';
  sementaraPilih = new Set();
  if (mode === 'koordinator') {
    const v = document.getElementById('inputKoordinator').value;
    if (v) sementaraPilih.add(parseInt(v));
  } else {
    anggotaTerpilih.forEach((_,k) => sementaraPilih.add(k));
  }
  document.getElementById('modalSearch').value = '';
  muatPetugasModal();
  modalPilih = new bootstrap.Modal(document.getElementById('modalPilihPetugas'), {backdrop:false});
  modalPilih.show();
  const bd = document.getElementById('backdropPilih');
  bd.style.display = 'block';
  setTimeout(() => bd.classList.add('show'), 10);
}

function tutupModalPilih() {
  if (modalPilih) modalPilih.hide();
  const bd = document.getElementById('backdropPilih');
  bd.classList.remove('show');
  setTimeout(() => bd.style.display = 'none', 200);
}

function muatPetugasModal() {
  if (!tanggalAktif) {
    petugasModal = semuaPetugas.map(p => ({...p, konflik:false}));
    renderModal(); return;
  }
  const jid = document.getElementById('inputJadwalId').value;
  fetch(`${URL_CARI}?q=&tanggal=${tanggalAktif}&jadwal_id=${jid}`, {
    headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}
  }).then(r=>r.json()).then(d=>{petugasModal=d;renderModal();});
}

function renderModal() {
  const q = document.getElementById('modalSearch').value.toLowerCase();
  let list = petugasModal.filter(p=>!q||p.nama.toLowerCase().includes(q)||p.nip.toLowerCase().includes(q));
  if (!list.length) {
    document.getElementById('modalListPetugas').innerHTML =
      '<div class="text-center text-muted py-4"><i class="bi bi-person-x d-block mb-1" style="font-size:24px"></i>Tidak ditemukan</div>';
    updateCounter(); return;
  }
  list.sort((a,b)=>{
    if(a.konflik&&!b.konflik)return 1;
    if(!a.konflik&&b.konflik)return -1;
    return a.nama.localeCompare(b.nama);
  });
  let html = '';
  list.forEach(p => {
    const ok = sementaraPilih.has(p.id);
    const bg = p.konflik?'#fffbeb':(ok?'#f0fdf4':'#fff');
    html += `<div onclick="togglePilih(${p.id},'${p.nama.replace(/'/g,"\\'")}','${p.nip}','${p.jabatan}')"
      style="cursor:pointer;background:${bg};border-bottom:1px solid #f1f5f9"
      class="px-3 py-2 d-flex align-items-center gap-3">
      <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
           style="width:36px;height:36px;font-size:13px;background:${p.konflik?'#f59e0b':'#2d6a4f'}">
        ${p.nama.charAt(0).toUpperCase()}
      </div>
      <div class="flex-grow-1">
        <div class="fw-semibold small">${p.nama}
          ${p.konflik?'<span class="badge bg-warning text-dark ms-1" style="font-size:9px">⚠ Jadwal lain</span>':''}
        </div>
        <div style="font-size:11px;color:#94a3b8">NIP: ${p.nip} &nbsp;•&nbsp; ${p.jabatan}</div>
      </div>
      <i class="bi ${ok?'bi-check-circle-fill text-success':'bi-circle text-muted'} flex-shrink-0" style="font-size:18px"></i>
    </div>`;
  });
  document.getElementById('modalListPetugas').innerHTML = html;
  updateCounter();
}

function togglePilih(id,nama,nip,jabatan) {
  if (modeTerpilih==='koordinator') { sementaraPilih.clear(); sementaraPilih.add(id); }
  else { sementaraPilih.has(id)?sementaraPilih.delete(id):sementaraPilih.add(id); }
  renderModal();
}

function konfirmasiPilihan() {
  if (modeTerpilih==='koordinator') {
    const id = [...sementaraPilih][0];
    if (!id) { alert('Pilih koordinator dulu.'); return; }
    const p = petugasModal.find(x=>x.id===id);
    document.getElementById('inputKoordinator').value = id;
    const d = document.getElementById('displayKoordinator');
    d.innerHTML = `<span class="fw-semibold text-dark">👑 ${p.nama}</span><small class="text-muted ms-1">${p.nip}</small>`;
    d.className = 'form-control form-control-sm flex-grow-1';
  } else {
    anggotaTerpilih.clear();
    sementaraPilih.forEach(id => {
      const p = petugasModal.find(x=>x.id===id);
      if (p) anggotaTerpilih.set(id,p);
    });
    renderChipAnggota();
  }
  tutupModalPilih();
}

function renderChipAnggota() {
  const box = document.getElementById('chipAnggota');
  const inp = document.getElementById('inputAnggotaContainer');
  if (!anggotaTerpilih.size) {
    box.innerHTML = '<span class="text-muted small fst-italic">Belum ada anggota</span>';
    inp.innerHTML = ''; return;
  }
  let c='',h='';
  anggotaTerpilih.forEach((p,id) => {
    c += `<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded"
      style="background:#f0fdf4;border:1px solid #bbf7d0;font-size:12px">
      <span class="fw-semibold text-success">${p.nama}</span>
      <button type="button" onclick="hapusAnggota(${id})"
        class="btn p-0 border-0 bg-transparent text-danger ms-1" style="font-size:11px;line-height:1">
        <i class="bi bi-x-circle-fill"></i></button></span>`;
    h += `<input type="hidden" name="anggota[]" value="${id}">`;
  });
  box.innerHTML = c; inp.innerHTML = h;
}

function hapusAnggota(id) { anggotaTerpilih.delete(id); renderChipAnggota(); }
function hapusSemuaAnggota() { anggotaTerpilih.clear(); renderChipAnggota(); }
function filterModal() { renderModal(); }
function updateCounter() {
  const el = document.getElementById('modalHitungTerpilih');
  el.textContent = (modeTerpilih==='anggota'&&sementaraPilih.size)?`${sementaraPilih.size} dipilih`:'';
}
document.getElementById('modalSearch').addEventListener('input', filterModal);
</script>
@endpush
