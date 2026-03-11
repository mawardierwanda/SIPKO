<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Jadwal,Shift};

class StatusController extends Controller {
    public function index() {
        $today  = now()->toDateString();
        $jadwal = Jadwal::with(['jenisKegiatan','shift','lokasi','laporan','penugasan.petugas'])->whereDate('tanggal',$today)->orderBy('shift_id')->get();
        $shifts = Shift::all();
        return view('admin.status.index',compact('jadwal','shifts','today'));
    }
}
