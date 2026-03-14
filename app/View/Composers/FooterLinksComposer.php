<?php

namespace App\View\Composers;

use App\Models\Country;
use App\Services\GeoLocationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FooterLinksComposer
{
    public function __construct(
        protected GeoLocationService $geoLocationService
    ) {}

    public function compose(View $view): void
    {
        $view->with('footerArchiveCountryLink', $this->resolveArchiveCountryLink());
    }

    protected function resolveArchiveCountryLink(): ?array
    {
        $ip = request()->ip() ?? 'unknown';

        return Cache::remember('footer.archive-country-link.'.md5($ip), 1800, function () use ($ip) {
            $detectedCountryCode = $this->geoLocationService->getCountryCodeFromIp($ip);

            $country = null;

            if ($detectedCountryCode) {
                $country = Country::active()
                    ->where('iso_code', strtoupper($detectedCountryCode))
                    ->first(['id', 'name', 'slug']);
            }

            $country ??= $this->mostPopularSugarBabyCountry();

            if (! $country) {
                return null;
            }

            return [
                'label' => 'Sugar Babies de '.$country->name,
                'url' => route('archive.country', $country->slug),
                'country_name' => $country->name,
            ];
        });
    }

    protected function mostPopularSugarBabyCountry(): ?Country
    {
        return Cache::remember('footer.archive-country-link.fallback', 1800, function () {
            return Country::active()
                ->withCount([
                    'users as public_sugar_babies_count' => function ($query) {
                        $query->where('user_type', 'sugar_baby')
                            ->where('is_active', true)
                            ->whereHas('profileDetail', function ($profileQuery) {
                                $profileQuery->where('is_private', false);
                            });
                    },
                ])
                ->orderByDesc('public_sugar_babies_count')
                ->orderBy('name')
                ->first(['id', 'name', 'slug']);
        });
    }
}
