<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador (usa tu email personal aquí)
        User::create([
            'name' => 'Cristian Cartes',
            'email' => 'cristiancartesa@gmail.com', // 👈 Pon tu email aquí
            'password' => Hash::make('123456'),
            'user_type' => 'sugar_daddy', // Técnicamente no importa para admin
            'gender' => 'male',
            'role' => 'admin', // 🔥 Este es el importante
            'birth_date' => '1985-05-15',
            'city' => 'Santiago',
            'bio' => 'Administrador de la plataforma Big-dad',
            'is_verified' => true,
            'is_premium' => true,
            'email_verified_at' => now(),
        ]);

        // // Sugar Daddy 1
        // User::create([
        //     'name' => 'Roberto Silva',
        //     'email' => 'roberto.silva@example.com',
        //     'password' => Hash::make('password123'),
        //     'user_type' => 'sugar_daddy',
        //     'gender' => 'male',
        //     'role' => 'user',
        //     'birth_date' => '1978-03-20',
        //     'city' => 'Santiago',
        //     'bio' => 'Empresario exitoso, CEO de una startup tecnológica. Me gusta viajar, el arte y las cenas elegantes. Busco conexiones auténticas con alguien inteligente y ambiciosa.',
        //     'is_verified' => true,
        //     'is_premium' => true,
        //     'email_verified_at' => now(),
        // ]);

        // // Sugar Daddy 2
        // User::create([
        //     'name' => 'Carlos Mendoza',
        //     'email' => 'carlos.mendoza@example.com',
        //     'password' => Hash::make('password123'),
        //     'user_type' => 'sugar_daddy',
        //     'gender' => 'male',
        //     'role' => 'user',
        //     'birth_date' => '1985-11-08',
        //     'city' => 'Viña del Mar',
        //     'bio' => 'Médico especialista, amante de los viajes y la buena vida. Disfruto del golf, la gastronomía y las conversaciones interesantes.',
        //     'is_verified' => true,
        //     'is_premium' => false,
        //     'email_verified_at' => now(),
        // ]);

        // // Sugar Daddy 3
        // User::create([
        //     'name' => 'Alejandro Torres',
        //     'email' => 'alejandro.torres@example.com',
        //     'password' => Hash::make('password123'),
        //     'user_type' => 'sugar_daddy',
        //     'gender' => 'male',
        //     'role' => 'user',
        //     'birth_date' => '1982-07-12',
        //     'city' => 'Santiago',
        //     'bio' => 'Inversionista y consultor financiero. Apasionado por el arte, los vinos y las experiencias únicas. Busco alguien con quien compartir aventuras.',
        //     'is_verified' => false,
        //     'is_premium' => true,
        //     'email_verified_at' => now(),
        // ]);

        // // Sugar Baby 1
        // User::create([
        //     'name' => 'Sofia Martinez',
        //     'email' => 'sofia.martinez@example.com',
        //     'password' => Hash::make('password123'),
        //     'user_type' => 'sugar_baby',
        //     'gender' => 'female',
        //     'role' => 'user',
        //     'birth_date' => '1999-02-14',
        //     'city' => 'Santiago',
        //     'bio' => 'Estudiante de Arte en la Universidad de Chile. Me encanta la fotografía, los museos y descubrir lugares nuevos. Busco alguien que valore la creatividad y la pasión.',
        //     'is_verified' => true,
        //     'is_premium' => false,
        //     'email_verified_at' => now(),
        // ]);

        // // Sugar Baby 2
        // User::create([
        //     'name' => 'Valentina Rodriguez',
        //     'email' => 'valentina.rodriguez@example.com',
        //     'password' => Hash::make('password123'),
        //     'user_type' => 'sugar_baby',
        //     'gender' => 'female',
        //     'role' => 'user',
        //     'birth_date' => '2001-09-22',
        //     'city' => 'Valparaíso',
        //     'bio' => 'Model e influencer. Apasionada por la moda, los viajes y las experiencias de lujo. Amo la vida y busco alguien que me inspire a crecer.',
        //     'is_verified' => true,
        //     'is_premium' => true,
        //     'email_verified_at' => now(),
        // ]);

        // // Sugar Baby 3
        // User::create([
        //     'name' => 'Andrea López',
        //     'email' => 'andrea.lopez@example.com',
        //     'password' => Hash::make('password123'),
        //     'user_type' => 'sugar_baby',
        //     'gender' => 'female',
        //     'role' => 'user',
        //     'birth_date' => '2000-06-18',
        //     'city' => 'Concepción',
        //     'bio' => 'Estudiante de Psicología y bailarina. Me gusta la música, los libros y las conversaciones profundas. Busco conexiones genuinas y experiencias enriquecedoras.',
        //     'is_verified' => false,
        //     'is_premium' => false,
        //     'email_verified_at' => now(),
        // ]);

        // // Sugar Baby 4
        // User::create([
        //     'name' => 'María Fernanda Castillo',
        //     'email' => 'mariafernanda.castillo@example.com',
        //     'password' => Hash::make('password123'),
        //     'user_type' => 'sugar_baby',
        //     'gender' => 'female',
        //     'role' => 'user',
        //     'birth_date' => '1998-12-03',
        //     'city' => 'Santiago',
        //     'bio' => 'Emprendedora digital y estudiante de Marketing. Amo los desafíos, los viajes y las experiencias que marquen la diferencia.',
        //     'is_verified' => true,
        //     'is_premium' => false,
        //     'email_verified_at' => now(),
        // ]);
    }
}
