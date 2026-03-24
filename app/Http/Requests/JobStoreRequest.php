<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:job_categories,id',
            'description' => 'required|string|max:5000',
            'requirements' => 'nullable|string|max:3000',
            'benefits' => 'nullable|string|max:2000',
            'responsibilities' => 'nullable|string|max:3000',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'job_type' => 'required|in:full-time,part-time,contract,internship,freelance',
            'work_mode' => 'required|in:onsite,remote,hybrid',
            'experience_level' => 'nullable|string|max:50',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'nullable|string|size:3',
            'salary_type' => 'nullable|string|max:50',
            'skills' => 'nullable|string|max:500',
            'vacancies' => 'nullable|integer|min:1',
            'application_deadline' => 'nullable|date|after:today',
            'status' => 'nullable|in:active,pending,closed,draft',
        ];
    }
}
