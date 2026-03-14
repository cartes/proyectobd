<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_renders_public_seo_content(): void
    {
        $response = $this->get(route('about.index'));

        $response->assertOk()
            ->assertSee('Quiénes Somos | Big-dad', false)
            ->assertSee('experiencia en sugar dating')
            ->assertSee('https://big-dad.com/blog/que-es-un-sugar-daddy', false)
            ->assertSee(route('blog.index'), false)
            ->assertSee('application/ld+json', false);
    }

    public function test_sitemap_generation_includes_about_page(): void
    {
        $output = 'storage/framework/testing/about-sitemap.xml';
        $fullPath = base_path($output);

        File::ensureDirectoryExists(dirname($fullPath));
        File::delete($fullPath);

        Artisan::call('sitemap:generate', ['--output' => $output]);

        $this->assertTrue(File::exists($fullPath));
        $this->assertStringContainsString(route('about.index'), File::get($fullPath));

        File::delete($fullPath);
    }
}
