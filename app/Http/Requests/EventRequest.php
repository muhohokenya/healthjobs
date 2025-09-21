<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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


            'start_date' => [
                'required',
                'date_format:Y-m-d\TH:i',
                'before_or_equal:end_date',
            ],

            'end_date' => [
                'required',
                'date_format:Y-m-d\TH:i',
                'after_or_equal:start_date',
            ],
            'link' => [
                'required',
                'string',
            ],
            'description' => [
                'min:50',
                'string',
                'max:5000',
            ]
        ];
    }

    private function isValidDateFormat($value): bool
    {
        try {
            $date = \DateTime::createFromFormat('d/m/Y, H:i', $value);
            return $date && $date->format('d/m/Y, H:i') === $value;
        } catch (\Exception $e) {
            return false;
        }
    }
}
