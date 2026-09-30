<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CountrySeoPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        Country::create(['name' => 'Uruguay', 'iso_code' => 'UY', 'slug' => 'uruguay', 'is_active' => true]);
        Country::create(['name' => 'República Dominicana', 'iso_code' => 'DO', 'slug' => 'republica-dominicana', 'is_active' => false]);
        Country::create(['name' => 'Chile', 'iso_code' => 'CL', 'slug' => 'chile', 'is_active' => true]);
    }

    public function test_babies_page_renders_seo_content_without_profiles(): void
    {
        $this->get('/sugar-babies/uruguay')
            ->assertOk()
            ->assertSee('<title>Sugar Babies en Uruguay | Conoce Sugar Daddies y Babies – BigDad</title>', false)
            ->assertSee(config('seo_countries.countries.uruguay.sugar_baby.meta_description'), false)
            ->assertDontSee('Conoce 0', false)
            ->assertDontSee('0 Perfiles', false)
            ->assertSee('Montevideo')
            ->assertSee('"@type": "FAQPage"', false)
            ->assertSee('"@type": "BreadcrumbList"', false)
            ->assertSee(route('archive.country.daddies', 'uruguay'), false)
            ->assertSee('<link rel="canonical" href="'.route('archive.country', 'uruguay').'">', false);
    }

    public function test_daddies_page_never_lists_profiles_and_links_to_babies(): void
    {
        $this->get('/sugar-daddies/uruguay')
            ->assertOk()
            ->assertSee('<title>Sugar Daddy en Uruguay | Conoce Sugar Daddies y Babies – BigDad</title>', false)
            ->assertSee('Tu perfil, siempre privado')
            ->assertDontSee('Ver Perfil Completo')
            ->assertSee('index, follow', false)
            ->assertSee(route('archive.country', 'uruguay'), false);
    }

    public function test_daddies_page_without_content_is_noindex(): void
    {
        $this->get('/sugar-daddies/chile')
            ->assertOk()
            ->assertSee('noindex, follow', false);
    }

    public function test_iso_urls_redirect_permanently_to_slug(): void
    {
        $this->get('/sugar-daddies/UY')->assertRedirect(route('archive.country.daddies', 'uruguay'))->assertStatus(301);
    }

    public function test_sitemap_includes_country_pages_with_content(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('archive.country', 'republica-dominicana'), false)
            ->assertSee(route('archive.country.daddies', 'republica-dominicana'), false)
            ->assertSee(route('archive.country.daddies', 'uruguay'), false)
            ->assertSee(route('archive.country', 'chile'), false)
            ->assertDontSee(route('archive.country.daddies', 'chile'), false);
    }

    public function test_home_has_brand_h1_and_country_links(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('BigDad: Sugar Dating en Latinoamérica')
            ->assertSee('"alternateName": ["Big-Dad", "Big Dad", "bigdad"]', false)
            ->assertSee(route('archive.country', 'republica-dominicana'), false)
            ->assertSee(route('archive.country.daddies', 'uruguay'), false);
    }
}
