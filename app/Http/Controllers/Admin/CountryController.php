<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index()
    {
        $countries = \App\Models\Country::orderBy('name')->get();
        return view('admin.countries.index', compact('countries'));
    }

    public function toggleStatus(\App\Models\Country $country)
    {
        $country->update(['is_active' => !$country->is_active]);

        return back()->with('success', 'País actualizado correctamente.');
    }
}
