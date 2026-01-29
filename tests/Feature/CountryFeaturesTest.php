<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\User;
use App\Services\GeoLocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CountryFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_populates_countries()
    {
        $this->seed(\Database\Seeders\CountrySeeder::class);

        $this->assertDatabaseHas('countries', ['iso_code' => 'CL', 'name' => 'Chile']);
        $this->assertDatabaseHas('countries', ['iso_code' => 'AR', 'name' => 'Argentina']);
        $this->assertDatabaseCount('countries', 21); // 21 in the list
    }

    public function test_geolocation_service_detects_country()
    {
        Http::fake([
            'ipapi.co/*/json/' => Http::response(['country' => 'CL'], 200),
        ]);

        $service = new GeoLocationService();
        $code = $service->getCountryCodeFromIp('8.8.8.8');

        $this->assertEquals('CL', $code);
    }

    public function test_geolocation_service_returns_null_on_failure()
    {
        Http::fake([
            'ipapi.co/*/json/' => Http::response(null, 500),
        ]);

        $service = new GeoLocationService();
        $code = $service->getCountryCodeFromIp('8.8.8.8');

        $this->assertNull($code);
    }

    public function test_registration_requires_country()
    {
        $this->seed(\Database\Seeders\CountrySeeder::class);
        
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'user_type' => 'sugar_baby',
            'gender' => 'female',
            'birth_date' => '2000-01-01',
            // Missing country_id
        ]);

        $response->assertSessionHasErrors('country_id');
    }

    public function test_registration_stores_country()
    {
        $this->seed(\Database\Seeders\CountrySeeder::class);
        $country = Country::where('iso_code', 'CL')->first();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'user_type' => 'sugar_baby',
            'gender' => 'female',
            'birth_date' => '2000-01-01',
            'country_id' => $country->id,
            'city' => 'Santiago', // Optional now
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'country_id' => $country->id,
            'city' => 'Santiago',
        ]);
        
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
