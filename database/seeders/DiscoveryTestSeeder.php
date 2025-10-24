<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProfileDetail;
use App\Models\ProfilePhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DiscoveryTestSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🧹 Limpiando usuarios de prueba anteriores...');

        // ELIMINAR USUARIOS DE PRUEBA ANTERIORES
        User::where('email', 'LIKE', '%@test.com')->each(function ($user) {
            // Eliminar carpeta completa del usuario
            if (Storage::exists("public/profiles/{$user->id}")) {
                Storage::deleteDirectory("public/profiles/{$user->id}");
            }

            // Eliminar fotos de la BD
            $user->photos()->delete();

            // Eliminar perfil detail
            $user->profileDetail?->delete();

            // Eliminar usuario
            $user->delete();
        });

        $this->command->info('✅ Limpieza completada');
        $this->command->info('🚀 Iniciando seeder con fotos...');

        // Crear 10 Sugar Babies
        for ($i = 1; $i <= 10; $i++) {
            $baby = User::create([
                'name' => fake()->firstName() . ' ' . fake()->lastName(),
                'email' => "baby{$i}@test.com",
                'password' => bcrypt('password'),
                'user_type' => 'sugar_baby',
                'gender' => 'female',
                'birth_date' => now()->subYears(rand(20, 30)),
                'city' => ['Santiago', 'Valparaíso', 'Concepción', 'Viña del Mar'][rand(0, 3)],
                'bio' => fake()->paragraph(),
                'is_verified' => true,
                'is_active' => true,
            ]);

            ProfileDetail::create([
                'user_id' => $baby->id,
                'height' => rand(155, 175),
                'body_type' => ['delgado', 'atletico', 'promedio'][rand(0, 2)],
                'personal_style' => ['elegante', 'casual', 'deportivo'][rand(0, 2)],
                'interests' => ['viajes', 'fitness', 'arte', 'moda'],
            ]);

            $this->command->info("💎 Creando Sugar Baby: {$baby->name}");

            // Crear carpeta para este usuario
            $userFolder = "profiles/{$baby->id}";
            if (!Storage::exists("public/{$userFolder}")) {
                Storage::makeDirectory("public/{$userFolder}");
            }

            // DESCARGAR Y GUARDAR FOTOS REALES
            $photoCount = rand(3, 5);
            for ($j = 0; $j < $photoCount; $j++) {
                try {
                    $photoNumber = $j + 1;

                    // Descargar imagen de Unsplash (mujeres)
                    $imageUrl = "https://source.unsplash.com/random/800x1000/?woman,portrait&sig=" . uniqid();
                    $imageContent = Http::timeout(10)->get($imageUrl)->body();

                    // Generar nombre único con timestamp
                    $filename = "{$userFolder}/" . time() . '_' . uniqid() . '.jpg';

                    // Guardar en storage/app/public/profiles/{user_id}
                    Storage::put('public/' . $filename, $imageContent);

                    ProfilePhoto::create([
                        'user_id' => $baby->id,
                        'photo_path' => $filename,
                        'is_primary' => ($j === 0),
                        'is_verified' => true,
                        'moderation_status' => 'approved',
                        'order' => $photoNumber,
                    ]);

                    $this->command->info("  📸 Foto {$photoNumber}/{$photoCount} descargada");

                    // Esperar un poco para no saturar la API
                    sleep(1);

                } catch (\Exception $e) {
                    $this->command->warn("  ⚠️ Error descargando foto: {$e->getMessage()}");
                }
            }
        }

        // Crear 10 Sugar Daddies
        for ($i = 1; $i <= 10; $i++) {
            $daddy = User::create([
                'name' => fake()->firstName() . ' ' . fake()->lastName(),
                'email' => "daddy{$i}@test.com",
                'password' => bcrypt('password'),
                'user_type' => 'sugar_daddy',
                'gender' => 'male',
                'birth_date' => now()->subYears(rand(35, 55)),
                'city' => ['Santiago', 'Valparaíso', 'Concepción', 'Viña del Mar'][rand(0, 3)],
                'bio' => fake()->paragraph(),
                'is_verified' => true,
                'is_active' => true,
            ]);

            ProfileDetail::create([
                'user_id' => $daddy->id,
                'occupation' => fake()->jobTitle(),
                'industry' => 'tecnologia',
                'income_range' => 'mas_250k',
                'interests' => ['viajes', 'negocios', 'golf'],
            ]);

            $this->command->info("👔 Creando Sugar Daddy: {$daddy->name}");

            // Crear carpeta para este usuario
            $userFolder = "profiles/{$daddy->id}";
            if (!Storage::exists("public/{$userFolder}")) {
                Storage::makeDirectory("public/{$userFolder}");
            }

            // FOTOS PARA DADDIES
            $photoCount = rand(2, 4);
            for ($j = 0; $j < $photoCount; $j++) {
                try {
                    $photoNumber = $j + 1;

                    $imageUrl = "https://picsum.photos/800/1000?random=" . uniqid();
                    $imageContent = Http::timeout(10)->get($imageUrl)->body();
                    $filename = "{$userFolder}/" . time() . '_' . uniqid() . '.jpg';
                    Storage::put('public/' . $filename, $imageContent);

                    ProfilePhoto::create([
                        'user_id' => $daddy->id,
                        'photo_path' => $filename,
                        'is_primary' => ($j === 0),
                        'is_verified' => true,
                        'moderation_status' => 'approved',
                        'order' => $photoNumber,
                    ]);

                    $this->command->info("  📸 Foto {$photoNumber}/{$photoCount} descargada");
                    sleep(1);

                } catch (\Exception $e) {
                    $this->command->warn("  ⚠️ Error descargando foto: {$e->getMessage()}");
                }
            }
        }

        $totalUsers = User::where('email', 'LIKE', '%@test.com')->count();
        $totalPhotos = ProfilePhoto::count();

        $this->command->info("✅ Seeder completado!");
        $this->command->info("📊 {$totalUsers} usuarios creados");
        $this->command->info("🖼️  {$totalPhotos} fotos descargadas");
        $this->command->info("📂 Fotos guardadas en storage/app/public/profiles/{user_id}/");
    }
}
