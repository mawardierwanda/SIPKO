<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class StorePenugasanRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules(): array {
        return [
            'jadwal_id'      => 'required|exists:jadwal,id',
            'koordinator_id' => 'required|exists:petugas,id',
            'anggota'        => 'nullable|array',
            'anggota.*'      => 'exists:petugas,id',
        ];
    }
}
