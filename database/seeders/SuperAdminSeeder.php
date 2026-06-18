<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuperAdmin::updateOrCreate(
            ['email' => 'markdavidgebra@gmail.com'],
            [
                'name' => 'Super Admin',
                'email' => 'markdavidgebra@gmail.com',
                'password' => Hash::make('PraiseBeToJesus07'),
                'token' => '',
            ]
        );
    }
}
