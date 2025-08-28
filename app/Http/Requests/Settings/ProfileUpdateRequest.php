<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'string',
                'max:255',
                Rule::requiredIf(fn () => $this->user()?->licence_status !== 'active'),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            //            PT2025D08038
            //            'licence' => [
            //                'nullable',
            //                'min:12',
            //                'max:20',
            //                'regex:/^[A-Z0-9\-\/]+$/', // Allow letters, numbers, hyphens, and forward slashes
            //                Rule::unique('users', 'licence_number')->ignore($this->user()->id),
            //            ],
        ];
    }
}
