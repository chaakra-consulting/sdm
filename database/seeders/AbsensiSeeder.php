<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Absensi::create([
            'hari' => 'senin',
            'waktu_masuk' => '08:00:00',
            'waktu_pulang' => '17:00:00',
            'batas_waktu_terlambat' => '08:15:00',
            'denda_terlambat' => 10000,
            'overtime' => 25000,
        ]);
        Absensi::create([
            'hari' => 'selasa',
            'waktu_masuk' => '08:00:00',
            'waktu_pulang' => '17:00:00',
            'batas_waktu_terlambat' => '08:15:00',
            'denda_terlambat' => 10000,
            'overtime' => 25000,
        ]);
        Absensi::create([
            'hari' => 'rabu',
            'waktu_masuk' => '08:00:00',
            'waktu_pulang' => '17:00:00',
            'batas_waktu_terlambat' => '08:15:00',
            'denda_terlambat' => 10000,
            'overtime' => 25000,
        ]);
        Absensi::create([
            'hari' => 'kamis',
            'waktu_masuk' => '08:00:00',
            'waktu_pulang' => '17:00:00',
            'batas_waktu_terlambat' => '08:15:00',
            'denda_terlambat' => 10000,
            'overtime' => 25000,
        ]);
        Absensi::create([
            'hari' => 'jumat',
            'waktu_masuk' => '08:00:00',
            'waktu_pulang' => '17:00:00',
            'batas_waktu_terlambat' => '08:15:00',
            'denda_terlambat' => 10000,
            'overtime' => 25000,
        ]);
        Absensi::create([
            'hari' => 'sabtu',
            'waktu_masuk' => '08:00:00',
            'waktu_pulang' => '13:00:00',
            'batas_waktu_terlambat' => '08:15:00',
            'denda_terlambat' => 10000,
            'overtime' => 25000,
        ]);
        Absensi::create([
            'hari' => 'minggu',
            'overtime' => 25000,
            'is_libur' => 1,
        ]);
    }
}
