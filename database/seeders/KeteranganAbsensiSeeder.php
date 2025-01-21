<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Divisi;
use App\Models\KeteranganAbsensi;
use App\Models\Role;
use App\Models\SubJabatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class KeteranganAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KeteranganAbsensi::create([
            'nama' => 'WFO',
            'slug' => 'wfo',
        ]);

        KeteranganAbsensi::create([
            'nama' => 'WFH',
            'slug' => 'wfh',
        ]);

        KeteranganAbsensi::create([
            'nama' => 'Sakit',
            'slug' => 'sakit',
        ]);

        KeteranganAbsensi::create([
            'nama' => 'Ijin',
            'slug' => 'ijin',
        ]);

        KeteranganAbsensi::create([
            'nama' => 'Lembur',
            'slug' => 'lembur',
        ]);

        KeteranganAbsensi::create([
            'nama' => 'Alpa',
            'slug' => 'alpa',
        ]);

        KeteranganAbsensi::create([
            'nama' => 'Cuti',
            'slug' => 'cuti',
        ]);
    }
}
