<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\{Jadwal, Laporan};
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $petugas = auth()->user()->petugas;

        $laporan = Laporan::with(['jadwal.jenisKegiatan'])
            ->where('petugas_id', $petugas->id)
            ->latest('waktu_laporan')
            ->paginate(15);

        return view('petugas.laporan.index', compact('laporan'));
    }

    public function create(Jadwal $jadwal)
    {
        if ($jadwal->sudahLaporan()) {
            return redirect()->route('petugas.jadwal.index')
                ->with('error', 'Laporan untuk jadwal ini sudah ada.');
        }
        return view('petugas.laporan.create', compact('jadwal'));
    }

    public function store(Request $r, Jadwal $jadwal)
    {
        $r->validate([
            'kondisi'      => 'required|in:kondusif,tidak kondusif,perlu perhatian',
            'jumlah_personil' => 'nullable|integer|min:0',
            'catatan'      => 'nullable|string|max:2000',
            'foto'         => 'nullable|image|max:5120',
        ]);

        $petugas = auth()->user()->petugas;
        $foto    = null;

        if ($r->hasFile('foto')) {
            $foto = $r->file('foto')->store('laporan', 'public');
        }

        Laporan::create([
            'jadwal_id'       => $jadwal->id,
            'petugas_id'      => $petugas->id,
            'kondisi'         => $r->kondisi,
            'jumlah_personil' => $r->jumlah_personil,
            'catatan'         => $r->catatan,
            'foto'            => $foto,
            'waktu_laporan'   => now(),
            'status'          => $jadwal->tanggal->isToday() ? 'diterima' : 'terlambat',
        ]);

        return redirect()->route('petugas.laporan.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(Laporan $laporan)
    {
        $laporan->load(['jadwal.shift','jadwal.lokasi','jadwal.jenisKegiatan','petugas']);
        return view('petugas.laporan.show', compact('laporan'));
    }
}
