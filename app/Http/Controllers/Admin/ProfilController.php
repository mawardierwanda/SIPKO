<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller {
    public function show() { return view('admin.profil.show',['user'=>auth()->user()]); }
    public function update(Request $r) {
        $r->validate(['name'=>'required|string|max:255','email'=>'required|email|unique:users,email,'.auth()->id()]);
        auth()->user()->update($r->only('name','email'));
        return back()->with('success','Profil diperbarui.');
    }
    public function updatePassword(Request $r) {
        $r->validate(['current_password'=>'required|current_password','password'=>'required|string|min:8|confirmed']);
        auth()->user()->update(['password'=>bcrypt($r->password)]);
        return back()->with('success','Password berhasil diubah.');
    }
}
