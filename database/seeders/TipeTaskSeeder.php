<?php

namespace Database\Seeders;

use App\Models\TipeTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipeTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipeTask::create([
            'nama_tipe' => 'Task Project',
            'slug' => 'task-project',
        ]);
        TipeTask::create([
            'nama_tipe' => 'Task Wajib',
            'slug' => 'task-wajib',
        ]);
    }
}
