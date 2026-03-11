<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Laporan;

class DashboardController extends Controller
{
    public function index()
    {
        $petugas = auth()->user()->petugas;

        if (!$petugas) {
            abort(403, 'Data petugas tidak ditemukan.');
        }

        $jadwalIds = $petugas->penugasan()->pluck('jadwal_id');

        $jadwalHariIni = \App\Models\Jadwal::with([
                'shift', 'lokasi', 'laporan', 'titikRazia'
            ])
            ->whereIn('id', $jadwalIds)
            ->whereDate('tanggal', today())
            ->get();

        $jadwalMendatang = \App\Models\Jadwal::with(['shift', 'lokasi'])
            ->whereIn('id', $jadwalIds)
            ->whereDate('tanggal', '>', today())
            ->orderBy('tanggal')
            ->take(5)
            ->get();

        $riwayat = Laporan::with(['jadwal'])
            ->where('petugas_id', $petugas->id)
            ->latest('waktu_laporan')
            ->take(5)
            ->get();

        $totalTugas = Laporan::where('petugas_id', $petugas->id)->count();

        return view('petugas.dashboard', compact(
            'petugas',
            'jadwalHariIni',
            'jadwalMendatang',
            'riwayat',
            'totalTugas'
        ));
    }
}
