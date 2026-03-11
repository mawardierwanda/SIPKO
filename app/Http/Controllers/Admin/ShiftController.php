<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller {
    public function index() { return view('admin.shift.index',['shifts'=>Shift::withCount('jadwal')->get()]); }
    public function store(Request $r) {
        $r->validate(['nama'=>'required|string|max:50','jam_mulai'=>'required','jam_selesai'=>'required','keterangan'=>'nullable|string']);
        Shift::create($r->all());
        return back()->with('success','Shift berhasil ditambahkan!');
    }
    public function update(Request $r, Shift $shift) {
        $r->validate(['nama'=>'required|string|max:50','jam_mulai'=>'required','jam_selesai'=>'required']);
        $shift->update($r->all());
        return back()->with('success','Shift diperbarui.');
    }
    public function destroy(Shift $shift) {
        if ($shift->jadwal()->exists()) return back()->with('error','Shift masih digunakan di jadwal.');
        $shift->delete();
        return back()->with('success','Shift dihapus.');
    }
}
