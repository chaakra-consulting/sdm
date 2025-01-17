<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\DataStatusPekerjaan;
use App\Models\Divisi;
use App\Models\Role;
use App\Models\SubJabatan;
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
            'nama_status_pekerjaan' => 'Freelance',
            'slug' => 'freelance',
        ]);

        DataStatusPekerjaan::create([
            'nama_status_pekerjaan' => 'Magang',
            'slug' => 'magang',
        ]);

        SubJabatan::create([
            'nama_sub_jabatan' => 'Asisten Konsultan Kebijakan Publik',
        ]);

        SubJabatan::create([
            'nama_sub_jabatan' => 'Asisten Konsultan SDM',
        ]);

        SubJabatan::create([
            'nama_sub_jabatan' => 'Sales',
        ]);

        SubJabatan::create([
            'nama_sub_jabatan' => 'Administrasi Umum',
        ]);

        SubJabatan::create([
            'nama_sub_jabatan' => 'Programmer',
        ]);

        Divisi::create([
            'nama_divisi' => 'Divisi Konsultansi',
            'slug' => 'divisi-konsultansi',
        ]);

        Divisi::create([
            'nama_divisi' => 'Divisi SDM & Asesmen',
            'slug' => 'divisi-sdm-asesmen',
        ]);

        Divisi::create([
            'nama_divisi' => 'Divisi Administrasi Umum',
            'slug' => 'divisi-administrasi-umum',
        ]);

        Divisi::create([
            'nama_divisi' => 'Divisi IT',
            'slug' => 'divisi-it',
        ]);
    }
}
