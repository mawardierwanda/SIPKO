<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJadwalRequest;
use App\Models\Jadwal;
use App\Models\JenisKegiatan;
use App\Models\Lokasi;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $jadwal = Jadwal::with(['jenisKegiatan', 'shift', 'lokasi', 'laporan', 'penugasan'])
            ->when($request->tanggal, fn ($q, $v) => $q->whereDate('tanggal', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->jenis, fn ($q, $v) => $q->where('jenis_kegiatan_id', $v))
            ->when($request->cari, fn ($q, $v) => $q->where('nama_kegiatan', 'like', "%{$v}%"))
            ->latest('tanggal')
            ->paginate(15)
            ->withQueryString();

        return view('admin.jadwal.index', [
            'jadwal'     => $jadwal,
            'jenis_list' => JenisKegiatan::where('aktif', true)->get(),
        ]);
    }

    public function riwayat(Request $request)
    {
        $query = Jadwal::withTrashed()
            ->with(['jenisKegiatan', 'shift', 'lokasi', 'laporan', 'penugasan.petugas', 'dibuatOleh']);

        if ($request->status === 'dihapus') {
            $query->onlyTrashed();
        } elseif ($request->status) {
            $query->where('status', $request->status);
        }

        $jadwal = $query
            ->when($request->dari,   fn ($q, $v) => $q->whereDate('tanggal', '>=', $v))
            ->when($request->sampai, fn ($q, $v) => $q->whereDate('tanggal', '<=', $v))
            ->when($request->jenis,  fn ($q, $v) => $q->where('jenis_kegiatan_id', $v))
            ->when($request->cari,   fn ($q, $v) => $q->where('nama_kegiatan', 'like', "%{$v}%"))
            ->latest('tanggal')
            ->paginate(15)
            ->withQueryString();

        return view('admin.jadwal.riwayat', [
            'jadwal'     => $jadwal,
            'jenis_list' => \App\Models\JenisKegiatan::where('aktif', true)->get(),
        ]);
    }

    public function restore($id)
    {
        Jadwal::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Jadwal berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Jadwal::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Jadwal dihapus permanen.');
    }

    public function create()
    {
        return view('admin.jadwal.create', [
            'jenis_list' => JenisKegiatan::where('aktif', true)->get(),
            'shifts'     => Shift::all(),
            'lokasi'     => Lokasi::all(),
        ]);
    }

    public function store(StoreJadwalRequest $request)
    {
        $data = $request->validated();
        $data['dibuat_oleh'] = Auth::id();
        $jadwal = Jadwal::create($data);

        return redirect()->route('admin.jadwal.show', $jadwal)
            ->with('success', 'Jadwal berhasil dibuat!');
    }

    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['jenisKegiatan', 'shift', 'lokasi', 'penugasan.petugas', 'laporan.petugas']);
        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        return view('admin.jadwal.edit', [
            'jadwal'     => $jadwal,
            'jenis_list' => JenisKegiatan::where('aktif', true)->get(),
            'shifts'     => Shift::all(),
            'lokasi'     => Lokasi::all(),
        ]);
    }

    public function update(StoreJadwalRequest $request, Jadwal $jadwal)
    {
        $jadwal->update($request->validated());
        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        // Soft delete — data masuk riwayat
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal dipindahkan ke riwayat.');
    }

    public function updateStatus(Request $request, Jadwal $jadwal)
    {
        $request->validate(['status' => ['required', 'in:aktif,selesai,dibatalkan']]);
        $jadwal->update(['status' => $request->status]);
        return back()->with('success', 'Status jadwal diperbarui.');
    }
}
