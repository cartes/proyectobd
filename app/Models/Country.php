<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'iso_code',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Nombre de la ruta pública del país según el tipo de perfil.
     */
    public static function archiveRouteName(string $type): string
    {
        return $type === 'sugar_daddy' ? 'archive.country.daddies' : 'archive.country';
    }

    /**
     * Contenido SEO del país (config/seo_countries.php) para /sugar-babies o /sugar-daddies.
     * Si el país no tiene contenido propio, devuelve textos genéricos sin contadores.
     */
    public function seoContent(string $type): array
    {
        $entry = config("seo_countries.countries.{$this->slug}", []);
        $content = array_filter($entry[$type] ?? [], fn ($value) => ! empty($value));

        $isDaddy = $type === 'sugar_daddy';
        $label = $isDaddy ? 'Sugar Daddy' : 'Sugar Babies';

        $defaults = [
            'meta_title' => "{$label} en {$this->name} | Conoce Sugar Daddies y Babies – BigDad",
            'meta_description' => $isDaddy
                ? "Sugar Daddy en {$this->name}: perfil siempre privado, Sugar Babies moderadas y chat solo con match mutuo. Regístrate gratis en BigDad."
                : "Sugar Babies en {$this->name}: perfiles moderados, chat solo con match mutuo y total discreción. Crea tu perfil gratis en BigDad.",
            'intro' => [],
            'cities_text' => [],
            'how_it_works' => [],
            'safety' => [],
            'faqs' => [],
        ];

        return array_merge($defaults, $content, [
            'cities' => $entry['cities'] ?? [],
            'has_content' => ! empty($content['intro']),
        ]);
    }

    /**
     * Slugs de países con contenido SEO escrito para el tipo indicado.
     */
    public static function slugsWithSeoContent(string $type): array
    {
        return collect(config('seo_countries.countries', []))
            ->filter(fn ($entry) => ! empty($entry[$type]['intro']))
            ->keys()
            ->all();
    }
}
