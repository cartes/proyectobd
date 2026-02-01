<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use SoftDeletes;

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
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
