<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGearRequest extends FormRequest
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
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:shoes,watch,clothing,accessories,nutrition,other'],
            'purchase_date' => ['nullable', 'date'],
            'current_km' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'max_km' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'purchase_price' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
