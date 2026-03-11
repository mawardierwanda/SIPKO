<?php
namespace App\Http\Requests\Petugas;
use Illuminate\Foundation\Http\FormRequest;
class StoreLaporanRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules(): array {
        return [
            'isi_laporan'        => 'required|string|min:20|max:5000',
            'kondisi'            => 'required|in:kondusif,tidak kondusif,perlu tindak lanjut',
            'jumlah_pelanggaran' => 'nullable|integer|min:0',
            'foto'               => 'nullable|array|max:5',
            'foto.*'             => 'image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
    public function messages(): array {
        return ['isi_laporan.min'=>'Isi laporan minimal 20 karakter.','foto.max'=>'Maksimal 5 foto.'];
    }
}
