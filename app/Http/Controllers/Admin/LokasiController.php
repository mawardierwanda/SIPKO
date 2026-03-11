<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller {
    public function index() { return view('admin.lokasi.index',['lokasi'=>Lokasi::withCount('jadwal')->paginate(20)]); }
    public function store(Request $r) {
        $r->validate(['nama'=>'required|string|max:255','alamat'=>'nullable|string','latitude'=>'nullable|numeric','longitude'=>'nullable|numeric','keterangan'=>'nullable|string']);
        Lokasi::create($r->all());
        return back()->with('success','Lokasi ditambahkan!');
    }
    public function update(Request $r, Lokasi $lokasi) {
        $r->validate(['nama'=>'required|string|max:255']);
        $lokasi->update($r->all());
        return back()->with('success','Lokasi diperbarui.');
    }
    public function destroy(Lokasi $lokasi) {
        if ($lokasi->jadwal()->exists()) return back()->with('error','Lokasi masih digunakan di jadwal.');
        $lokasi->delete();
        return back()->with('success','Lokasi dihapus.');
    }
}
