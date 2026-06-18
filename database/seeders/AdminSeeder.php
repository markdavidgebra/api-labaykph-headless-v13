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
        $obj = new Admin;
        $obj->name = "Morshedul Arefin";
        $obj->email = "admin@labaykph.com";
        $obj->photo = "admin.jpg";
        $obj->password = Hash::make('password');
        $obj->token = "";
        $obj->save();
    }
}
