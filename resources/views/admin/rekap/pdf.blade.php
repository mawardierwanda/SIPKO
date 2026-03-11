<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
<style>
body{font-family:Arial,sans-serif;font-size:10px;color:#333;}
h2{color:#2D6A4F;font-size:14px;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th{background:#2D6A4F;color:#fff;padding:5px 6px;font-size:9px;}
td{border:1px solid #ddd;padding:4px 6px;}
tr:nth-child(even){background:#f0fdf4;}
.ada{color:#16a34a;font-weight:bold;}
.belum{color:#dc2626;font-weight:bold;}
</style>
</head><body>
<h2>REKAPITULASI KEGIATAN OPERASIONAL — SATPOL PP KAB. KETAPANG</h2>
<p>Periode: {{ date('d M Y', strtotime($dari)) }} s.d. {{ date('d M Y', strtotime($sampai)) }}</p>
<p>Total Jadwal: <b>{{ $total_jadwal }}</b> &nbsp;|&nbsp; Sudah Laporan: <b>{{ $sudah_laporan }}</b> &nbsp;|&nbsp; Belum: <b>{{ $belum_laporan }}</b></p>
<table>
<thead><tr><th>No</th><th>Tanggal</th><th>Kegiatan</th><th>Jenis</th><th>Shift</th><th>Lokasi</th><th>Tim</th><th>Petugas</th><th>Laporan</th><th>Kondisi</th></tr></thead>
<tbody>
@foreach($jadwal as $i=>$j)
<tr>
  <td>{{ $i+1 }}</td>
  <td>{{ $j->tanggal->format('d/m/Y') }}</td>
  <td>{{ $j->nama_kegiatan }}</td>
  <td>{{ $j->jenisKegiatan->nama }}</td>
  <td>{{ $j->shift->nama }}</td>
  <td>{{ $j->lokasi->nama }}</td>
  <td>{{ $j->satuan ?? '-' }}</td>
  <td>{{ $j->penugasan->count() }}</td>
  <td class="{{ $j->sudahLaporan()?'ada':'belum' }}">{{ $j->sudahLaporan()?'Ada':'Belum' }}</td>
  <td>{{ $j->laporan?->kondisi ?? '-' }}</td>
</tr>
@endforeach
</tbody>
</table>
<p style="margin-top:14px;font-size:9px;color:#999">Dicetak: {{ date('d M Y H:i') }} &mdash; SIPKO Satpol PP Kab. Ketapang</p>
</body></html>
