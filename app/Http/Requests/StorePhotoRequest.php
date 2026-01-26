<?php

namespace App\Http\Requests;

use App\Models\ProfilePhoto;
use Illuminate\Foundation\Http\FormRequest;

class StorePhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // El usuario debe estar autenticado y poder subir más fotos
        return auth()->check() && $this->user()->canUploadMorePhotos();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'photo' => [
                'required',
                'image',
                'mimes:' . implode(',', ProfilePhoto::ALLOWED_TYPES),
                'max:' . ProfilePhoto::MAX_FILE_SIZE,
            ],
            'potential_nudity' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'photo.required' => 'Debes seleccionar una foto',
            'photo.image' => 'El archivo debe ser una imagen',
            'photo.mimes' => 'Solo se permiten fotos en formato: ' . implode(', ', ProfilePhoto::ALLOWED_TYPES),
            'photo.max' => 'La foto no debe superar 5MB',
        ];
    }
}
