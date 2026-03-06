<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\ProfileDetail;
use App\Models\User;

class CityArchiveController extends Controller
{
    public function index(Country $country, City $city)
    {
        $users = User::where('user_type', 'sugar_baby')
            ->where('country_id', $country->id)
            ->where('city_id', $city->id)
            ->where('is_active', true)
            ->whereHas('profileDetail', fn ($q) => $q->where('is_private', false))
            ->with(['profileDetail', 'photos', 'country', 'cityModel'])
            ->latest()
            ->paginate(12);

        $interestsOptions = ProfileDetail::interestsOptions();

        return view('archive.city', compact('users', 'country', 'city', 'interestsOptions'));
    }
}
