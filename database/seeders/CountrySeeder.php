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
            ['name' => 'Argentina',            'iso_code' => 'AR', 'slug' => 'argentina'],
            ['name' => 'Bolivia',               'iso_code' => 'BO', 'slug' => 'bolivia'],
            ['name' => 'Chile',                 'iso_code' => 'CL', 'slug' => 'chile'],
            ['name' => 'Colombia',              'iso_code' => 'CO', 'slug' => 'colombia'],
            ['name' => 'Costa Rica',            'iso_code' => 'CR', 'slug' => 'costa-rica'],
            ['name' => 'Cuba',                  'iso_code' => 'CU', 'slug' => 'cuba'],
            ['name' => 'República Dominicana',  'iso_code' => 'DO', 'slug' => 'republica-dominicana'],
            ['name' => 'Ecuador',               'iso_code' => 'EC', 'slug' => 'ecuador'],
            ['name' => 'El Salvador',           'iso_code' => 'SV', 'slug' => 'el-salvador'],
            ['name' => 'Guatemala',             'iso_code' => 'GT', 'slug' => 'guatemala'],
            ['name' => 'Honduras',              'iso_code' => 'HN', 'slug' => 'honduras'],
            ['name' => 'México',                'iso_code' => 'MX', 'slug' => 'mexico'],
            ['name' => 'Nicaragua',             'iso_code' => 'NI', 'slug' => 'nicaragua'],
            ['name' => 'Panamá',                'iso_code' => 'PA', 'slug' => 'panama'],
            ['name' => 'Paraguay',              'iso_code' => 'PY', 'slug' => 'paraguay'],
            ['name' => 'Perú',                  'iso_code' => 'PE', 'slug' => 'peru'],
            ['name' => 'Puerto Rico',           'iso_code' => 'PR', 'slug' => 'puerto-rico'],
            ['name' => 'Uruguay',               'iso_code' => 'UY', 'slug' => 'uruguay'],
            ['name' => 'Venezuela',             'iso_code' => 'VE', 'slug' => 'venezuela'],
            ['name' => 'España',                'iso_code' => 'ES', 'slug' => 'espana'],
            ['name' => 'Estados Unidos',        'iso_code' => 'US', 'slug' => 'estados-unidos'],
        ];

        foreach ($countries as $country) {
            \App\Models\Country::firstOrCreate(
                ['iso_code' => $country['iso_code']],
                ['name' => $country['name'], 'slug' => $country['slug'], 'is_active' => true]
            );
        }
    }
}
