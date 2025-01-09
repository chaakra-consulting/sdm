<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Super Admin',
        ]);
        Role::create([
            'name' => 'Admin',
        ]);
        Role::create([
            'name' => 'Karyawan',
        ]);

        User::create([
            'name' => 'Ahmad Maulana Subandrio',
            'email' => 'ahmadrio@gmail.com',
            'password' => Hash::make('superadmin'),
            'role_id' => 1
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role_id' => 2
        ]);
        User::create([
            'name' => 'karyawan',
            'email' => 'karyawan@gmail.com',
            'password' => Hash::make('karyawan123'),
            'role_id' => 3
        ]);
    }
}
