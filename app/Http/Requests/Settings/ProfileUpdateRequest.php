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
            'contact_number'=>[
                'string',
                'max:21',
                'regex:/^(\+254|0)(7|1)[0-9]{8}$/', // Kenyan phone number format
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'licence' => [
                'nullable',
                'between:9,20',
                Rule::unique('facilities', 'licence_number')->ignore($this->user()->id),
            ],
        ];
    }
}
