<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_screen_prefills_sugar_daddy_from_home_cta(): void
    {
        $response = $this->get('/register?role=sugar_daddy');

        $response
            ->assertOk()
            ->assertSee("userType: 'sugar_daddy'", false);
    }

    public function test_new_users_can_register(): void
    {
        $this->seed(\Database\Seeders\CountrySeeder::class);
        $country = \App\Models\Country::where('iso_code', 'AR')->first();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'user_type' => 'sugar_daddy',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'city' => 'Buenos Aires',
            'country_id' => $country->id,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));
    }
}
