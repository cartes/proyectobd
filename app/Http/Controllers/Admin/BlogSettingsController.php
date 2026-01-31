<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogSettings;
use Illuminate\Http\Request;

class BlogSettingsController extends Controller
{
    /**
     * Display the blog settings page
     */
    public function index()
    {
        $settings = BlogSettings::pluck('value', 'key')->toArray();

        return view('admin.blog.settings.index', compact('settings'));
    }

    /**
     * Update the blog settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'google_analytics_id' => 'nullable|string',
            'google_tag_manager_id' => 'nullable|string',
            'meta_title_template' => 'nullable|string',
            'meta_description_template' => 'nullable|string',
            'header_scripts' => 'nullable|string',
            'footer_scripts' => 'nullable|string',
            'posts_per_page' => 'nullable|integer|min:1|max:50',
            'enable_comments' => 'nullable|boolean',
        ]);

        foreach ($validated as $key => $value) {
            BlogSettings::set($key, $value);
        }

        return redirect()->route('admin.blog.settings.index')
            ->with('success', 'Configuración actualizada exitosamente.');
    }

    /**
     * Display SEO settings
     */
    public function seo()
    {
        $settings = BlogSettings::getMultiple([
            'meta_title_template',
            'meta_description_template',
        ]);

        return view('admin.blog.settings.seo', compact('settings'));
    }

    /**
     * Display analytics settings
     */
    public function analytics()
    {
        $settings = BlogSettings::getMultiple([
            'google_analytics_id',
            'google_tag_manager_id',
        ]);

        return view('admin.blog.settings.analytics', compact('settings'));
    }

    /**
     * Display scripts settings
     */
    public function scripts()
    {
        $settings = BlogSettings::getMultiple([
            'header_scripts',
            'footer_scripts',
        ]);

        return view('admin.blog.settings.scripts', compact('settings'));
    }
}
