@extends('layout.petugas')
@section('title','Razia Multi-Lokasi')
@section('page-title','Razia Hari Ini')
@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/leaflet.css') }}">
<style>
#petaCheckin { height:280px; border-radius:10px; border:1px solid #e2e8f0; }
.titik-card {
  border:1px solid #e2e8f0; border-radius:10px;
  margin-bottom:10px; overflow:hidden;
  transition:box-shadow .2s;
}
.titik-card:hover { box-shadow:0 4px 12px rgba(0,0,0,.08); }
.titik-card.selesai { border-left:4px solid #16a34a; }
.titik-card.belum   { border-left:4px solid #f59e0b; }
.titik-card.sudah-checkin { border-left:4px solid #2563eb; }
</style>
@endpush

{{-- Header jadwal --}}
<div class="card mb-3 overflow-hidden" style="border:none">
  <div class="p-3 d-flex align-items-center gap-3"
       style="background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff">
    <i class="bi bi-geo-alt-fill" style="font-size:28px;opacity:.8"></i>
    <div>
      <div class="fw-bold" style="font-size:15px">{{ $jadwal->nama_kegiatan }}</div>
      <div style="font-size:12px;opacity:.8">
        {{ $jadwal->tanggal->format('d M Y') }} &bull;
        {{ $jadwal->shift->nama ?? '-' }} &bull;
        Tim {{ $jadwal->satuan ?? '-' }}
      </div>
    </div>
    <div class="ms-auto text-end">
      <div class="fw-bold" style="font-size:22px;line-height:1">{{ $selesai }}/{{ $total }}</div>
      <div style="font-size:11px;opacity:.75">Titik Selesai</div>
    </div>
  </div>
  {{-- Progress --}}
  <div class="progress" style="height:6px;border-radius:0">
    <div class="progress-bar bg-success" style="width:{{ $total ? round($selesai/$total*100) : 0 }}%"></div>
  </div>
</div>

{{-- PETA --}}
<div class="card mb-3">
  <div class="card-body p-2">
    <div id="petaCheckin"></div>
    <div class="mt-2 d-flex gap-3 justify-content-center" style="font-size:11px">
      <span><span style="display:inline-block;width:12px;height:12px;background:#16a34a;border-radius:50%;margin-right:4px"></span>Selesai</span>
      <span><span style="display:inline-block;width:12px;height:12px;background:#f59e0b;border-radius:50%;margin-right:4px"></span>Belum</span>
      <span><span style="display:inline-block;width:12px;height:12px;background:#2563eb;border-radius:50%;margin-right:4px"></span>Sudah Checkin</span>
    </div>
  </div>
</div>

{{-- DAFTAR TITIK --}}
<h6 class="fw-bold mb-2" style="font-size:13px">
  <i class="bi bi-list-check text-success me-2"></i>Titik Razia
</h6>

@forelse($titikRazia as $t)
@php
  $sudahCheckin = $t->sudahCheckin($petugas->id);
  $cardClass    = $sudahCheckin ? 'sudah-checkin' : ($t->status === 'selesai' ? 'selesai' : 'belum');
@endphp
<div class="titik-card {{ $cardClass }}">
  <div class="card-body py-2 px-3">
    <div class="d-flex align-items-center gap-3">
      {{-- Nomor urut --}}
      <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
           style="width:32px;height:32px;font-size:12px;
                  background:{{ $sudahCheckin ? '#2563eb' : ($t->status==='selesai' ? '#16a34a' : '#f59e0b') }}">
        {{ $t->urutan }}
      </div>

      {{-- Info titik --}}
      <div class="flex-grow-1">
        <div class="fw-semibold" style="font-size:13px">{{ $t->nama_titik }}</div>
        @if($t->latitude && $t->longitude)
        <small class="text-muted font-monospace" style="font-size:10px">
          {{ number_format($t->latitude,5) }}, {{ number_format($t->longitude,5) }}
        </small>
        @endif
        {{-- Checkin petugas lain --}}
        @if($t->checkins->count())
        <div class="mt-1 d-flex flex-wrap gap-1">
          @foreach($t->checkins as $c)
          <span style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:20px;padding:1px 7px;font-size:10px;color:#15803d">
            <i class="bi bi-check-circle-fill"></i>
            {{ explode(' ',$c->petugas->nama ?? '-')[0] }}
            {{ $c->waktu_checkin->format('H:i') }}
          </span>
          @endforeach
        </div>
        @endif
      </div>

      {{-- Tombol checkin --}}
      <div class="flex-shrink-0">
        @if($sudahCheckin)
          <span class="sipko-badge green" style="font-size:11px">
            <i class="bi bi-check-circle-fill"></i> Checkin
          </span>
        @else
          <button class="btn btn-sm btn-petugas px-3"
                  style="font-size:12px"
                  onclick="mulaiCheckin({{ $t->id }},'{{ addslashes($t->nama_titik) }}',{{ $t->latitude ?? 'null' }},{{ $t->longitude ?? 'null' }})">
            <i class="bi bi-geo-alt-fill me-1"></i>Checkin
          </button>
        @endif
      </div>
    </div>
  </div>
</div>
@empty
<div class="text-center py-4 text-muted">
  <i class="bi bi-geo-alt fs-2 d-block mb-2 opacity-25"></i>
  <small>Belum ada titik razia ditentukan.</small>
</div>
@endforelse

<a href="{{ route('petugas.jadwal.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">
  <i class="bi bi-arrow-left me-1"></i>Kembali ke Jadwal
</a>

{{-- MODAL CHECKIN --}}
<div class="modal fade" id="modalCheckin" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
    <div class="modal-content">
      <div class="modal-header py-2 px-3">
        <h6 class="modal-title fw-bold mb-0">
          <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
          Checkin Titik Razia
        </h6>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-3 py-3">
        <div class="rounded p-2 mb-3" style="background:#eff6ff;border:1px solid #bfdbfe;font-size:13px">
          <i class="bi bi-geo-alt me-1 text-primary"></i>
          <span id="namaTitikCheckin" class="fw-semibold text-primary">-</span>
        </div>

        <div class="mb-3">
          <label class="form-label">Lokasi Anda Saat Ini</label>
          <div id="statusGPS" class="text-muted small fst-italic">
            <i class="bi bi-hourglass-split me-1"></i>Mendeteksi lokasi GPS...
          </div>
          <div id="koordinatGPS" class="font-monospace small mt-1" style="color:#374151"></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Catatan <small class="text-muted">(opsional)</small></label>
          <textarea id="catatanCheckin" class="form-control form-control-sm" rows="2"
                    placeholder="Kondisi di lokasi..."></textarea>
        </div>

        <button id="btnKonfirmasiCheckin" class="btn btn-primary btn-sm w-100" onclick="konfirmasiCheckin()">
          <i class="bi bi-check-circle-fill me-1"></i>Konfirmasi Checkin
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('asset/js/leaflet.js') }}"></script>
<script>
@php
$titikJson = $titikRazia->map(fn($t) => [
    'id'        => $t->id,
    'nama'      => $t->nama_titik,
    'lat'       => $t->latitude,
    'lng'       => $t->longitude,
    'status'    => $t->status,
    'checkin'   => $t->sudahCheckin($petugas->id),
]);
@endphp
const TITIK_DATA = {!! json_encode($titikJson) !!};
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
const BASE_URL   = '{{ url("") }}';

let petaCheckin  = null;
let userMarker   = null;
let aktiveTitikId = null;
let userLat = null, userLng = null;

// Init peta
document.addEventListener('DOMContentLoaded', function() {
  petaCheckin = L.map('petaCheckin').setView([-1.8388, 109.9833], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(petaCheckin);

  // Pasang marker tiap titik
  TITIK_DATA.forEach(t => {
    if (!t.lat || !t.lng) return;
    const color = t.checkin ? '#2563eb' : (t.status === 'selesai' ? '#16a34a' : '#f59e0b');
    const icon = L.divIcon({
      html: `<div style="background:${color};color:#fff;width:28px;height:28px;border-radius:50%;
                         display:flex;align-items:center;justify-content:center;
                         font-size:12px;font-weight:700;border:2px solid #fff;
                         box-shadow:0 2px 6px rgba(0,0,0,.3)">${TITIK_DATA.indexOf(t)+1}</div>`,
      iconSize: [28,28], iconAnchor: [14,14], className:''
    });
    L.marker([t.lat, t.lng], {icon})
      .addTo(petaCheckin)
      .bindPopup(`<strong>${t.nama}</strong><br>${t.checkin ? '✅ Sudah checkin' : '⏳ Belum checkin'}`);
  });

  // Fit bounds jika ada titik
  const pts = TITIK_DATA.filter(t => t.lat && t.lng).map(t => [t.lat, t.lng]);
  if (pts.length) petaCheckin.fitBounds(pts, {padding:[20,20]});

  // Deteksi posisi user
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(pos => {
      userLat = pos.coords.latitude;
      userLng = pos.coords.longitude;
      if (!userMarker) {
        const me = L.divIcon({
          html: `<div style="background:#dc2626;width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 3px rgba(220,38,38,.3)"></div>`,
          iconSize:[14,14], iconAnchor:[7,7], className:''
        });
        userMarker = L.marker([userLat, userLng], {icon: me}).addTo(petaCheckin).bindPopup('Posisi Anda');
      } else {
        userMarker.setLatLng([userLat, userLng]);
      }
    }, null, {enableHighAccuracy: true});
  }
});

function mulaiCheckin(titikId, namaTitik, titikLat, titikLng) {
  aktiveTitikId = titikId;
  document.getElementById('namaTitikCheckin').textContent = namaTitik;
  document.getElementById('catatanCheckin').value = '';
  document.getElementById('koordinatGPS').textContent = '';

  if (userLat && userLng) {
    document.getElementById('statusGPS').innerHTML =
      '<i class="bi bi-geo-alt-fill text-success me-1"></i>Lokasi terdeteksi';
    document.getElementById('koordinatGPS').textContent =
      `${userLat.toFixed(6)}, ${userLng.toFixed(6)}`;
  } else {
    document.getElementById('statusGPS').innerHTML =
      '<i class="bi bi-exclamation-triangle text-warning me-1"></i>GPS belum terdeteksi, izinkan akses lokasi';
  }

  new bootstrap.Modal(document.getElementById('modalCheckin')).show();
}

function konfirmasiCheckin() {
  const btn = document.getElementById('btnKonfirmasiCheckin');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

  fetch(`${BASE_URL}/petugas/razia/${aktiveTitikId}/checkin`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': CSRF,
      'Accept': 'application/json',
    },
    body: JSON.stringify({
      latitude:  userLat,
      longitude: userLng,
      catatan:   document.getElementById('catatanCheckin').value,
    })
  })
  .then(r => r.json())
  .then(d => {
    if (d.success) {
      bootstrap.Modal.getInstance(document.getElementById('modalCheckin')).hide();
      location.reload();
    } else {
      alert(d.error || 'Gagal checkin.');
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Konfirmasi Checkin';
    }
  })
  .catch(() => {
    alert('Terjadi kesalahan. Coba lagi.');
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Konfirmasi Checkin';
  });
}
</script>
@endpush
