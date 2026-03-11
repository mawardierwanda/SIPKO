<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePetugasRequest;
use App\Models\{Petugas, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};

class PetugasController extends Controller {
    public function index(Request $r) {
        $q = Petugas::with('user')
            ->when($r->search, fn($q,$s)=>$q->where('nama','like',"%$s%")->orWhere('nip','like',"%$s%"))
            ->when($r->satuan, fn($q,$s)=>$q->where('satuan',$s))
            ->when($r->status, fn($q,$s)=>$q->where('status',$s));
        return view('admin.petugas.index',['petugas'=>$q->paginate(15)->withQueryString()]);
    }

    public function create() { return view('admin.petugas.create'); }

    public function store(StorePetugasRequest $r) {
        DB::transaction(function() use ($r) {
            $user = User::create([
                'name'      => $r->nama,
                'username'  => $r->username,
                'email'     => $r->email,
                'password'  => bcrypt($r->password),
                'role'      => 'petugas',
                'is_active' => true,
            ]);
            $data = $r->only(['nip','nama','jabatan','pangkat','satuan','no_hp','alamat','status']);
            if ($r->hasFile('foto')) $data['foto'] = $r->file('foto')->store('petugas','public');
            $data['user_id'] = $user->id;
            Petugas::create($data);
        });
        return redirect()->route('admin.petugas.index')->with('success','Petugas berhasil ditambahkan!');
    }

    public function edit(Petugas $petugas) { return view('admin.petugas.edit', compact('petugas')); }

    public function update(StorePetugasRequest $r, Petugas $petugas) {
        $data = $r->only(['nip','nama','jabatan','pangkat','satuan','no_hp','alamat','status']);
        if ($r->hasFile('foto')) {
            if ($petugas->foto) Storage::disk('public')->delete($petugas->foto);
            $data['foto'] = $r->file('foto')->store('petugas','public');
        }
        $petugas->update($data);
        $petugas->user->update(['name'=>$r->nama]);
        return redirect()->route('admin.petugas.index')->with('success','Data petugas diperbarui!');
    }

    public function destroy(Petugas $petugas) {
        if ($petugas->foto) Storage::disk('public')->delete($petugas->foto);
        $petugas->user->delete();
        return redirect()->route('admin.petugas.index')->with('success','Petugas dihapus.');
    }

    public function toggleStatus(Petugas $petugas) {
        $newStatus = $petugas->status === 'aktif' ? 'nonaktif' : 'aktif';
        $petugas->update(['status' => $newStatus]);
        $petugas->user->update(['is_active' => $newStatus === 'aktif']);
        return back()->with('success','Status petugas diubah.');
    }
}
