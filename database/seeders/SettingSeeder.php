<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing records
        Setting::truncate();

        // Seed new data
        Setting::create([
            'app_name' => 'LUCKNOW CONSTRUCTION PRIVATE LIMITED',
            'email' => 'lcpl.original@gmail.com, Info@lucknowconstruction.in',
            'whatsapp' => '8853639355',
            'contact' => '8853639355',
            'cin_no' => 'U41000UP2026PTC242352',
            'pan' => 'AAGCL6990H',
            'tan' => 'LKNL07678G',
            'cgst' => '9',
            'sgst' => '9',
            'address' => '19A/336, Vrindavan, Sector-19A, Vrinda Van Colony, Lucknow, Lucknow- 226029, Uttar Pradesh',
            'header_image' => "assets/front/images/header.jpg",
            'is_fresh' => 1,
        ]);
    }
}
