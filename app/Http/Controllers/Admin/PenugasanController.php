<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Jadwal, Petugas, Penugasan};
use Illuminate\Http\Request;

class PenugasanController extends Controller {

    public function index(Request $r) {
        $jadwal = Jadwal::with(['penugasan.petugas','laporan','jenisKegiatan','shift','lokasi'])
            ->when($r->tanggal, fn($q,$t) => $q->whereDate('tanggal',$t))
            ->when($r->status,  fn($q,$s) => $q->where('status',$s))
            ->orderBy('tanggal','desc')
            ->paginate(15)
            ->withQueryString();

        $petugas = Petugas::where('status','aktif')
            ->orderBy('nama')
            ->get();

        return view('admin.penugasan.index', compact('jadwal','petugas'));
    }

    /** AJAX — cari petugas + cek konflik jadwal di tanggal tertentu */
    public function cariPetugas(Request $r) {
        $q       = $r->q ?? '';
        $tanggal = $r->tanggal;
        $jadwalId = $r->jadwal_id;

        // ID petugas yang sudah ditugaskan di jadwal lain pada tanggal yang sama
        $konflik = collect();
        if ($tanggal) {
            $konflik = Penugasan::whereHas('jadwal', function($q) use ($tanggal, $jadwalId) {
                $q->whereDate('tanggal', $tanggal);
                if ($jadwalId) $q->where('id','!=',$jadwalId);
            })->pluck('petugas_id');
        }

        $petugas = Petugas::where('status','aktif')
            ->where(fn($query) =>
                $query->where('nama','like',"%{$q}%")
                      ->orWhere('nip','like',"%{$q}%")
            )
            ->orderBy('nama')
            ->get()
            ->map(fn($p) => [
                'id'      => $p->id,
                'nama'    => $p->nama,
                'nip'     => $p->nip ?? '-',
                'jabatan' => $p->jabatan ?? '-',
                'satuan'  => $p->satuan ?? '-',
                'konflik' => $konflik->contains($p->id),
            ]);

        return response()->json($petugas);
    }

    public function store(Request $r) {
        $r->validate([
            'jadwal_id'      => 'required|exists:jadwal,id',
            'koordinator_id' => 'required|exists:petugas,id',
            'anggota'        => 'nullable|array',
            'anggota.*'      => 'exists:petugas,id',
            'catatan'        => 'nullable|string|max:500',
        ]);

        $jadwal = Jadwal::findOrFail($r->jadwal_id);
        $jadwal->penugasan()->delete();

        Penugasan::create([
            'jadwal_id'  => $jadwal->id,
            'petugas_id' => $r->koordinator_id,
            'peran'      => 'koordinator',
            'catatan'    => $r->catatan,
        ]);

        foreach (($r->anggota ?? []) as $pid) {
            if ($pid != $r->koordinator_id) {
                Penugasan::create([
                    'jadwal_id'  => $jadwal->id,
                    'petugas_id' => $pid,
                    'peran'      => 'anggota',
                ]);
            }
        }

        return redirect()->route('admin.penugasan.index')
            ->with('success','Penugasan berhasil disimpan!');
    }

    public function destroy(Penugasan $penugasan) {
        $penugasan->delete();
        return back()->with('success','Penugasan dihapus.');
    }
}