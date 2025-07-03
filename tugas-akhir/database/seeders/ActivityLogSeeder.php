<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // ambil user pertama sebagai contoh

        if ($user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'login',
                'description' => 'Seeder: Login berhasil oleh ' . $user->name,
                'created_at' => Carbon::now(),
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'bonus',
                'description' => 'Seeder: Menambahkan bonus melalui seeder',
                'created_at' => Carbon::now()->subDay(),
            ]);
        }
    }
}
