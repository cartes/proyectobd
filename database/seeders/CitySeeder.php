<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'AR' => ['Buenos Aires', 'Córdoba', 'Rosario', 'Mendoza', 'La Plata', 'Mar del Plata', 'Tucumán', 'Salta', 'Santa Fe', 'Corrientes'],
            'BO' => ['La Paz', 'Santa Cruz de la Sierra', 'Cochabamba', 'Sucre', 'Oruro', 'Potosí', 'Tarija'],
            'CL' => ['Santiago', 'Valparaíso', 'Concepción', 'La Serena', 'Antofagasta', 'Temuco', 'Rancagua', 'Talca', 'Arica'],
            'CO' => ['Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena', 'Bucaramanga', 'Pereira', 'Santa Marta', 'Manizales', 'Cúcuta'],
            'CR' => ['San José', 'Cartago', 'Heredia', 'Alajuela', 'Liberia'],
            'CU' => ['La Habana', 'Santiago de Cuba', 'Holguín', 'Camagüey', 'Santa Clara', 'Guantánamo'],
            'DO' => ['Santo Domingo', 'Santiago de los Caballeros', 'La Romana', 'San Pedro de Macorís', 'Puerto Plata'],
            'EC' => ['Guayaquil', 'Quito', 'Cuenca', 'Santo Domingo', 'Machala', 'Manta', 'Ambato'],
            'SV' => ['San Salvador', 'Soyapango', 'Santa Ana', 'San Miguel'],
            'GT' => ['Ciudad de Guatemala', 'Mixco', 'Villa Nueva', 'Quetzaltenango'],
            'HN' => ['Tegucigalpa', 'San Pedro Sula', 'La Ceiba', 'El Progreso'],
            'MX' => ['Ciudad de México', 'Guadalajara', 'Monterrey', 'Puebla', 'Tijuana', 'León', 'Ciudad Juárez', 'Mérida', 'Cancún', 'Querétaro', 'San Luis Potosí', 'Aguascalientes', 'Culiacán'],
            'NI' => ['Managua', 'León', 'Masaya', 'Matagalpa', 'Chinandega'],
            'PA' => ['Ciudad de Panamá', 'Colón', 'David', 'Santiago'],
            'PY' => ['Asunción', 'Ciudad del Este', 'San Lorenzo', 'Luque', 'Capiatá'],
            'PE' => ['Lima', 'Arequipa', 'Trujillo', 'Chiclayo', 'Iquitos', 'Piura', 'Cusco', 'Tacna'],
            'PR' => ['San Juan', 'Bayamón', 'Carolina', 'Ponce', 'Caguas'],
            'UY' => ['Montevideo', 'Salto', 'Paysandú', 'Las Piedras', 'Rivera'],
            'VE' => ['Caracas', 'Maracaibo', 'Valencia', 'Barquisimeto', 'Ciudad Guayana', 'San Cristóbal', 'Maturín'],
            'ES' => ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia', 'Palma', 'Las Palmas de Gran Canaria', 'Bilbao', 'Alicante', 'Córdoba', 'Valladolid', 'Vigo'],
            'US' => ['Nueva York', 'Los Ángeles', 'Miami', 'Chicago', 'Houston', 'Phoenix', 'Dallas', 'San Antonio', 'San Diego', 'San Francisco', 'Las Vegas', 'Orlando', 'Atlanta', 'Boston', 'Seattle'],
        ];

        foreach ($data as $isoCode => $cities) {
            $country = Country::where('iso_code', $isoCode)->first();

            if (! $country) {
                continue;
            }

            foreach ($cities as $name) {
                $slug = Str::slug($name);
                City::firstOrCreate(
                    ['country_id' => $country->id, 'slug' => $slug],
                    ['name' => $name, 'is_active' => true]
                );
            }
        }
    }
}
