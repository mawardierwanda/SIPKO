<?php

namespace Database\Seeders;

use App\Models\JenisKegiatan;
use Illuminate\Database\Seeder;

class JenisKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $jenisKegiatan = [
            [
                'nama' => 'Patroli',
                'kode' => 'PAT',
                'deskripsi' => 'Patroli wilayah rutin',
                'aktif' => true,
            ],
            [
                'nama' => 'Razia',
                'kode' => 'RAZ',
                'deskripsi' => 'Razia pelanggaran Perda',
                'aktif' => true,
            ],
            [
                'nama' => 'Piket',
                'kode' => 'PIK',
                'deskripsi' => 'Piket jaga kantor',
                'aktif' => true,
            ],
            [
                'nama' => 'Pengamanan Acara',
                'kode' => 'PGA',
                'deskripsi' => 'Pengamanan acara',
                'aktif' => true,
            ],
            [
                'nama' => 'Penertiban',
                'kode' => 'PNT',
                'deskripsi' => 'Penertiban PKL/bangunan',
                'aktif' => true,
            ],
        ];

        foreach ($jenisKegiatan as $item) {
            JenisKegiatan::create($item);
        }
    }
}
