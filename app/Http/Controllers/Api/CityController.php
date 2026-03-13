<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;

class CityController extends Controller
{
    public function index(string $country)
    {
        $countryModel = Country::query()
            ->active()
            ->where(function ($query) use ($country) {
                $query->where('slug', $country);

                if (ctype_digit($country)) {
                    $query->orWhere('id', (int) $country);
                }
            })
            ->firstOrFail();

        return $countryModel->cities()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }
}
