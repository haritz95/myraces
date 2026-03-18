<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        $ad = $this->route('ad');

        return $ad && $ad->user_id === $this->user()->id
            && in_array($ad->status, ['pending', 'rejected']);
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:80'],
            'subtitle' => ['nullable', 'string', 'max:160'],
            'image' => ['nullable', 'image', 'max:2048'],
            'cta_label' => ['required', 'string', 'max:30'],
            'target_url' => ['required', 'url', 'max:500'],
            'type' => ['required', 'in:race,product,service,event'],
            'location' => ['required', 'in:feed,dashboard'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'max_impressions' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'target_url.required' => 'La URL de destino es obligatoria.',
            'target_url.url' => 'La URL de destino no tiene un formato válido.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no puede superar 2 MB.',
            'ends_at.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
        ];
    }
}
