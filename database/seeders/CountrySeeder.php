<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Argentina', 'iso_code' => 'AR'],
            ['name' => 'Bolivia', 'iso_code' => 'BO'],
            ['name' => 'Chile', 'iso_code' => 'CL'],
            ['name' => 'Colombia', 'iso_code' => 'CO'],
            ['name' => 'Costa Rica', 'iso_code' => 'CR'],
            ['name' => 'Cuba', 'iso_code' => 'CU'],
            ['name' => 'República Dominicana', 'iso_code' => 'DO'],
            ['name' => 'Ecuador', 'iso_code' => 'EC'],
            ['name' => 'El Salvador', 'iso_code' => 'SV'],
            ['name' => 'Guatemala', 'iso_code' => 'GT'],
            ['name' => 'Honduras', 'iso_code' => 'HN'],
            ['name' => 'México', 'iso_code' => 'MX'],
            ['name' => 'Nicaragua', 'iso_code' => 'NI'],
            ['name' => 'Panamá', 'iso_code' => 'PA'],
            ['name' => 'Paraguay', 'iso_code' => 'PY'],
            ['name' => 'Perú', 'iso_code' => 'PE'],
            ['name' => 'Puerto Rico', 'iso_code' => 'PR'],
            ['name' => 'Uruguay', 'iso_code' => 'UY'],
            ['name' => 'Venezuela', 'iso_code' => 'VE'],
            ['name' => 'España', 'iso_code' => 'ES'],
            ['name' => 'Estados Unidos', 'iso_code' => 'US'],
        ];

        foreach ($countries as $country) {
            \App\Models\Country::firstOrCreate(
                ['iso_code' => $country['iso_code']],
                ['name' => $country['name'], 'is_active' => true]
            );
        }
    }
}
