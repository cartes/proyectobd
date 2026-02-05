<?php

namespace App\Http\Controllers;

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
        // Query only public and active Sugar Babies from the given country
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

        return view('archive.index', compact('users', 'country', 'interestsOptions'));
    }
}
