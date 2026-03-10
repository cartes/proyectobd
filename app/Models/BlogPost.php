<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category_id',
        'author_id',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'views',
        'reading_time',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
        'reading_time' => 'integer',
    ];

    /**
     * Get the category of this post
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    /**
     * Get the author of this post
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope to get only published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to get scheduled posts
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->where('published_at', '>', now());
    }

    /**
     * Scope to get draft posts
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Auto-generate slug from title and calculate reading time
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (! $post->slug) {
                $post->slug = Str::slug($post->title);
            }

            // Calculate reading time (average 200 words per minute)
            if ($post->content && ! $post->reading_time) {
                $wordCount = str_word_count(strip_tags($post->content));
                $post->reading_time = max(1, ceil($wordCount / 200));
            }
        });

        static::updating(function ($post) {
            // Recalculate reading time if content changed
            if ($post->isDirty('content')) {
                $wordCount = str_word_count(strip_tags($post->content));
                $post->reading_time = max(1, ceil($wordCount / 200));
            }
        });
    }

    /**
     * SEO: Genera la mejor descripción disponible (≤160 chars).
     * Prioridad: meta_description → excerpt → contenido limpio → fallback.
     */
    public function getSeoDescriptionAttribute(): string
    {
        $fallback = 'Descubre lo último en lifestyle premium y conexiones exclusivas en Latinoamérica. Lee más en Big-Dad Blog.';

        if (! empty($this->meta_description)) {
            return $this->meta_description;
        }

        if (! empty($this->excerpt)) {
            return Str::limit($this->excerpt, 160);
        }

        if (! empty($this->content)) {
            $clean = strip_tags($this->content);
            $clean = html_entity_decode($clean, ENT_QUOTES, 'UTF-8');
            $clean = preg_replace('/\s+/', ' ', trim($clean));

            if (mb_strlen($clean) > 0) {
                return Str::limit($clean, 160);
            }
        }

        return $fallback;
    }

    /**
     * SEO: Genera keywords automáticas.
     * Prioridad: meta_keywords → categoría + top-5 palabras del contenido.
     */
    public function getSeoKeywordsAttribute(): string
    {
        if (! empty($this->meta_keywords)) {
            return $this->meta_keywords;
        }

        $keywords = [];

        // Incluir categoría si existe
        if ($this->relationLoaded('category') && $this->category) {
            $keywords[] = mb_strtolower($this->category->name);
        } elseif ($this->category_id) {
            $category = $this->category;
            if ($category) {
                $keywords[] = mb_strtolower($category->name);
            }
        }

        // Extraer top-5 palabras del contenido
        if (! empty($this->content)) {
            $clean = strip_tags($this->content);
            $clean = html_entity_decode($clean, ENT_QUOTES, 'UTF-8');
            $clean = mb_strtolower($clean);
            $clean = preg_replace('/[^\p{L}\s]/u', '', $clean);

            $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);

            // Stopwords en español
            $stopwords = [
                'de',
                'la',
                'el',
                'en',
                'que',
                'por',
                'con',
                'un',
                'una',
                'los',
                'las',
                'del',
                'para',
                'es',
                'se',
                'al',
                'lo',
                'como',
                'más',
                'mas',
                'pero',
                'sus',
                'le',
                'ya',
                'este',
                'esta',
                'entre',
                'cuando',
                'muy',
                'sin',
                'sobre',
                'ser',
                'también',
                'me',
                'hasta',
                'hay',
                'donde',
                'quien',
                'desde',
                'todo',
                'nos',
                'durante',
                'todos',
                'uno',
                'les',
                'ni',
                'contra',
                'otros',
                'ese',
                'eso',
                'ante',
                'ellos',
                'fue',
                'son',
                'está',
                'tiene',
                'han',
                'sido',
                'tiene',
                'the',
                'and',
                'for',
                'are',
                'but',
                'not',
                'you',
                'all',
                'can',
                'had',
                'her',
                'was',
                'one',
                'our',
                'out',
                'has',
                'with',
                'that',
                'this',
                'from',
                'they',
            ];

            $filtered = array_filter($words, function ($word) use ($stopwords) {
                return mb_strlen($word) >= 4 && ! in_array($word, $stopwords);
            });

            $frequency = array_count_values($filtered);
            arsort($frequency);

            $topWords = array_slice(array_keys($frequency), 0, 5);
            $keywords = array_merge($keywords, $topWords);
        }

        // Fallback si no hay nada
        if (empty($keywords)) {
            return 'lifestyle, conexiones, latinoamérica, premium, blog';
        }

        return implode(', ', array_unique($keywords));
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
