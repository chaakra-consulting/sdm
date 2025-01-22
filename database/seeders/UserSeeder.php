<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Super Admin',
            'slug' => Str::slug('Super Admin'),
        ]);
        Role::create([
            'name' => 'Admin',
            'slug' => Str::slug('Admin'),
        ]);
        Role::create([
            'name' => 'Karyawan',
            'slug' => Str::slug('Karyawan'),
        ]);
        Role::create([
            'name' => 'Admin SDM',
            'slug' => Str::slug('Admin SDM'),
        ]);
        Role::create([
            'name' => 'Direktur',
            'slug' => Str::slug('Direktur'),
        ]);
        Role::create([
            'name' => 'Manager',
            'slug' => Str::slug('Manager'),
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
        User::create([
            'name' => 'admin sdm',
            'email' => 'sdm@gmail.com',
            'password' => Hash::make('adminsdm'),
            'role_id' => 4
        ]);
        User::create([
            'name' => 'direktur',
            'email' => 'direktur@gmail.com',
            'password' => Hash::make('direktur123'),
            'role_id' => 5
        ]);
        User::create([
            'name' => 'manajer',
            'email' => 'manajer@gmail.com',
            'password' => Hash::make('manajer123'),
            'role_id' => 6
        ]);
    }
}
