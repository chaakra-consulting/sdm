<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\DataStatusPekerjaan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class StatusPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DataStatusPekerjaan::create([
            'nama_status_pekerjaan' => 'Karyawan Tetap',
            'slug' => 'karyawan-tetap',
        ]);
        DataStatusPekerjaan::create([
            'nama_status_pekerjaan' => 'Karyawan Kontrak',
            'slug' => 'karyawan-kontrak',
        ]);
        DataStatusPekerjaan::create([
            'nama_status_pekerjaan' => 'Freelance',
            'slug' => 'freelance',
        ]);
    }
}
