<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cargomas.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Pimpinan
        User::create([
            'name' => 'Pimpinan',
            'email' => 'pimpinan@cargomas.com',
            'password' => Hash::make('pimpinan123'),
            'role' => 'pimpinan',
        ]);

        // Karyawan (10 sample)
        $karyawan = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@cargomas.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'dewi@cargomas.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Hendra Wijaya',
                'email' => 'hendra@cargomas.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Siti Nuraini',
                'email' => 'siti@cargomas.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Agus Purnomo',
                'email' => 'agus@cargomas.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ],
        ];

        foreach ($karyawan as $user) {
            User::create($user);
        }
    }
}
