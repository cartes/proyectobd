<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(1),
            'content' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'featured_image' => null,
            'category_id' => null,
            'author_id' => User::factory(),
            'status' => 'published',
            'published_at' => now(),
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
            'og_image' => null,
            'views' => fake()->numberBetween(0, 1000),
            'reading_time' => fake()->numberBetween(1, 15),
        ];
    }

    /**
     * Post en estado draft.
     */
    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}
