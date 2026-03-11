<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class StoreJadwalRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules(): array {
        return [
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatan,id',
            'nama_kegiatan'     => 'required|string|max:255',
            'tanggal'           => 'required|date',
            'shift_id'          => 'required|exists:shifts,id',
            'lokasi_id'         => 'required|exists:lokasi,id',
            'satuan'            => 'nullable|string|max:50',
            'keterangan'        => 'nullable|string|max:1000',
        ];
    }
}
