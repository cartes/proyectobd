<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request, \App\Services\GeoLocationService $geoService): View
    {
        $countries = \App\Models\Country::active()->orderBy('name')->get();

        // Detectar país por IP
        $detectedCountryCode = $geoService->getCountryCodeFromIp($request->ip());
        $defaultCountry = null;

        if ($detectedCountryCode) {
            $defaultCountry = $countries->firstWhere('iso_code', $detectedCountryCode);
        }

        // Fallback a Chile si no se detecta (o primer de la lista)
        // $defaultCountryId = $defaultCountry ? $defaultCountry->id : ($countries->firstWhere('iso_code', 'CL')?->id ?? null);
        $defaultCountryId = $defaultCountry?->id;

        return view('auth.register', compact('countries', 'defaultCountryId'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'in:sugar_daddy,sugar_baby'],
            'gender' => ['required', 'in:male,female,other'],
            'birth_date' => ['required', 'date', 'before:today'],
            'country_id' => ['required', 'exists:countries,id'],
            'city' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'country_id' => $request->country_id,
            'city' => $request->city,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
