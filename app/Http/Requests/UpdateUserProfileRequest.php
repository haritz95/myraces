<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'avatar' => ['nullable', 'image', 'max:2048'],
            'username' => ['nullable', 'string', 'max:30', 'regex:/^[a-zA-Z0-9_]+$/'],
            'bio' => ['nullable', 'string', 'max:300'],
            'city' => ['nullable', 'string', 'max:80'],
            'country' => ['nullable', 'string', 'max:80'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other,prefer_not'],
            'height_cm' => ['nullable', 'integer', 'min:100', 'max:250'],
            'weight_kg' => ['nullable', 'numeric', 'min:30', 'max:300'],
            'is_public' => ['boolean'],
            'attend_add_race' => ['nullable', 'in:ask,always,never'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'username.regex' => 'El nombre de usuario solo puede contener letras, números y guiones bajos.',
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.max' => 'La imagen no puede superar 2 MB.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'height_cm.min' => 'La altura mínima es 100 cm.',
            'height_cm.max' => 'La altura máxima es 250 cm.',
        ];
    }
}
