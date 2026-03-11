<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        $lokasi = [
            [
                'nama' => 'Jl. Ahmad Yani',
                'alamat' => 'Jl. Ahmad Yani, Ketapang',
                'latitude' => -1.8388,
                'longitude' => 110.0111,
            ],
            [
                'nama' => 'Pasar Ketapang',
                'alamat' => 'Pasar Sentral Ketapang',
                'latitude' => -1.8432,
                'longitude' => 110.0158,
            ],
            [
                'nama' => 'Jembatan Pawan',
                'alamat' => 'Jembatan Sungai Pawan',
                'latitude' => -1.8501,
                'longitude' => 110.0201,
            ],
            [
                'nama' => 'Kantor Bupati',
                'alamat' => 'Jl. Jend. Sudirman',
                'latitude' => -1.8369,
                'longitude' => 110.0098,
            ],
            [
                'nama' => 'Kec. Mulia Baru',
                'alamat' => 'Kecamatan Mulia Baru',
                'latitude' => -1.8600,
                'longitude' => 110.0300,
            ],
            [
                'nama' => 'Pantai Ketapang',
                'alamat' => 'Kawasan Wisata Pantai',
                'latitude' => -1.8700,
                'longitude' => 109.9800,
            ],
        ];

        foreach ($lokasi as $item) {
            Lokasi::create($item);
        }
    }
}
