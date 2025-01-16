<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perusahaan::create([
            'nama_perusahaan' => 'PT Adi Graha Wira Jatim',
        ]);
        Perusahaan::create([
            'nama_perusahaan' => 'PT BPR Jatim',
        ]);
        Perusahaan::create([
            'nama_perusahaan' => 'PT Karet Ngagel Surabaya Wira Jatim',
        ]);
    }
}
