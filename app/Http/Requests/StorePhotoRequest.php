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
                'uploaded',
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
            'photo.uploaded' => 'La foto no se pudo subir. Es posible que el archivo sea demasiado grande para el servidor.',
            'photo.image' => 'El archivo debe ser una imagen',
            'photo.mimes' => 'Solo se permiten fotos en formato: ' . implode(', ', ProfilePhoto::ALLOWED_TYPES),
            'photo.max' => 'La foto no debe superar 5MB',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        \Log::info('StorePhotoRequest: starting validation');
        $validator->after(function ($validator) {
            if ($this->hasFile('photo')) {
                $file = $this->file('photo');
                if (!$file->isValid()) {
                    \Log::critical('DEBUG: PHP Upload Error', [
                        'error_code' => $file->getError(),
                        'error_message' => $file->getErrorMessage(),
                    ]);
                }
            } else if ($this->exists('photo')) {
                \Log::critical('DEBUG: Photo field exists but is not a file. Likely post_max_size exceeded.');
            }
        });
    }
}
