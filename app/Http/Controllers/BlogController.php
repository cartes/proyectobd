<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts
     */
    public function index()
    {
        $posts = BlogPost::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogCategory::active()
            ->withCount('posts')
            ->orderBy('name')
            ->get();

        $recentPosts = BlogPost::published()
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'recentPosts'));
    }

    /**
     * Display the specified blog post
     */
    public function show($slug)
    {
        $query = BlogPost::with(['category', 'author'])->where('slug', $slug);

        // Only enforce published status for guests (or non-admins)
        // Assuming all logged-in users here are admins/staff for simplicity, or check specific permission
        if (! auth()->check()) {
            $query->published();
        }

        $post = $query->firstOrFail();

        // Increment view count
        $post->incrementViews();

        // Relacionados: misma categoría y, si faltan, los más recientes (siempre 3 si existen)
        $relatedPosts = BlogPost::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        if ($relatedPosts->count() < 3) {
            $relatedPosts = $relatedPosts->concat(
                BlogPost::published()
                    ->whereNotIn('id', $relatedPosts->pluck('id')->push($post->id))
                    ->latest('published_at')
                    ->limit(3 - $relatedPosts->count())
                    ->get()
            );
        }

        // Enlaces a páginas por país: los países mencionados en el artículo o, si no hay, los destacados
        $countryLinks = $this->countryLinksFor($post);

        $categories = BlogCategory::active()
            ->withCount('posts')
            ->orderBy('name')
            ->get();

        return view('blog.show', compact('post', 'relatedPosts', 'categories', 'countryLinks'));
    }

    /**
     * Países con contenido SEO mencionados en el artículo (o los destacados como respaldo).
     *
     * @return array<string, string> slug => nombre
     */
    protected function countryLinksFor(BlogPost $post): array
    {
        $countries = collect(config('seo_countries.countries', []))
            ->filter(fn ($entry) => ! empty($entry['sugar_baby']['intro']) || ! empty($entry['sugar_daddy']['intro']))
            ->map(fn ($entry) => $entry['name']);

        $text = mb_strtolower($post->title.' '.strip_tags((string) $post->content));
        $mentioned = $countries->filter(fn ($name) => str_contains($text, mb_strtolower($name)));

        if ($mentioned->isEmpty()) {
            $mentioned = $countries->only(config('seo_countries.featured', []));
        }

        return $mentioned->all();
    }

    /**
     * Display posts by category
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $posts = BlogPost::with(['category', 'author'])
            ->where('category_id', $category->id)
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogCategory::active()
            ->withCount('posts')
            ->orderBy('name')
            ->get();

        $recentPosts = BlogPost::published()
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('blog.category', compact('category', 'posts', 'categories', 'recentPosts'));
    }
}
