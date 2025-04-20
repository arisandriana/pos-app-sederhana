<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengguna::create([
            'NamaPengguna' => 'Admin',
            'Email' => 'admin@pos.com',
            'KataSandi' => Hash::make('admin123'),
            'Peran' => 'Admin',
        ]);
    }
}