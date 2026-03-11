<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\TitikRazia;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(\Illuminate\Http\Request $r)
    {
        $petugas   = auth()->user()->petugas;
        $jadwalIds = $petugas->penugasan()->pluck('jadwal_id');

        $jadwal = \App\Models\Jadwal::with(['shift','lokasi','laporan','titikRazia','penugasan'])
            ->whereIn('id', $jadwalIds)
            ->when($r->status, fn($q, $v) => $q->where('status', $v))
            ->orderByDesc('tanggal')
            ->paginate(15)
            ->withQueryString();

        return view('petugas.jadwal.index', compact('jadwal', 'petugas'));
    }

    public function show(\App\Models\Jadwal $jadwal)
    {
        $petugas = auth()->user()->petugas;
        $jadwal->load(['shift','lokasi','penugasan.petugas','titikRazia.checkins.petugas','laporan']);
        return view('petugas.jadwal.show', compact('jadwal', 'petugas'));
    }

    // Halaman razia — daftar titik + checkin GPS
    public function razia(\App\Models\Jadwal $jadwal)
    {
        $petugas = auth()->user()->petugas;
        $jadwal->load(['shift','titikRazia.checkins.petugas']);

        $titikRazia = $jadwal->titikRazia;
        $selesai    = $titikRazia->where('status','selesai')->count();
        $total      = $titikRazia->count();

        return view('petugas.jadwal.razia', compact('jadwal','petugas','titikRazia','selesai','total'));
    }

    // Checkin GPS di titik razia
    public function checkinRazia(Request $r, TitikRazia $titikRazia)
    {
        $r->validate([
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'catatan'   => 'nullable|string|max:255',
        ]);

        $petugas = auth()->user()->petugas;

        if (!$petugas) {
            return response()->json(['error' => 'Data petugas tidak ditemukan.'], 403);
        }

        if ($titikRazia->sudahCheckin($petugas->id)) {
            return response()->json(['error' => 'Sudah checkin di titik ini.'], 422);
        }

        $titikRazia->checkins()->create([
            'petugas_id'    => $petugas->id,
            'latitude'      => $r->latitude,
            'longitude'     => $r->longitude,
            'catatan'       => $r->catatan,
            'waktu_checkin' => now(),
        ]);

        // Tandai titik selesai jika semua petugas sudah checkin
        $jadwal       = $titikRazia->jadwal->load('penugasan');
        $totalPetugas = $jadwal->penugasan->count();
        if ($totalPetugas && $titikRazia->checkins()->count() >= $totalPetugas) {
            $titikRazia->update(['status' => 'selesai']);
        }

        return response()->json(['success' => true, 'pesan' => 'Checkin berhasil!']);
    }
}
