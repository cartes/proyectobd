<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\AdminNewUserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminNewUserNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_notified_via_database_when_new_user_registers(): void
    {
        Notification::fake();

        // Crear un admin en BD para recibir la notificación
        $admin = User::factory()->create(['role' => 'admin']);

        $country = \App\Models\Country::create([
            'name' => 'Chile',
            'iso_code' => 'CL',
            'is_active' => true,
        ]);

        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'user_type' => 'sugar_baby',
            'gender' => 'female',
            'birth_date' => '1995-01-01',
            'country_id' => $country->id,
            'city' => 'Santiago',
        ]);

        // La notificación debe haberse enviado al admin por canal 'database'
        Notification::assertSentTo($admin, AdminNewUserNotification::class, function ($notification) {
            return in_array('database', $notification->via($notification));
        });
    }

    public function test_no_notification_sent_when_no_admins_exist(): void
    {
        Notification::fake();

        // No hay admin en BD
        $country = \App\Models\Country::create([
            'name' => 'Chile',
            'iso_code' => 'CL',
            'is_active' => true,
        ]);

        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser2@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'user_type' => 'sugar_daddy',
            'gender' => 'male',
            'birth_date' => '1990-05-10',
            'country_id' => $country->id,
            'city' => 'Valparaíso',
        ]);

        // Sin admins en BD, la notificación AdminNewUserNotification NO debe dispararse
        $anyUser = User::all();
        foreach ($anyUser as $u) {
            Notification::assertNotSentTo($u, AdminNewUserNotification::class);
        }
    }
}
