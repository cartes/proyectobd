<?php

namespace App\View\Composers;

use App\Models\BlogSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class BlogSettingsComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $settings = Cache::remember('blog.settings', 3600, function () {
            return BlogSettings::all()->pluck('value', 'key')->toArray();
        });

        $view->with('blogSettings', $settings);
    }
}
