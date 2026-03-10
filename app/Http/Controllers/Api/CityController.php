<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;

class CityController extends Controller
{
    public function index(Country $country)
    {
        return City::where('country_id', $country->id)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }
}
