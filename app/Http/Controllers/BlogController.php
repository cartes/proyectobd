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

        // Get related posts from the same category
        $relatedPosts = BlogPost::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $categories = BlogCategory::active()
            ->withCount('posts')
            ->orderBy('name')
            ->get();

        return view('blog.show', compact('post', 'relatedPosts', 'categories'));
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
