<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class HealthJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->isProfileComplete();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:50',
                'min:3',
            ],
            'description' => [
                'required',
                'string',
                'min:50',
                'max:10000',
            ],
            'job_type' => [
                'required',
                'string',
                'in:full-time,part-time,contract',
            ],
            'salary_min' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999.99',
            ],
            'salary_max' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999.99',
                'gte:salary_min', // salary_max must be greater than or equal to salary_min
            ],
            'experience_level' => [
                'required',
                'string',
                'in:entry,mid,senior',
            ],
            // Add qualifications validation rules
            'qualifications' => [
                'nullable',
                'array',
                'max:25',  // Maximum 5 qualifications
            ],
            'qualifications.*' => [
                'required',  // Each qualification must not be empty
                'string',
                'min:10',    // Minimum 10 characters per qualification
                'max:500',   // Maximum 500 characters per qualification
                'distinct',   // Each qualification must be unique
            ],
            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];

    }
}
