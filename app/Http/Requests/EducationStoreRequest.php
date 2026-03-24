<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:500',
            'is_current' => 'nullable|boolean',
        ];
    }
}
