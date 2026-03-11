@extends('layout.petugas')
@section('title','Buat Laporan')
@section('page-title','Buat Laporan Kegiatan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('petugas.laporan.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Buat</li>
@endsection
@section('content')
<div class="alert alert-info d-flex gap-2 align-items-start">
  <i class="bi bi-info-circle-fill fs-5"></i>
  <div><strong>{{ $jadwal->nama_kegiatan }}</strong><br><small>{{ $jadwal->tanggal->format('d M Y') }} &bull; Shift {{ $jadwal->shift->nama }} ({{ $jadwal->shift->jam_mulai }}–{{ $jadwal->shift->jam_selesai }}) &bull; {{ $jadwal->lokasi->nama }}</small></div>
</div>

{{-- Stepper --}}
<div class="step-header mb-3">
  <div class="step-indicator active" id="lbl1"><span>1</span> Info Laporan</div>
  <div class="step-indicator" id="lbl2"><span>2</span> Foto</div>
  <div class="step-indicator" id="lbl3"><span>3</span> Kirim</div>
</div>

<form method="POST" action="{{ route('petugas.laporan.store',$jadwal) }}" enctype="multipart/form-data" id="frmLaporan">
@csrf
<div class="form-step active" id="step1">
  <div class="card p-3">
    <div class="mb-3"><label class="form-label">Isi Laporan *</label><textarea name="isi_laporan" class="form-control @error('isi_laporan') is-invalid @enderror" rows="6" placeholder="Deskripsikan kegiatan yang dilaksanakan secara lengkap...">{{ old('isi_laporan') }}</textarea>@error('isi_laporan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Kondisi Lapangan *</label>
        <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
          <option value="">-- Pilih Kondisi --</option>
          <option value="kondusif" {{ old('kondisi')==='kondusif'?'selected':'' }}>Kondusif</option>
          <option value="tidak kondusif" {{ old('kondisi')==='tidak kondusif'?'selected':'' }}>Tidak Kondusif</option>
          <option value="perlu tindak lanjut" {{ old('kondisi')==='perlu tindak lanjut'?'selected':'' }}>Perlu Tindak Lanjut</option>
        </select>@error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6"><label class="form-label">Jumlah Pelanggaran</label><input type="number" name="jumlah_pelanggaran" class="form-control" min="0" value="{{ old('jumlah_pelanggaran',0) }}"></div>
    </div>
    <div class="mt-3"><button type="button" class="btn btn-petugas" onclick="goStep(1,2)">Lanjut <i class="bi bi-arrow-right"></i></button></div>
  </div>
</div>

<div class="form-step" id="step2">
  <div class="card p-3">
    <div class="mb-3"><label class="form-label">Foto Dokumentasi (Maks. 5 foto, maks. 2MB/foto)</label><input type="file" name="foto[]" class="form-control" multiple accept="image/*" id="inputFoto">@error('foto')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
    <div id="previewFoto" class="d-flex flex-wrap gap-2 mb-3"></div>
    <div class="d-flex gap-2"><button type="button" class="btn btn-outline-secondary" onclick="goStep(2,1)"><i class="bi bi-arrow-left"></i> Kembali</button><button type="button" class="btn btn-petugas" onclick="goStep(2,3)">Lanjut <i class="bi bi-arrow-right"></i></button></div>
  </div>
</div>

<div class="form-step" id="step3">
  <div class="card p-3">
    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>Data laporan siap dikirim. Pastikan semua informasi sudah benar.</div>
    <div class="d-flex gap-2"><button type="button" class="btn btn-outline-secondary" onclick="goStep(3,2)"><i class="bi bi-arrow-left"></i> Kembali</button><button type="submit" class="btn btn-petugas"><i class="bi bi-send me-1"></i> Kirim Laporan</button></div>
  </div>
</div>
</form>
@endsection
@push('scripts')
<script>
function goStep(from, to) {
  document.getElementById('step'+from).classList.remove('active');
  document.getElementById('step'+to).classList.add('active');
  document.getElementById('lbl'+from).classList.remove('active');
  document.getElementById('lbl'+to).classList.add('active');
}
document.getElementById('inputFoto').addEventListener('change', function() {
  const prev = document.getElementById('previewFoto');
  prev.innerHTML = '';
  [...this.files].slice(0,5).forEach(f => {
    const url = URL.createObjectURL(f);
    prev.innerHTML += `<img src="${url}" style="height:80px;width:80px;border-radius:8px;object-fit:cover">`;
  });
});
</script>
@endpush
