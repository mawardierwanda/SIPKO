<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class StorePetugasRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        $id = $this->route('petugas')?->id;
        $isEdit = !is_null($id);
        return [
            'nip'      => 'required|string|max:30|unique:petugas,nip'.($id ? ','.$id : ''),
            'nama'     => 'required|string|max:255',
            'jabatan'  => 'required|string|max:100',
            'pangkat'  => 'nullable|string|max:100',
            'satuan'   => 'nullable|string|max:50',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'   => 'required|in:aktif,nonaktif',
            'username' => $isEdit ? 'nullable' : 'required|string|max:100|unique:users,username',
            'email'    => $isEdit ? 'nullable|email' : 'required|email|unique:users,email',
            'password' => $isEdit ? 'nullable|string|min:8' : 'required|string|min:8',
        ];
    }
    public function messages(): array {
        return [
            'nip.required'   => 'NIP wajib diisi.',
            'nip.unique'     => 'NIP sudah terdaftar.',
            'nama.required'  => 'Nama lengkap wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
            'password.min'   => 'Password minimal 8 karakter.',
        ];
    }
}
