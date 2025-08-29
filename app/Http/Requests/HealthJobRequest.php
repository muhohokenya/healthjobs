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
                'max:255',
                'min:3'
            ],
            'company' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'description' => [
                'required',
                'string',
                'min:50',
                'max:10000'
            ],
            'location' => [
                'required',
                'string',
                'max:255'
            ],
            'job_type' => [
                'required',
                'string',
                'in:full-time,part-time,contract'
            ],
            'salary_min' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999.99'
            ],
            'salary_max' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999.99',
                'gte:salary_min' // salary_max must be greater than or equal to salary_min
            ],
            'experience_level' => [
                'required',
                'string',
                'in:entry,mid,senior'
            ],
            'requirements' => [
                'nullable',
                'array'
            ],
            'requirements.*' => [
                'string',
                'max:500'
            ],
            'is_active' => [
                'sometimes',
                'boolean'
            ]
        ];

    }
}
