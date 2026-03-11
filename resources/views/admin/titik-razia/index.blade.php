@extends('layout.admin')
@section('title','Razia Multi-Lokasi')
@section('page-title','Razia Multi-Lokasi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">SIPKO</a></li>
<li class="breadcrumb-item active">Razia Multi-Lokasi</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/leaflet.css') }}">
<style>
#peta { height:360px; border-radius:10px; border:1px solid #e2e8f0; }
.urutan-badge {
  width:26px; height:26px; border-radius:50%;
  background:#2d6a4f; color:#fff;
  display:inline-flex; align-items:center; justify-content:center;
  font-size:11px; font-weight:700; flex-shrink:0;
}
.checkin-chip {
  display:inline-flex; align-items:center; gap:4px;
  padding:2px 8px; border-radius:20px; font-size:11px;
  background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d;
}
.belum-chip {
  display:inline-flex; align-items:center; gap:4px;
  padding:2px 8px; border-radius:20px; font-size:11px;
  background:#fef2f2; border:1px solid #fecaca; color:#dc2626;
}
.petugas-tag {
  display:inline-flex; align-items:center; gap:4px;
  padding:2px 8px; border-radius:20px; font-size:11px;
  background:#eff6ff; border:1px solid #bfdbfe; color:#2563eb;
}
.titik-card {
  border:1px solid #e2e8f0; border-radius:8px;
  margin-bottom:8px; overflow:hidden;
}
.titik-card.selesai { border-left:4px solid #16a34a; }
.titik-card.belum   { border-left:4px solid #e2e8f0; }
.notif-banner {
  background:#fef9c3; border:1px solid #fbbf24;
  border-radius:8px; padding:10px 14px;
  font-size:13px; color:#92400e;
  display:flex; align-items:center; gap:8px;
}
</style>
@endpush

@section('content')

{{-- NOTIFIKASI TITIK BELUM DICOVERY --}}
@php
  $belumCovery = \App\Models\TitikRazia::where('status','belum')
    ->whereHas('jadwal', fn($q) => $q->whereDate('tanggal', today())->where('status','aktif'))
    ->with('jadwal')
    ->get();
@endphp
@if($belumCovery->count())
<div class="notif-banner mb-3">
  <i class="bi bi-exclamation-triangle-fill fs-5"></i>
  <div>
    <strong>{{ $belumCovery->count() }} titik razia hari ini belum dicovery!</strong>
    <div class="mt-1 d-flex flex-wrap gap-1">
      @foreach($belumCovery as $b)
      <span style="background:#fef3c7;border-radius:20px;padding:1px 8px;font-size:11px;font-weight:600">
        {{ $b->nama_titik }} — {{ $b->jadwal->nama_kegiatan }}
      </span>
      @endforeach
    </div>
  </div>
</div>
@endif

{{-- FILTER --}}
<div class="card mb-3">
  <div class="card-body py-2 px-3">
    <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
      <input type="date" name="tanggal" class="form-control form-control-sm" style="width:145px" value="{{ request('tanggal') }}">
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">Semua Status</option>
        <option value="aktif"      {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
        <option value="selesai"    {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
        <option value="dibatalkan" {{ request('status')=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
      </select>
      <input type="text" name="cari" class="form-control form-control-sm" style="width:160px" placeholder="Cari jadwal..." value="{{ request('cari') }}">
      <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
      <a href="{{ route('admin.titik-razia.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </form>
  </div>
</div>

{{-- DAFTAR JADWAL --}}
@forelse($jadwal as $j)
@php
  $titik   = $j->titikRazia;
  $selesai = $titik->where('status','selesai')->count();
  $total   = $titik->count();
  $pct     = $total ? round($selesai/$total*100) : 0;
  $petugas = $j->penugasan->map(fn($p) => $p->petugas)->filter();
@endphp

<div class="card mb-3">
  {{-- Header --}}
  <div class="card-header py-2 px-3 d-flex align-items-center justify-content-between gap-3"
       style="background:#f8fafc;border-bottom:1px solid #e2e8f0">
    <div class="d-flex align-items-center gap-2 flex-grow-1 flex-wrap">
      <i class="bi bi-calendar2-week text-success"></i>
      <span class="fw-bold">{{ $j->nama_kegiatan }}</span>
      <small class="text-muted">{{ $j->tanggal->format('d M Y') }} • {{ $j->shift->nama ?? '-' }}</small>
      @php $sc = match($j->status){ 'selesai'=>'green','dibatalkan'=>'red',default=>'blue' }; @endphp
      <span class="sipko-badge {{ $sc }}">{{ ucfirst($j->status) }}</span>
      @if($j->satuan)
      <span class="sipko-badge gray"><i class="bi bi-shield-fill"></i> Tim {{ $j->satuan }}</span>
      @endif
    </div>
    <div class="d-flex align-items-center gap-2 flex-shrink-0">
      @if($total)
      <small class="text-muted">{{ $selesai }}/{{ $total }} selesai</small>
      <div class="progress" style="width:70px;height:8px;border-radius:4px">
        <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
      </div>
      @endif
      <button class="btn btn-sipko btn-sm px-3"
              onclick="bukaModalTitik({{ $j->id }},'{{ addslashes($j->nama_kegiatan) }}',{{ $petugas->values()->toJson() }})">
        <i class="bi bi-geo-alt-fill me-1"></i>Atur Titik
      </button>
    </div>
  </div>

  {{-- Titik razia --}}
  @if($titik->count())
  <div class="card-body p-3">
    @foreach($titik->sortBy('urutan') as $t)
    @php
      $checkins      = $t->checkins;
      $sdCheckin     = $checkins->pluck('petugas_id')->toArray();
      $belumCheckin  = $petugas->filter(fn($p) => !in_array($p->id, $sdCheckin));
    @endphp
    <div class="titik-card {{ $t->status }}">
      <div class="d-flex align-items-start gap-3 p-3">
        {{-- Urutan --}}
        <span class="urutan-badge mt-1">{{ $t->urutan }}</span>
        {{-- Info titik --}}
        <div class="flex-grow-1">
          <div class="fw-semibold">{{ $t->nama_titik }}</div>
          @if($t->latitude && $t->longitude)
          <small class="text-muted font-monospace">{{ number_format($t->latitude,5) }}, {{ number_format($t->longitude,5) }}</small>
          @endif

          {{-- Petugas yang sudah checkin --}}
          <div class="mt-2 d-flex flex-wrap gap-1">
            @foreach($checkins as $c)
            <span class="checkin-chip">
              <i class="bi bi-check-circle-fill"></i>
              {{ $c->petugas->nama ?? '-' }}
              <span class="text-muted">{{ $c->waktu_checkin->format('H:i') }}</span>
            </span>
            @endforeach

            {{-- Petugas belum checkin --}}
            @foreach($belumCheckin as $bp)
            <span class="belum-chip">
              <i class="bi bi-clock"></i>
              {{ $bp->nama }}
            </span>
            @endforeach
          </div>
        </div>
        {{-- Status --}}
        <div class="flex-shrink-0">
          @if($t->status === 'selesai')
          <span class="sipko-badge green"><i class="bi bi-check-circle-fill"></i> Selesai</span>
          @else
          <span class="sipko-badge gray"><i class="bi bi-clock"></i> Belum</span>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @else
  <div class="card-body py-3 text-center text-muted small fst-italic">
    <i class="bi bi-geo-alt me-1"></i>Belum ada titik razia. Klik <strong>Atur Titik</strong>.
  </div>
  @endif
</div>
@empty
<div class="text-center text-muted py-5">
  <i class="bi bi-map" style="font-size:48px;opacity:.2;display:block;margin-bottom:12px"></i>
  Tidak ada jadwal ditemukan.
</div>
@endforelse

@if($jadwal->hasPages())
<div class="mt-2">{{ $jadwal->links() }}</div>
@endif

{{-- ══════ MODAL ATUR TITIK ══════ --}}
<div class="modal fade" id="modalTitik" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header py-2 px-3">
        <h6 class="modal-title fw-bold mb-0">
          <i class="bi bi-geo-alt-fill me-2 text-success"></i>
          <span id="judulModalTitik">Atur Titik Razia</span>
        </h6>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="row g-0" style="min-height:500px">

          {{-- Kiri: form --}}
          <div class="col-md-5 border-end p-3 d-flex flex-column" style="max-height:580px;overflow-y:auto">
            <form id="formTitik" method="POST" action="{{ route('admin.titik-razia.store') }}">
              @csrf
              <input type="hidden" name="jadwal_id" id="inputJadwalIdTitik">

              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold small">Daftar Titik Razia</span>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="tambahTitikBaris()">
                  <i class="bi bi-plus-circle me-1"></i>Tambah Titik
                </button>
              </div>

              <div id="listTitik">
                <div class="text-center text-muted small py-3 fst-italic" id="emptyTitik">
                  Klik "Tambah Titik" atau klik pada peta.
                </div>
              </div>

              <div class="mt-3">
                <button type="submit" class="btn btn-sipko btn-sm w-100">
                  <i class="bi bi-save me-1"></i>Simpan Titik Razia
                </button>
              </div>
            </form>
          </div>

          {{-- Kanan: peta --}}
          <div class="col-md-7 p-3">
            <div class="mb-2">
              <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Klik peta untuk tambah titik. Drag marker untuk pindahkan.</small>
            </div>
            <div id="peta"></div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('asset/js/leaflet.js') }}"></script>
<script>
let petaTitik    = null;
let markers      = [];
let barisCounter = 0;
let petugasJadwal = [];

function bukaModalTitik(jadwalId, namaJadwal, petugas) {
  document.getElementById('inputJadwalIdTitik').value = jadwalId;
  document.getElementById('judulModalTitik').textContent = namaJadwal;
  document.getElementById('listTitik').innerHTML = '';
  barisCounter = 0;
  markers = [];
  petugasJadwal = petugas;

  const modal = new bootstrap.Modal(document.getElementById('modalTitik'));
  modal.show();

  document.getElementById('modalTitik').addEventListener('shown.bs.modal', function init() {
    if (!petaTitik) {
      petaTitik = L.map('peta').setView([-1.8388, 109.9833], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
      }).addTo(petaTitik);
      petaTitik.on('click', e => tambahTitikBaris(e.latlng.lat, e.latlng.lng));
    } else {
      petaTitik.invalidateSize();
    }
    this.removeEventListener('shown.bs.modal', init);
  });
}

function tambahTitikBaris(lat = null, lng = null) {
  const idx = barisCounter++;
  const empty = document.getElementById('emptyTitik');
  if (empty) empty.style.display = 'none';

  // Pilihan petugas untuk titik ini
  let petugasOpts = petugasJadwal.map((p,i) =>
    `<div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox"
             name="titik[${idx}][petugas][]" value="${p.id}"
             id="pet_${idx}_${i}" checked>
      <label class="form-check-label small" for="pet_${idx}_${i}">${p.nama.split(' ')[0]}</label>
    </div>`
  ).join('');

  const div = document.createElement('div');
  div.id = `titikBaris${idx}`;
  div.className = 'border rounded p-2 mb-2';
  div.style.background = '#f8fafc';
  div.innerHTML = `
    <div class="d-flex align-items-center gap-2 mb-2">
      <span class="urutan-badge">${document.querySelectorAll('[id^=titikBaris]').length + 1}</span>
      <input type="text" name="titik[${idx}][nama_titik]"
             class="form-control form-control-sm flex-grow-1"
             placeholder="Nama titik razia..." required>
      <button type="button" class="btn btn-sm btn-outline-danger px-2"
              onclick="hapusTitikBaris(${idx})">
        <i class="bi bi-x"></i>
      </button>
    </div>
    <div class="row g-1 mb-2">
      <div class="col-6">
        <input type="number" step="any" name="titik[${idx}][latitude]"
               id="lat${idx}" class="form-control form-control-sm"
               placeholder="Latitude" value="${lat ?? ''}">
      </div>
      <div class="col-6">
        <input type="number" step="any" name="titik[${idx}][longitude]"
               id="lng${idx}" class="form-control form-control-sm"
               placeholder="Longitude" value="${lng ?? ''}">
      </div>
    </div>
    ${petugasOpts ? `<div class="border-top pt-2 mt-1">
      <small class="text-muted d-block mb-1"><i class="bi bi-people me-1"></i>Petugas di titik ini:</small>
      ${petugasOpts}
    </div>` : '<small class="text-muted fst-italic">Belum ada petugas ditugaskan di jadwal ini.</small>'}
    <input type="hidden" name="titik[${idx}][lokasi_id]" value="">
  `;
  document.getElementById('listTitik').appendChild(div);

  // Marker peta
  if (lat && lng && petaTitik) {
    const num = document.querySelectorAll('[id^=titikBaris]').length;
    const marker = L.marker([lat, lng], {draggable:true})
      .addTo(petaTitik)
      .bindPopup(`Titik ${num}`)
      .openPopup();

    marker.on('dragend', e => {
      const pos = e.target.getLatLng();
      const latEl = document.getElementById(`lat${idx}`);
      const lngEl = document.getElementById(`lng${idx}`);
      if (latEl) latEl.value = pos.lat.toFixed(7);
      if (lngEl) lngEl.value = pos.lng.toFixed(7);
    });

    markers.push(marker);
    petaTitik.setView([lat, lng], 15);
  }
}

function hapusTitikBaris(idx) {
  const el = document.getElementById(`titikBaris${idx}`);
  if (el) el.remove();
  document.querySelectorAll('[id^=titikBaris]').forEach((row, i) => {
    const badge = row.querySelector('.urutan-badge');
    if (badge) badge.textContent = i + 1;
  });
}

// Sync input lat/lng ke marker
document.addEventListener('input', function(e) {
  if (!e.target.id.match(/^(lat|lng)\d+$/)) return;
  const idx   = parseInt(e.target.id.replace(/\D/g,''));
  const lat   = parseFloat(document.getElementById(`lat${idx}`)?.value);
  const lng   = parseFloat(document.getElementById(`lng${idx}`)?.value);
  if (!isNaN(lat) && !isNaN(lng) && markers[idx]) {
    markers[idx].setLatLng([lat, lng]);
  }
});
</script>
@endpush
