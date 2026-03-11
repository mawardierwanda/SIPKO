@extends('layout.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection
@section('content')

{{-- STAT CARDS --}}
<div class="row g-3">
  <div class="col-6 col-lg-3">
    <div class="card p-3 d-flex flex-row align-items-center gap-3">
      <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px;height:52px;background:#dcfce7;flex-shrink:0"><i class="bi bi-people-fill text-success fs-4"></i></div>
      <div><div class="text-muted small">Total Petugas Aktif</div><div class="fw-bold fs-4">{{ $total_petugas }}</div></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card p-3 d-flex flex-row align-items-center gap-3">
      <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px;height:52px;background:#dbeafe;flex-shrink:0"><i class="bi bi-calendar-check-fill text-primary fs-4"></i></div>
      <div><div class="text-muted small">Jadwal Hari Ini</div><div class="fw-bold fs-4">{{ $jadwal_hari_ini }}</div></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card p-3 d-flex flex-row align-items-center gap-3">
      <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px;height:52px;background:#fef3c7;flex-shrink:0"><i class="bi bi-file-check-fill text-warning fs-4"></i></div>
      <div><div class="text-muted small">Sudah Laporan</div><div class="fw-bold fs-4">{{ $sudah_laporan }}</div></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card p-3 d-flex flex-row align-items-center gap-3">
      <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px;height:52px;background:#fee2e2;flex-shrink:0"><i class="bi bi-exclamation-circle-fill text-danger fs-4"></i></div>
      <div><div class="text-muted small">Belum Laporan</div><div class="fw-bold fs-4">{{ $belum_laporan }}</div></div>
    </div>
  </div>
</div>

{{-- CHART + LAPORAN TERBARU --}}
<div class="row g-3 mt-1">
  <div class="col-lg-7">
    <div class="card p-3">
      <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart-fill text-success me-2"></i>Aktivitas 7 Hari Terakhir</h6>
      <div id="chartArea" style="min-height:220px"></div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card p-3">
      <h6 class="fw-bold mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Laporan Terbaru</h6>
      @forelse($aktivitas as $l)
      <div class="d-flex align-items-start gap-2 mb-3 pb-2 border-bottom">
        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width:34px;height:34px;font-size:11px;background:#2d6a4f">{{ strtoupper(substr($l->petugas->nama??'?',0,2)) }}</div>
        <div class="flex-grow-1">
          <div class="fw-semibold small">{{ $l->petugas->nama ?? '-' }}</div>
          <div class="text-muted" style="font-size:11px">{{ $l->jadwal->nama_kegiatan ?? '-' }}</div>
          <div style="font-size:11px">
            <span class="sipko-badge {{ $l->kondisi==='kondusif'?'green':($l->kondisi==='tidak kondusif'?'red':'orange') }}">{{ $l->kondisi }}</span>
          </div>
        </div>
        <div class="text-muted" style="font-size:10px">{{ $l->waktu_laporan->diffForHumans() }}</div>
      </div>
      @empty
      <p class="text-muted small">Belum ada laporan hari ini.</p>
      @endforelse
    </div>
  </div>
</div>

{{-- JADWAL HARI INI --}}
<div class="row g-3 mt-1">
  <div class="col-12">
    <div class="card p-3">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-calendar3 text-primary me-2"></i>Jadwal Hari Ini</h6>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
      </div>
      <div class="table-responsive">
        <table class="table table-borderless table-hover mb-0">
          <thead>
            <tr>
              <th>Kegiatan</th>
              <th>Shift</th>
              <th>Lokasi</th>
              <th>Tim</th>
              <th>Petugas</th>
              <th>Laporan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse($jadwal_terbaru as $j)
          <tr>
            <td>
              <div class="fw-semibold">{{ $j->nama_kegiatan }}</div>
              <small class="text-muted">{{ $j->jenisKegiatan->nama }}</small>
            </td>
            <td><span class="sipko-badge blue">{{ $j->shift->nama }}</span></td>
            <td>{{ $j->lokasi->nama }}</td>
            <td>{{ $j->satuan ?? '-' }}</td>
            <td>{{ $j->penugasan->count() }} org</td>
            <td>
              @if($j->sudahLaporan())
                <span class="sipko-badge green"><i class="bi bi-check-circle-fill"></i> Ada</span>
              @else
                <span class="sipko-badge red"><i class="bi bi-x-circle-fill"></i> Belum</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.jadwal.show', $j) }}" class="btn btn-sm btn-outline-primary" title="Detail Jadwal">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada jadwal hari ini.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.2/apexcharts.min.js"></script>
<script>
const rekap = @json($rekap7);
new ApexCharts(document.getElementById('chartArea'),{
  chart:{type:'area',height:220,toolbar:{show:false},fontFamily:'Nunito,sans-serif'},
  series:[{name:'Jadwal',data:rekap.map(r=>r.jadwal)},{name:'Laporan',data:rekap.map(r=>r.laporan)}],
  xaxis:{categories:rekap.map(r=>r.label)},
  colors:['#2d6a4f','#40916c'],
  fill:{type:'gradient',gradient:{shadeIntensity:.8,opacityFrom:.4,opacityTo:.05}},
  stroke:{curve:'smooth',width:2},
  legend:{position:'top'},
  grid:{borderColor:'#f1f5f9'},
}).render();
</script>
@endpush
