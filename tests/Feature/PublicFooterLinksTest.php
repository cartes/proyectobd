<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\User;
use App\Services\GeoLocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicFooterLinksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_public_plans_page_uses_public_layout_and_detected_country_footer_link(): void
    {
        $argentina = Country::create([
            'name' => 'Argentina',
            'iso_code' => 'AR',
            'slug' => 'argentina',
            'is_active' => true,
        ]);

        Country::create([
            'name' => 'Chile',
            'iso_code' => 'CL',
            'slug' => 'chile',
            'is_active' => true,
        ]);

        $this->app->instance(GeoLocationService::class, new class extends GeoLocationService
        {
            public function getCountryCodeFromIp(string $ip): ?string
            {
                return 'AR';
            }
        });

        $response = $this->get(route('plans.public'));

        $response->assertOk()
            ->assertSee('BIG-<span class="text-pink-500">DAD</span>', false)
            ->assertSee('Quiénes Somos')
            ->assertSee(route('archive.country', $argentina->slug), false)
            ->assertSee('Sugar Babies de Argentina')
            ->assertDontSee('Sugar Daddies Verificados');
    }

    public function test_footer_falls_back_to_country_with_most_public_sugar_babies(): void
    {
        $argentina = Country::create([
            'name' => 'Argentina',
            'iso_code' => 'AR',
            'slug' => 'argentina',
            'is_active' => true,
        ]);

        $chile = Country::create([
            'name' => 'Chile',
            'iso_code' => 'CL',
            'slug' => 'chile',
            'is_active' => true,
        ]);

        $this->createPublicSugarBaby($argentina->id);
        $this->createPublicSugarBaby($chile->id);
        $this->createPublicSugarBaby($chile->id);

        $this->app->instance(GeoLocationService::class, new class extends GeoLocationService
        {
            public function getCountryCodeFromIp(string $ip): ?string
            {
                return 'ZZ';
            }
        });

        $response = $this->get(route('about.index'));

        $response->assertOk()
            ->assertSee(route('archive.country', $chile->slug), false)
            ->assertSee('Sugar Babies de Chile');
    }

    protected function createPublicSugarBaby(int $countryId): User
    {
        $user = User::factory()->create([
            'user_type' => 'sugar_baby',
            'country_id' => $countryId,
            'is_active' => true,
        ]);

        $user->profileDetail()->create([
            'is_private' => false,
        ]);

        return $user;
    }
}
