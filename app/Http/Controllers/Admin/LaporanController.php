<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Jadwal,Laporan};
use Illuminate\Http\Request;

class LaporanController extends Controller {
    public function index(Request $r) {
        $laporan = Laporan::with(['jadwal.jenisKegiatan','jadwal.shift','petugas'])
            ->when($r->kondisi, fn($q,$k)=>$q->where('kondisi',$k))
            ->when($r->dari,    fn($q,$d)=>$q->whereHas('jadwal',fn($j)=>$j->whereDate('tanggal','>=',$d)))
            ->when($r->sampai,  fn($q,$s)=>$q->whereHas('jadwal',fn($j)=>$j->whereDate('tanggal','<=',$s)))
            ->latest('waktu_laporan')->paginate(20)->withQueryString();
        return view('admin.laporan.index',compact('laporan'));
    }

    public function belum() {
        $jadwal = Jadwal::belumLaporan()->with(['jenisKegiatan','shift','lokasi','penugasan.petugas'])->latest('tanggal')->get();
        return view('admin.laporan.belum',compact('jadwal'));
    }

    public function show(Laporan $laporan) {
        $laporan->load(['jadwal.jenisKegiatan','jadwal.shift','jadwal.lokasi','petugas']);
        return view('admin.laporan.show',compact('laporan'));
    }

    public function review(Request $r, Laporan $laporan) {
        $r->validate(['status'=>'required|in:diterima,terlambat,review','catatan_admin'=>'nullable|string|max:500']);
        $laporan->update($r->only('status','catatan_admin'));
        return back()->with('success','Status laporan diperbarui.');
    }

    public function update(Request $r, Laporan $laporan) {
        $r->validate([
            'kondisi'       => 'required|in:kondusif,tidak kondusif,perlu tindak lanjut',
            'status'        => 'required|in:diterima,terlambat,pending',
            'catatan_admin' => 'nullable|string|max:500',
        ]);
        $laporan->update($r->only('kondisi','status','catatan_admin'));
        return back()->with('success','Laporan berhasil diperbarui.');
    }
}
