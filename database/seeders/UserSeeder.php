<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Kurban',
                'email' => 'admin@gmail.com',
                'no_hp' => '081234567890',
                'alamat' => 'Kantor Panitia Kurban',
                'role' => 'admin_kurban',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Peserta Kurban',
                'email' => 'peserta@gmail.com',
                'no_hp' => '089876543210',
                'alamat' => 'Jl. Contoh Alamat',
                'role' => 'peserta_kurban',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
