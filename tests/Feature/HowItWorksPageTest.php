<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HowItWorksPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_how_it_works_page_uses_public_static_layout(): void
    {
        $response = $this->get(route('como-funciona'));

        $response->assertOk()
            ->assertSee('Cómo Funciona | Big-dad', false)
            ->assertSee('wordmark-light', false)
            ->assertSee('Quiénes Somos')
            ->assertSee('Crear cuenta gratis')
            ->assertSee('Ver todos los planes')
            ->assertSee('application/ld+json', false);
    }
}
