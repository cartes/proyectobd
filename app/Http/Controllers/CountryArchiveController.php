<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\ProfileDetail;
use App\Models\User;

class CountryArchiveController extends Controller
{
    /**
     * Página pública por país: /sugar-babies/{pais} y /sugar-daddies/{pais}.
     * Los perfiles de Sugar Daddies son siempre privados: nunca se listan.
     */
    public function index(Country $country, string $type = 'sugar_baby')
    {
        $seo = $country->seoContent($type);
        $users = null;
        $countryCities = collect();

        if ($type === 'sugar_baby') {
            $users = User::where('user_type', 'sugar_baby')
                ->where('country_id', $country->id)
                ->where('is_active', true)
                ->whereHas('profileDetail', function ($query) {
                    $query->where('is_private', false);
                })
                ->with(['profileDetail', 'primaryPhoto', 'country'])
                ->latest()
                ->paginate(12);

            $countryCities = City::where('country_id', $country->id)
                ->active()
                ->whereHas('users', fn ($q) => $q->where('user_type', 'sugar_baby')
                    ->where('is_active', true)
                    ->whereHas('profileDetail', fn ($q2) => $q2->where('is_private', false)))
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);
        }

        $interestsOptions = ProfileDetail::interestsOptions();

        return view('archive.index', compact('users', 'country', 'interestsOptions', 'countryCities', 'type', 'seo'));
    }
}
