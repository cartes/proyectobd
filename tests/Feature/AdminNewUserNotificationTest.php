<?php

namespace Tests\Feature;

use App\Notifications\AdminNewUserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminNewUserNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_notified_when_new_user_registers(): void
    {
        Notification::fake();

        config(['app.admin_notification_email' => 'admin@bigdad.test']);

        // We need an active country to pass validation
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

        Notification::assertSentOnDemand(
            AdminNewUserNotification::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'admin@bigdad.test';
            }
        );
    }

    public function test_no_notification_sent_when_admin_email_not_configured(): void
    {
        Notification::fake();

        config(['app.admin_notification_email' => null]);

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

        Notification::assertSentOnDemandTimes(AdminNewUserNotification::class, 0);
    }
}
