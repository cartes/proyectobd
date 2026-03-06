<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\ProfileDetail;
use App\Models\User;

class CountryArchiveController extends Controller
{
    /**
     * Display a listing of Sugar Babies for a specific country.
     */
    public function index(Country $country)
    {
        $users = User::where('user_type', 'sugar_baby')
            ->where('country_id', $country->id)
            ->where('is_active', true)
            ->whereHas('profileDetail', function ($query) {
                $query->where('is_private', false);
            })
            ->with(['profileDetail', 'photos', 'country'])
            ->latest()
            ->paginate(12);

        $interestsOptions = ProfileDetail::interestsOptions();

        $countryCities = City::where('country_id', $country->id)
            ->active()
            ->whereHas('users', fn ($q) => $q->where('user_type', 'sugar_baby')
                ->where('is_active', true)
                ->whereHas('profileDetail', fn ($q2) => $q2->where('is_private', false)))
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return view('archive.index', compact('users', 'country', 'interestsOptions', 'countryCities'));
    }
}
