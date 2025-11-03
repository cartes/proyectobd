<?php

namespace App\Http\Controllers;

use App\Models\ProfileDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user = null)
    {
        // Si no se pasa un usuario, mostrar el perfil del usuario autenticado
        if (!$user) {
            $user = Auth::user();
        }

        $authUser = Auth::user();

        // Si es otro usuario, verificar que haya match
        if ($user->id !== $authUser->id && !$authUser->hasMatchWith($user)) {
            abort(403, 'No tienes permiso para ver este perfil.');
        }

        $user->load('profileDetail');

        if (!$user->profileDetail) {
            $user->profileDetail()->create([]);
            $user->load('profileDetail');
        }

        $options = [
            'bodyTypes' => ProfileDetail::bodyTypes(),
            'relationshipStatuses' => ProfileDetail::relationshipStatuses(),
            'childrenOptions' => ProfileDetail::childrenOptions(),
            'educationLevels' => ProfileDetail::educationLevels(),
            'incomeRanges' => ProfileDetail::incomeRanges(),
            'availabilityOptions' => ProfileDetail::availabilityOptions(),
            'interestsOptions' => ProfileDetail::interestsOptions(),
            'industries' => ProfileDetail::industries(),
            'companySizes' => ProfileDetail::companySizes(),
            'travelFrequencies' => ProfileDetail::travelFrequencies(),
            'mentorshipAreasOptions' => ProfileDetail::mentorshipAreasOptions(),
            'personalStyles' => ProfileDetail::personalStyles(),
            'fitnessLevels' => ProfileDetail::fitnessLevels(),
        ];

        $isOwnProfile = $user->id === $authUser->id;

        if ($user->user_type === 'sugar_daddy') {
            return view('profile.sugar-daddy.show', compact('user', 'options', 'isOwnProfile'));
        } else {
            return view('profile.sugar-baby.show', compact('user', 'options', 'isOwnProfile'));
        }
    }

    public function edit()
    {
        $user = Auth::user()->load('profileDetail', 'photos');

        if (!$user->profileDetail) {
            $user->profileDetail()->create([]);
            $user->load('profileDetail');
        }

        // Opciones comunes
        $bodyTypes = ProfileDetail::bodyTypes();
        $relationshipStatuses = ProfileDetail::relationshipStatuses();
        $childrenOptions = ProfileDetail::childrenOptions();
        $educationLevels = ProfileDetail::educationLevels();
        $incomeRanges = ProfileDetail::incomeRanges();
        $availabilityOptions = ProfileDetail::availabilityOptions();
        $interestsOptions = ProfileDetail::interestsOptions();

        // Opciones específicas para Sugar Daddy
        $industries = ProfileDetail::industries();
        $companySizes = ProfileDetail::companySizes();
        $travelFrequencies = ProfileDetail::travelFrequencies();
        $mentorshipAreasOptions = ProfileDetail::mentorshipAreasOptions();

        // Opciones específicas para Sugar Baby
        $personalStyles = ProfileDetail::personalStyles();
        $fitnessLevels = ProfileDetail::fitnessLevels();

        // Redirigir según el tipo de usuario
        if ($user->user_type === 'sugar_daddy') {
            return view('profile.sugar-daddy.edit', compact(
                'user',
                'bodyTypes',
                'relationshipStatuses',
                'childrenOptions',
                'educationLevels',
                'incomeRanges',
                'availabilityOptions',
                'interestsOptions',
                'industries',
                'companySizes',
                'travelFrequencies',
                'mentorshipAreasOptions'
            ));
        } else {
            return view('profile.sugar-baby.edit', compact(
                'user',
                'bodyTypes',
                'relationshipStatuses',
                'childrenOptions',
                'educationLevels',
                'availabilityOptions',
                'interestsOptions',
                'personalStyles',
                'fitnessLevels'
            ));
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validación base
        $rules = [
            'city' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date|before:today',
            'bio' => 'nullable|string|max:1000',
            'height' => 'nullable|integer|min:100|max:250',
            'body_type' => ['nullable', Rule::in(array_keys(ProfileDetail::bodyTypes()))],
            'relationship_status' => ['nullable', Rule::in(array_keys(ProfileDetail::relationshipStatuses()))],
            'children' => ['nullable', Rule::in(array_keys(ProfileDetail::childrenOptions()))],
            'education' => ['nullable', Rule::in(array_keys(ProfileDetail::educationLevels()))],
            'occupation' => 'nullable|string|max:100',
            'interests' => 'nullable|array',
            'interests.*' => 'string',
            'languages' => 'nullable|array',
            'languages.*' => 'string',
            'lifestyle' => 'nullable|array',
            'lifestyle.*' => 'string',
            'looking_for' => 'nullable|string|max:500',
            'availability' => ['nullable', Rule::in(array_keys(ProfileDetail::availabilityOptions()))],
        ];

        // Reglas específicas para Sugar Daddy
        if ($user->user_type === 'sugar_daddy') {
            $rules = array_merge($rules, [
                'income_range' => ['nullable', Rule::in(array_keys(ProfileDetail::incomeRanges()))],
                'net_worth' => 'nullable|string|max:100',
                'industry' => ['nullable', Rule::in(array_keys(ProfileDetail::industries()))],
                'company_size' => ['nullable', Rule::in(array_keys(ProfileDetail::companySizes()))],
                'travel_frequency' => ['nullable', Rule::in(array_keys(ProfileDetail::travelFrequencies()))],
                'what_i_offer' => 'nullable|string|max:1000',
                'mentorship_areas' => 'nullable|array',
                'mentorship_areas.*' => 'string',
            ]);
        }

        // Reglas específicas para Sugar Baby
        if ($user->user_type === 'sugar_baby') {
            $rules = array_merge($rules, [
                'appearance_details' => 'nullable|string|max:500',
                'personal_style' => ['nullable', Rule::in(array_keys(ProfileDetail::personalStyles()))],
                'fitness_level' => ['nullable', Rule::in(array_keys(ProfileDetail::fitnessLevels()))],
                'aspirations' => 'nullable|string|max:1000',
                'ideal_daddy' => 'nullable|string|max:500',
            ]);
        }

        $validated = $request->validate($rules);

        // Actualizar User
        $user->update([
            'city' => $request->city,
            'birth_date' => $request->birth_date,
            'bio' => $request->bio,
        ]);

        // Datos comunes de ProfileDetail
        $profileData = [
            'height' => $request->height,
            'body_type' => $request->body_type,
            'relationship_status' => $request->relationship_status,
            'children' => $request->children,
            'education' => $request->education,
            'occupation' => $request->occupation,
            'interests' => $request->interests ?? [],
            'languages' => $request->languages ?? [],
            'lifestyle' => $request->lifestyle ?? [],
            'looking_for' => $request->looking_for,
            'availability' => $request->availability,
        ];

        // Agregar campos específicos según tipo de usuario
        if ($user->user_type === 'sugar_daddy') {
            $profileData = array_merge($profileData, [
                'income_range' => $request->income_range,
                'net_worth' => $request->net_worth,
                'industry' => $request->industry,
                'company_size' => $request->company_size,
                'travel_frequency' => $request->travel_frequency,
                'what_i_offer' => $request->what_i_offer,
                'mentorship_areas' => $request->mentorship_areas ?? [],
            ]);
        }

        if ($user->user_type === 'sugar_baby') {
            $profileData = array_merge($profileData, [
                'appearance_details' => $request->appearance_details,
                'personal_style' => $request->personal_style,
                'fitness_level' => $request->fitness_level,
                'aspirations' => $request->aspirations,
                'ideal_daddy' => $request->ideal_daddy,
            ]);
        }

        $user->profileDetail()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('profile.show')
            ->with('success', '¡Perfil actualizado exitosamente! ✨');
    }
}
