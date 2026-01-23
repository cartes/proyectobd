<?php

namespace App\Http\Requests;

use App\Models\ProfileDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

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
            'is_private' => 'nullable|boolean',
            'social_instagram' => 'nullable|string|max:100',
            'social_whatsapp' => 'nullable|string|max:20',
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

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $user = $this->user();
        $features = app(\App\Services\SubscriptionService::class)->getUserFeatures($user);

        // Validar features premium
        if (!$features['private_profiles'] && $this->has('is_private')) {
            $this->merge(['is_private' => false]);
        }

        if (!$features['share_data']) {
            if ($this->filled('social_instagram') || $this->filled('social_whatsapp')) {
                $this->merge([
                    'social_instagram' => null,
                    'social_whatsapp' => null
                ]);
            }
        }

        // ✅ REGLA ESPECÍFICA: Solo Sugar Babies pueden compartir WhatsApp (por ahora)
        if ($user->user_type === 'sugar_daddy' && $this->filled('social_whatsapp')) {
            $this->merge(['social_whatsapp' => null]);
        }
    }
}
