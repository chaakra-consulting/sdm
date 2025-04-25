<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusPengerjaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusPengerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusPengerjaan::crete([
            'nama' => 'Belum',
            'slug' => 'belum',
        ]);
        StatusPengerjaan::crete([
            'nama' => 'Proses',
            'slug' => 'proses',
        ]);
        StatusPengerjaan::crete([
            'nama' => 'Selesai',
            'slug' => 'selesai',
        ]);
    }
}
