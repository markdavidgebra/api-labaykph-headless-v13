<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@labaykph.com'],
            [
                'name' => 'Morshedul Arefin',
                'role' => 'admin',
                'photo' => 'admin.jpg',
                'password' => Hash::make('password'),
                'token' => '',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'staff@labaykph.com'],
            [
                'name' => 'Staff Admin',
                'role' => 'staff',
                'photo' => 'admin.jpg',
                'password' => Hash::make('password'),
                'token' => '',
            ]
        );
    }
}
