<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'nama' => 'Pagi',
                'jam_mulai' => '06:00',
                'jam_selesai' => '14:00',
                'keterangan' => 'Shift pagi',
            ],
            [
                'nama' => 'Siang',
                'jam_mulai' => '14:00',
                'jam_selesai' => '22:00',
                'keterangan' => 'Shift siang',
            ],
            [
                'nama' => 'Malam',
                'jam_mulai' => '22:00',
                'jam_selesai' => '06:00',
                'keterangan' => 'Shift malam',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}