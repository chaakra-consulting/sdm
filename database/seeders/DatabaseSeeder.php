<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AbsensiSeeder::class,
            PerusahaanSeeder::class,
            KeteranganAbsensiSeeder::class,
            StatusPekerjaanSeeder::class,
            TipeTaskSeeder::class,
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
