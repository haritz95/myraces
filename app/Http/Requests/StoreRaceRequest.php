<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:2'],
            'distance' => ['required', 'numeric', 'min:0.1', 'max:9999'],
            'distance_unit' => ['required', 'in:km,mi'],
            'modality' => ['required', 'in:road,trail,mountain,track,cross,other'],
            'status' => ['required', 'in:upcoming,completed,dnf,dns'],
            'finish_time' => ['nullable', 'string', 'regex:/^\d+:\d{2}(:\d{2})?$/'],
            'position_overall' => ['nullable', 'integer', 'min:1'],
            'position_category' => ['nullable', 'integer', 'min:1'],
            'category' => ['nullable', 'string', 'max:100'],
            'bib_number' => ['nullable', 'string', 'max:20'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:9999'],
            'website' => ['nullable', 'url', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_public' => ['boolean'],
            'gear_ids' => ['nullable', 'array'],
            'gear_ids.*' => ['integer', 'exists:gears,id'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.races.name_required'),
            'date.required' => __('validation.races.date_required'),
            'distance.required' => __('validation.races.distance_required'),
            'finish_time.regex' => __('validation.races.finish_time_format'),
        ];
    }
}
