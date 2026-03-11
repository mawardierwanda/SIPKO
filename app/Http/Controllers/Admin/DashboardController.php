<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Jadwal, Laporan, Petugas};

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $rekap7 = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl      = now()->subDays($i)->toDateString();
            $rekap7[] = [
                'label'   => now()->subDays($i)->format('D, d/m'),
                'jadwal'  => Jadwal::whereDate('tanggal', $tgl)->count(),
                'laporan' => Laporan::whereHas('jadwal', fn($q) => $q->whereDate('tanggal', $tgl))->count(),
            ];
        }

        return view('admin.dashboard', [
            'total_petugas'   => Petugas::where('status', 'aktif')->count(),
            'jadwal_hari_ini' => Jadwal::whereDate('tanggal', $today)->count(),
            'sudah_laporan'   => Laporan::whereHas('jadwal', fn($q) => $q->whereDate('tanggal', $today))->count(),
            'belum_laporan'   => Jadwal::whereDate('tanggal', $today)->doesntHave('laporan')->count(),
            'jadwal_terbaru'  => Jadwal::with([
                                    'jenisKegiatan',
                                    'shift',
                                    'lokasi',
                                    'laporan',
                                    'penugasan',
                                    
                                 ])->whereDate('tanggal', $today)->latest()->get(),
            'aktivitas'       => Laporan::with(['jadwal', 'petugas'])->latest('waktu_laporan')->take(8)->get(),
            'rekap7'          => $rekap7,
        ]);
    }
}
