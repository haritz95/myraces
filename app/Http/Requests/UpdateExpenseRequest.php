<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'amount' => ['required', 'numeric', 'min:0.01', 'max:99999'],
            'currency' => ['required', 'string', 'size:3'],
            'category' => ['required', 'in:registration,travel,accommodation,gear,nutrition,other'],
            'description' => ['nullable', 'string', 'max:500'],
            'date' => ['required', 'date'],
            'race_id' => ['nullable', 'exists:races,id'],
        ];
    }
}
