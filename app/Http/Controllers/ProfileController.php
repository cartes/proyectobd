<?php

namespace App\Http\Controllers;

use App\Models\ProfileDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(?User $user = null)
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

        // Registrar visita si no es el mismo usuario
        if ($authUser && $user->id !== $authUser->id) {
            \App\Models\ProfileView::create([
                'viewer_id' => $authUser->id,
                'viewed_id' => $user->id,
            ]);
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

        // ✅ PERFILES PRIVADOS: Si es privado, solo el dueño o matches pueden verlo
        if (!$isOwnProfile && $user->profileDetail->is_private && !$authUser->hasMatchWith($user)) {
            abort(403, 'Este perfil es privado. Necesitas un match para verlo.');
        }

        // ✅ PERMISOS DE VISIÓN (El que ve debe ser premium para ver Redes Sociales)
        $viewerFeatures = app(\App\Services\SubscriptionService::class)->getUserFeatures($authUser);
        $canSeeSocial = $viewerFeatures['share_data'] || $authUser->is_premium;

        // El WhatsApp solo lo pueden compartir los Babies (por ahora)
        $canSeeWhatsapp = $canSeeSocial && $user->user_type === 'sugar_baby';

        if ($user->user_type === 'sugar_daddy') {
            return view('profile.sugar-daddy.show', compact('user', 'options', 'isOwnProfile'))
                ->with('canSeeInstagram', $canSeeSocial);
        } else {
            return view('profile.sugar-baby.show', compact('user', 'options', 'isOwnProfile'))
                ->with('canSeeInstagram', $canSeeSocial)
                ->with('canSeeWhatsapp', $canSeeWhatsapp);
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

        $personalStyles = ProfileDetail::personalStyles();
        $fitnessLevels = ProfileDetail::fitnessLevels();

        // Obtener features del usuario
        $features = app(\App\Services\SubscriptionService::class)->getUserFeatures($user);
        $hasPrivateProfilePlan = $features['private_profiles'] ?? false;

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
                'mentorshipAreasOptions',
                'hasPrivateProfilePlan'
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
                'fitnessLevels',
                'hasPrivateProfilePlan'
            ));
        }
    }

    public function update(\App\Http\Requests\UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->updateProfile($request->validated());

        return redirect()->route('profile.show')
            ->with('success', '¡Perfil actualizado exitosamente! ✨');
    }
}
