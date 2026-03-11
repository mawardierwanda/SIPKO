<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\JenisKegiatan;
use Illuminate\Http\Request;

class JenisKegiatanController extends Controller {
    public function index() { return view('admin.jenis-kegiatan.index',['jenis'=>JenisKegiatan::withCount('jadwal')->get()]); }
    public function store(Request $r) {
        $r->validate(['nama'=>'required|string|max:100|unique:jenis_kegiatan,nama','kode'=>'nullable|string|max:10','deskripsi'=>'nullable|string']);
        JenisKegiatan::create($r->all());
        return back()->with('success','Jenis kegiatan ditambahkan!');
    }
    public function update(Request $r, JenisKegiatan $jenisKegiatan) {
        $r->validate(['nama'=>'required|string|max:100|unique:jenis_kegiatan,nama,'.$jenisKegiatan->id]);
        $jenisKegiatan->update([
            'nama'      => $r->nama,
            'kode'      => $r->kode,
            'deskripsi' => $r->deskripsi,
            'aktif'     => $r->boolean('aktif'),
        ]);
        return back()->with('success','Jenis kegiatan diperbarui.');
    }
    public function destroy(JenisKegiatan $jenisKegiatan) {
        if ($jenisKegiatan->jadwal()->exists()) return back()->with('error','Jenis kegiatan masih digunakan.');
        $jenisKegiatan->delete();
        return back()->with('success','Jenis kegiatan dihapus.');
    }
}
