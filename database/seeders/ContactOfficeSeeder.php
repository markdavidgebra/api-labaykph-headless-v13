<?php

namespace Database\Seeders;

use App\Models\ContactOffice;
use Illuminate\Database\Seeder;

class ContactOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            [
                'name' => 'Makati Central Office',
                'address' => "Unit 507, 5th floor, Greenbelt Mansion,\n106 Perea St., Legazpi Village, Makati City 1229",
                'landline' => '(02) 84 426 857',
                'globe' => '0967-415-8601',
                'smart' => '0961-297-7633',
                'sort_order' => 1,
            ],
            [
                'name' => 'Cotabato City',
                'address' => "Door 10, 2nd floor, Dimacisil Building\nSinsuat Avenue Rosary Heights 4, Cotabato City",
                'landline' => '(+63) 64 557 3539',
                'globe' => '+63 967 415 7395',
                'smart' => '+63 949 828 3109',
                'sort_order' => 2,
            ],
            [
                'name' => 'Pagadian City',
                'address' => "Unit No. 87, 2nd floor, C3 Mall, Rizal Ave.,\nPob. Santiago, Pagadian City",
                'landline' => '(+63) 62 945 5078',
                'globe' => '+63 916 295 0067',
                'smart' => '+63 929 370 1051',
                'sort_order' => 3,
            ],
        ];

        foreach ($offices as $office) {
            ContactOffice::updateOrCreate(
                ['name' => $office['name']],
                $office
            );
        }
    }
}
