<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePersonalRecordRequest extends FormRequest
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
            'distance_label' => ['required', 'string', 'max:100'],
            'distance_km' => ['nullable', 'numeric', 'min:0.1', 'max:9999'],
            'time_seconds' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'race_id' => ['nullable', 'exists:races,id'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'distance_label.required' => 'La distancia es obligatoria.',
            'time_seconds.required' => 'El tiempo es obligatorio.',
            'date.required' => 'La fecha es obligatoria.',
        ];
    }
}
