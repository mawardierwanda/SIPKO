<?php

namespace Database\Seeders;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Ahmad Dahlan, S.Sos',
            'username' => 'admin',
            'email' => 'admin@satpolpp.ketapang.go.id',
            'password' => Hash::make('Admin@2026'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $list = [
            [
                'nama' => 'Rudi Prasetya, S.Sos',
                'nip' => '19850621201001001',
                'jabatan' => 'Kasi Operasi',
                'pangkat' => 'Penata Muda Tk.I',
                'satuan' => 'Tim Bravo',
                'no_hp' => '08131234001',
                'username' => 'rudi.prasetya',
            ],
            [
                'nama' => 'Budi Santoso, SH',
                'nip' => '19870312201101002',
                'jabatan' => 'Staf Operasi',
                'pangkat' => 'Pengatur',
                'satuan' => 'Tim Alpha',
                'no_hp' => '08131234002',
                'username' => 'budi.santoso',
            ],
            [
                'nama' => 'Siti Aminah',
                'nip' => '19901205201201003',
                'jabatan' => 'Staf Admin',
                'pangkat' => 'Pengatur Muda',
                'satuan' => 'Tim Charlie',
                'no_hp' => '08131234003',
                'username' => 'siti.aminah',
            ],
            [
                'nama' => 'Darmawan',
                'nip' => '19880820201301004',
                'jabatan' => 'Staf Operasi',
                'pangkat' => 'Pengatur Muda',
                'satuan' => 'Tim Alpha',
                'no_hp' => '08131234004',
                'username' => 'darmawan',
            ],
        ];

        foreach ($list as $petugas) {
            $user = User::create([
                'name' => $petugas['nama'],
                'username' => $petugas['username'],
                'email' => $petugas['username'] . '@satpolpp.ketapang.go.id',
                'password' => Hash::make('Petugas@2026'),
                'role' => 'petugas',
                'is_active' => true,
            ]);

            Petugas::create([
                'user_id' => $user->id,
                'nip' => $petugas['nip'],
                'nama' => $petugas['nama'],
                'jabatan' => $petugas['jabatan'],
                'pangkat' => $petugas['pangkat'],
                'satuan' => $petugas['satuan'],
                'no_hp' => $petugas['no_hp'],
                'status' => 'aktif',
            ]);
        }
    }
}
