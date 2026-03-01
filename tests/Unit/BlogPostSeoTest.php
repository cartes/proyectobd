<?php

namespace Tests\Unit;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostSeoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: meta_description tiene prioridad máxima.
     */
    public function test_seo_description_uses_meta_description_first(): void
    {
        $post = BlogPost::factory()->create([
            'meta_description' => 'Mi meta description personalizada',
            'excerpt' => 'Un excerpt diferente',
            'content' => '<p>Contenido largo del post</p>',
        ]);

        $this->assertEquals('Mi meta description personalizada', $post->seo_description);
    }

    /**
     * Test: Sin meta_description, usa excerpt.
     */
    public function test_seo_description_falls_back_to_excerpt(): void
    {
        $post = BlogPost::factory()->create([
            'meta_description' => null,
            'excerpt' => 'Este es el excerpt del post',
            'content' => '<p>Contenido del post</p>',
        ]);

        $this->assertEquals('Este es el excerpt del post', $post->seo_description);
    }

    /**
     * Test: Sin meta_description ni excerpt, genera desde content.
     */
    public function test_seo_description_generates_from_content(): void
    {
        $post = BlogPost::factory()->create([
            'meta_description' => null,
            'excerpt' => null,
            'content' => '<p>Este es el contenido principal del post con bastante texto para verificar que se genera correctamente la descripción SEO a partir del HTML del editor.</p>',
        ]);

        $description = $post->seo_description;

        $this->assertLessThanOrEqual(163, mb_strlen($description)); // 160 + "..."
        $this->assertStringNotContainsString('<p>', $description);
        $this->assertStringNotContainsString('</p>', $description);
    }

    /**
     * Test: HTML se limpia correctamente del contenido.
     */
    public function test_seo_description_strips_html(): void
    {
        $post = BlogPost::factory()->create([
            'meta_description' => null,
            'excerpt' => null,
            'content' => '<h2>Título</h2><p>Texto con <strong>negritas</strong> y <a href="#">links</a>.</p>',
        ]);

        $description = $post->seo_description;

        $this->assertStringNotContainsString('<', $description);
        $this->assertStringNotContainsString('>', $description);
        $this->assertStringContainsString('Título', $description);
        $this->assertStringContainsString('negritas', $description);
    }

    /**
     * Test: Sin nada, retorna el fallback.
     */
    public function test_seo_description_returns_fallback(): void
    {
        $post = BlogPost::factory()->create([
            'meta_description' => null,
            'excerpt' => null,
            'content' => '',
        ]);

        $this->assertStringContainsString('lifestyle premium', $post->seo_description);
        $this->assertStringContainsString('Big-Dad', $post->seo_description);
    }

    /**
     * Test: meta_keywords tiene prioridad máxima.
     */
    public function test_seo_keywords_uses_meta_keywords_first(): void
    {
        $post = BlogPost::factory()->create([
            'meta_keywords' => 'sugar dating, lifestyle, premium',
        ]);

        $this->assertEquals('sugar dating, lifestyle, premium', $post->seo_keywords);
    }

    /**
     * Test: Genera keywords automáticas desde el contenido.
     */
    public function test_seo_keywords_generates_from_content(): void
    {
        $post = BlogPost::factory()->create([
            'meta_keywords' => null,
            'content' => '<p>El lifestyle premium ofrece experiencias únicas. El lifestyle moderno combina elegancia con aventura. Lifestyle es la clave del éxito premium.</p>',
        ]);

        $keywords = $post->seo_keywords;

        $this->assertStringContainsString('lifestyle', $keywords);
        $this->assertStringNotContainsString('el', explode(', ', $keywords)[0] === 'el' ? 'el' : '');
    }

    /**
     * Test: La categoría se incluye en las keywords.
     */
    public function test_seo_keywords_includes_category(): void
    {
        $category = BlogCategory::factory()->create(['name' => 'Sugar Dating']);

        $post = BlogPost::factory()->create([
            'meta_keywords' => null,
            'category_id' => $category->id,
            'content' => '<p>Artículo sobre relaciones modernas y conexiones premium en Latinoamérica.</p>',
        ]);

        $keywords = $post->seo_keywords;

        $this->assertStringContainsString('sugar dating', $keywords);
    }
}
