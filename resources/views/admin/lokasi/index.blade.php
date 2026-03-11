@extends('layout.admin')
@section('title','Data Lokasi')
@section('page-title','Data Lokasi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">SIPKO</a></li>
<li class="breadcrumb-item"><a href="#">Master Data</a></li>
<li class="breadcrumb-item active">Lokasi</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/leaflet.css') }}">
<style>
.map-picker { height:130px; border-radius:8px; border:1px solid #dee2e6; }
.modal { overflow-y: hidden !important; }
.modal-dialog { margin: auto; top: 50%; transform: translateY(-50%) !important; }
.modal.show .modal-dialog { transform: translateY(-50%) !important; }
</style>
@endpush

@section('content')




{{-- TABEL --}}
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
        <div class="d-flex justify-content-end mb">
    <button class="btn btn-sipko px" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me"></i> Tambah Lokasi
    </button>
</div>
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:40px">#</th>
            <th>Nama Lokasi</th>
            <th>Kecamatan</th>
            <th>Koordinat</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($lokasi as $l)
        <tr>
          <td class="text-muted">{{ $loop->iteration }}</td>
          <td class="fw-semibold">{{ $l->nama }}</td>
          <td>{{ $l->alamat ?? '-' }}</td>
          <td>
            @if($l->latitude)
              <code style="font-size:12px">{{ round($l->latitude,4) }}, {{ round($l->longitude,4) }}</code>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td><small class="text-muted">{{ $l->keterangan ?? '-' }}</small></td>
          <td>
            <div class="d-flex gap-1">
              <button class="btn btn-sm btn-outline-secondary"
                      style="width:32px;height:32px;padding:0;border-radius:6px"
                      data-bs-toggle="modal" data-bs-target="#editLokasi{{ $l->id }}" title="Edit">
                <i class="bi bi-pencil-square" style="font-size:12px"></i>
              </button>
              <form method="POST" action="{{ route('admin.lokasi.destroy',$l) }}" class="d-inline"
                    onsubmit="return confirm('Hapus lokasi {{ $l->nama }}?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"
                        style="width:32px;height:32px;padding:0;border-radius:6px" title="Hapus">
                  <i class="bi bi-trash" style="font-size:12px"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted py-5">
            <i class="bi bi-geo-alt fs-1 d-block mb-2 opacity-25"></i>
            Belum ada data lokasi.
          </td>
        </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    @if($lokasi->hasPages())
    <div class="p-3 border-top">{{ $lokasi->links() }}</div>
    @endif
  </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-success"></i>Tambah Lokasi</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.lokasi.store') }}">
        @csrf
        <div class="modal-body py-2">
          <div class="mb-1">
            <label class="form-label small fw-semibold mb-1">Nama Lokasi <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-sm" value="{{ old('nama') }}" required>
          </div>
          <div class="mb-1">
            <label class="form-label small fw-semibold mb-1">Kecamatan / Alamat</label>
            <input type="text" name="alamat" class="form-control form-control-sm" value="{{ old('alamat') }}" placeholder="Delta Pawan">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Keterangan</label>
            <input type="text" name="keterangan" class="form-control form-control-sm" value="{{ old('keterangan') }}" placeholder="Area PKL, Titik razia...">
          </div>
          <div class="mb-0">
            <label class="form-label small fw-semibold mb-1">
              <i class="bi bi-map me-1 text-success"></i>Koordinat
              <span class="text-muted fw-normal">(klik peta)</span>
            </label>
            <div id="mapTambah" class="map-picker mb-2"></div>
            <div class="row g-2">
              <div class="col">
                <input type="number" step="any" name="latitude" id="latTambah"
                       class="form-control form-control-sm" value="{{ old('latitude') }}" placeholder="Latitude">
              </div>
              <div class="col">
                <input type="number" step="any" name="longitude" id="lngTambah"
                       class="form-control form-control-sm" value="{{ old('longitude') }}" placeholder="Longitude">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer py-2 justify-content-center border-0">
          <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-success px-4"><i class="bi bi-check-circle me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL EDIT --}}
@foreach($lokasi as $l)
<div class="modal fade" id="editLokasi{{ $l->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Lokasi</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.lokasi.update',$l) }}">
        @csrf
        @method('PUT')
        <div class="modal-body py-2">
          <div class="mb-1">
            <label class="form-label small fw-semibold mb-1">Nama Lokasi <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-sm" value="{{ $l->nama }}" required>
          </div>
          <div class="mb-1">
            <label class="form-label small fw-semibold mb-1">Kecamatan / Alamat</label>
            <input type="text" name="alamat" class="form-control form-control-sm" value="{{ $l->alamat }}">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Keterangan</label>
            <input type="text" name="keterangan" class="form-control form-control-sm" value="{{ $l->keterangan }}">
          </div>
          <div class="mb-0">
            <label class="form-label small fw-semibold mb-1">
              <i class="bi bi-map me-1 text-success"></i>Koordinat
              <span class="text-muted fw-normal">(klik peta)</span>
            </label>
            <div id="mapEdit{{ $l->id }}" class="map-picker mb-2"
                 data-lat="{{ $l->latitude ?? -1.8388 }}"
                 data-lng="{{ $l->longitude ?? 109.9833 }}"
                 data-has="{{ $l->latitude ? '1' : '0' }}"></div>
            <div class="row g-2">
              <div class="col">
                <input type="number" step="any" name="latitude" id="latEdit{{ $l->id }}"
                       class="form-control form-control-sm" value="{{ $l->latitude }}" placeholder="Latitude">
              </div>
              <div class="col">
                <input type="number" step="any" name="longitude" id="lngEdit{{ $l->id }}"
                       class="form-control form-control-sm" value="{{ $l->longitude }}" placeholder="Longitude">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer py-2 justify-content-center border-0">
          <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-success px-4"><i class="bi bi-check-circle me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endsection

@push('scripts')
<script src="{{ asset('asset/js/leaflet.js') }}"></script>
<script>
const DEF_LAT = -1.8388, DEF_LNG = 109.9833, DEF_ZOOM = 13;

function mkLayers(map) {
  const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' });
  const sat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });
  osm.addTo(map);
  L.control.layers({ '🗺️ Peta': osm, '🛰️ Satelit': sat }).addTo(map);
}

function initMap(divId, latId, lngId, initLat, initLng, hasMarker) {
  const map = L.map(divId).setView([initLat, initLng], DEF_ZOOM);
  mkLayers(map);
  let marker = hasMarker ? L.marker([initLat, initLng]).addTo(map) : null;

  map.on('click', function(e) {
    document.getElementById(latId).value = e.latlng.lat.toFixed(6);
    document.getElementById(lngId).value = e.latlng.lng.toFixed(6);
    if (marker) marker.setLatLng(e.latlng);
    else marker = L.marker(e.latlng).addTo(map);
  });

  [latId, lngId].forEach(id => {
    document.getElementById(id).addEventListener('change', function() {
      const la = parseFloat(document.getElementById(latId).value);
      const ln = parseFloat(document.getElementById(lngId).value);
      if (la && ln) {
        if (marker) marker.setLatLng([la, ln]);
        else marker = L.marker([la, ln]).addTo(map);
        map.setView([la, ln], DEF_ZOOM);
      }
    });
  });
  return map;
}

// Modal Tambah
(function(){
  let map = null;
  document.getElementById('modalTambah').addEventListener('shown.bs.modal', function() {
    if (map) { map.invalidateSize(); return; }
    const la = parseFloat(document.getElementById('latTambah').value) || DEF_LAT;
    const ln = parseFloat(document.getElementById('lngTambah').value) || DEF_LNG;
    map = initMap('mapTambah', 'latTambah', 'lngTambah', la, ln, !!parseFloat(document.getElementById('latTambah').value));
  });
})();

// Modal Edit
@foreach($lokasi as $l)
(function(){
  let map = null;
  document.getElementById('editLokasi{{ $l->id }}').addEventListener('shown.bs.modal', function() {
    if (map) { map.invalidateSize(); return; }
    const el = document.getElementById('mapEdit{{ $l->id }}');
    map = initMap('mapEdit{{ $l->id }}', 'latEdit{{ $l->id }}', 'lngEdit{{ $l->id }}',
      parseFloat(el.dataset.lat) || DEF_LAT,
      parseFloat(el.dataset.lng) || DEF_LNG,
      el.dataset.has === '1');
  });
})();
@endforeach
</script>
@endpush
@extends('layout.admin')
@section('title','Data Lokasi')
@section('page-title','Data Lokasi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">SIPKO</a></li>
<li class="breadcrumb-item"><a href="#">Master Data</a></li>
<li class="breadcrumb-item active">Lokasi</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/leaflet.css') }}">
<style>
.map-picker { height:130px; border-radius:8px; border:1px solid #dee2e6; }
.modal { overflow-y: hidden !important; }
.modal-dialog { margin: auto; top: 50%; transform: translateY(-50%) !important; }
.modal.show .modal-dialog { transform: translateY(-50%) !important; }
</style>
@endpush

@section('content')



{{-- TABEL --}}
<div class="card">

<div class="d-flex justify-content-end mb-3">
  <button class="btn btn-sipko px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle me-1"></i> Tambah Lokasi
  </button>
</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:40px">#</th>
            <th>Nama Lokasi</th>
            <th>Kecamatan</th>
            <th>Koordinat</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($lokasi as $l)
        <tr>
          <td class="text-muted">{{ $loop->iteration }}</td>
          <td class="fw-semibold">{{ $l->nama }}</td>
          <td>{{ $l->alamat ?? '-' }}</td>
          <td>
            @if($l->latitude)
              <code style="font-size:12px">{{ round($l->latitude,4) }}, {{ round($l->longitude,4) }}</code>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td><small class="text-muted">{{ $l->keterangan ?? '-' }}</small></td>
          <td>
            <div class="d-flex gap-1">
              <button class="btn btn-sm btn-outline-secondary"
                      style="width:32px;height:32px;padding:0;border-radius:6px"
                      data-bs-toggle="modal" data-bs-target="#editLokasi{{ $l->id }}" title="Edit">
                <i class="bi bi-pencil-square" style="font-size:12px"></i>
              </button>
              <form method="POST" action="{{ route('admin.lokasi.destroy',$l) }}" class="d-inline"
                    onsubmit="return confirm('Hapus lokasi {{ $l->nama }}?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"
                        style="width:32px;height:32px;padding:0;border-radius:6px" title="Hapus">
                  <i class="bi bi-trash" style="font-size:12px"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted py-5">
            <i class="bi bi-geo-alt fs-1 d-block mb-2 opacity-25"></i>
            Belum ada data lokasi.
          </td>
        </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    @if($lokasi->hasPages())
    <div class="p-3 border-top">{{ $lokasi->links() }}</div>
    @endif
  </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-success"></i>Tambah Lokasi</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.lokasi.store') }}">
        @csrf
        <div class="modal-body py-2">
          <div class="mb-1">
            <label class="form-label small fw-semibold mb-1">Nama Lokasi <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-sm" value="{{ old('nama') }}" required>
          </div>
          <div class="mb-1">
            <label class="form-label small fw-semibold mb-1">Kecamatan / Alamat</label>
            <input type="text" name="alamat" class="form-control form-control-sm" value="{{ old('alamat') }}" placeholder="Delta Pawan">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Keterangan</label>
            <input type="text" name="keterangan" class="form-control form-control-sm" value="{{ old('keterangan') }}" placeholder="Area PKL, Titik razia...">
          </div>
          <div class="mb-0">
            <label class="form-label small fw-semibold mb-1">
              <i class="bi bi-map me-1 text-success"></i>Koordinat
              <span class="text-muted fw-normal">(klik peta)</span>
            </label>
            <div id="mapTambah" class="map-picker mb-2"></div>
            <div class="row g-2">
              <div class="col">
                <input type="number" step="any" name="latitude" id="latTambah"
                       class="form-control form-control-sm" value="{{ old('latitude') }}" placeholder="Latitude">
              </div>
              <div class="col">
                <input type="number" step="any" name="longitude" id="lngTambah"
                       class="form-control form-control-sm" value="{{ old('longitude') }}" placeholder="Longitude">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer py-2 justify-content-center border-0">
          <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-success px-4"><i class="bi bi-check-circle me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL EDIT --}}
@foreach($lokasi as $l)
<div class="modal fade" id="editLokasi{{ $l->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Lokasi</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.lokasi.update',$l) }}">
        @csrf
        @method('PUT')
        <div class="modal-body py-2">
          <div class="mb-1">
            <label class="form-label small fw-semibold mb-1">Nama Lokasi <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-sm" value="{{ $l->nama }}" required>
          </div>
          <div class="mb-1">
            <label class="form-label small fw-semibold mb-1">Kecamatan / Alamat</label>
            <input type="text" name="alamat" class="form-control form-control-sm" value="{{ $l->alamat }}">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Keterangan</label>
            <input type="text" name="keterangan" class="form-control form-control-sm" value="{{ $l->keterangan }}">
          </div>
          <div class="mb-0">
            <label class="form-label small fw-semibold mb-1">
              <i class="bi bi-map me-1 text-success"></i>Koordinat
              <span class="text-muted fw-normal">(klik peta)</span>
            </label>
            <div id="mapEdit{{ $l->id }}" class="map-picker mb-2"
                 data-lat="{{ $l->latitude ?? -1.8388 }}"
                 data-lng="{{ $l->longitude ?? 109.9833 }}"
                 data-has="{{ $l->latitude ? '1' : '0' }}"></div>
            <div class="row g-2">
              <div class="col">
                <input type="number" step="any" name="latitude" id="latEdit{{ $l->id }}"
                       class="form-control form-control-sm" value="{{ $l->latitude }}" placeholder="Latitude">
              </div>
              <div class="col">
                <input type="number" step="any" name="longitude" id="lngEdit{{ $l->id }}"
                       class="form-control form-control-sm" value="{{ $l->longitude }}" placeholder="Longitude">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer py-2 justify-content-center border-0">
          <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-success px-4"><i class="bi bi-check-circle me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endsection

@push('scripts')
<script src="{{ asset('asset/js/leaflet.js') }}"></script>
<script>
const DEF_LAT = -1.8388, DEF_LNG = 109.9833, DEF_ZOOM = 13;

function mkLayers(map) {
  const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' });
  const sat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });
  osm.addTo(map);
  L.control.layers({ '🗺️ Peta': osm, '🛰️ Satelit': sat }).addTo(map);
}

function initMap(divId, latId, lngId, initLat, initLng, hasMarker) {
  const map = L.map(divId).setView([initLat, initLng], DEF_ZOOM);
  mkLayers(map);
  let marker = hasMarker ? L.marker([initLat, initLng]).addTo(map) : null;

  map.on('click', function(e) {
    document.getElementById(latId).value = e.latlng.lat.toFixed(6);
    document.getElementById(lngId).value = e.latlng.lng.toFixed(6);
    if (marker) marker.setLatLng(e.latlng);
    else marker = L.marker(e.latlng).addTo(map);
  });

  [latId, lngId].forEach(id => {
    document.getElementById(id).addEventListener('change', function() {
      const la = parseFloat(document.getElementById(latId).value);
      const ln = parseFloat(document.getElementById(lngId).value);
      if (la && ln) {
        if (marker) marker.setLatLng([la, ln]);
        else marker = L.marker([la, ln]).addTo(map);
        map.setView([la, ln], DEF_ZOOM);
      }
    });
  });
  return map;
}

// Modal Tambah
(function(){
  let map = null;
  document.getElementById('modalTambah').addEventListener('shown.bs.modal', function() {
    if (map) { map.invalidateSize(); return; }
    const la = parseFloat(document.getElementById('latTambah').value) || DEF_LAT;
    const ln = parseFloat(document.getElementById('lngTambah').value) || DEF_LNG;
    map = initMap('mapTambah', 'latTambah', 'lngTambah', la, ln, !!parseFloat(document.getElementById('latTambah').value));
  });
})();

// Modal Edit
@foreach($lokasi as $l)
(function(){
  let map = null;
  document.getElementById('editLokasi{{ $l->id }}').addEventListener('shown.bs.modal', function() {
    if (map) { map.invalidateSize(); return; }
    const el = document.getElementById('mapEdit{{ $l->id }}');
    map = initMap('mapEdit{{ $l->id }}', 'latEdit{{ $l->id }}', 'lngEdit{{ $l->id }}',
      parseFloat(el.dataset.lat) || DEF_LAT,
      parseFloat(el.dataset.lng) || DEF_LNG,
      el.dataset.has === '1');
  });
})();
@endforeach
</script>
@endpush
