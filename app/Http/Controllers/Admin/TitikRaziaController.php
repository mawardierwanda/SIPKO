<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\TitikRazia;
use Illuminate\Http\Request;

class TitikRaziaController extends Controller
{
    public function index(Request $r)
    {
        $jadwal = Jadwal::with([
            'titikRazia.checkins.petugas',
            'shift',
            'penugasan.petugas',
        ])
            ->when($r->tanggal, fn($q, $v) => $q->whereDate('tanggal', $v))
            ->when($r->status,  fn($q, $v) => $q->where('status', $v))
            ->when($r->cari,    fn($q, $v) => $q->where('nama_kegiatan', 'like', "%{$v}%"))
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('admin.titik-razia.index', compact('jadwal'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'jadwal_id'          => 'required|exists:jadwal,id',
            'titik'              => 'required|array|min:1',
            'titik.*.nama_titik' => 'required|string|max:100',
            'titik.*.latitude'   => 'nullable|numeric',
            'titik.*.longitude'  => 'nullable|numeric',
            'titik.*.lokasi_id'  => 'nullable|exists:lokasi,id',
        ]);

        // Hapus titik lama lalu simpan ulang
        TitikRazia::where('jadwal_id', $r->jadwal_id)->delete();

        foreach ($r->titik as $i => $t) {
            TitikRazia::create([
                'jadwal_id'  => $r->jadwal_id,
                'lokasi_id'  => $t['lokasi_id'] ?? null,
                'nama_titik' => $t['nama_titik'],
                'latitude'   => $t['latitude']  ?? null,
                'longitude'  => $t['longitude'] ?? null,
                'urutan'     => $i + 1,
                'status'     => 'belum',
            ]);
        }

        return back()->with('success', 'Titik razia berhasil disimpan.');
    }

    public function destroy(TitikRazia $titikRazia)
    {
        $titikRazia->delete();
        return back()->with('success', 'Titik razia dihapus.');
    }

    // Petugas checkin GPS di titik razia (dipanggil dari dashboard petugas)
    public function checkin(Request $r, TitikRazia $titikRazia)
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
