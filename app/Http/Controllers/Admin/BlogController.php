<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;

class BlogController extends Controller
{
    /**
     * Display the blog dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_posts' => BlogPost::count(),
            'published' => BlogPost::where('status', 'published')->count(),
            'drafts' => BlogPost::where('status', 'draft')->count(),
            'scheduled' => BlogPost::where('status', 'scheduled')->count(),
            'total_views' => BlogPost::sum('views'),
        ];

        $recentPosts = BlogPost::with(['author', 'category'])
            ->latest()
            ->limit(10)
            ->get();

        $popularPosts = BlogPost::published()
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        // Posts by month for chart (last 6 months)
        // Agrupado en PHP para ser portable entre MySQL/PostgreSQL (son pocos registros)
        $postsByMonth = BlogPost::where('created_at', '>=', now()->subMonths(6))
            ->get(['created_at'])
            ->groupBy(fn ($post) => $post->created_at->format('Y-m'))
            ->map(fn ($group, $month) => (object) ['month' => $month, 'count' => $group->count()])
            ->sortKeys()
            ->values();

        return view('admin.blog.dashboard', compact('stats', 'recentPosts', 'popularPosts', 'postsByMonth'));
    }
}
