<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StoreFamilyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ssn' => 'required|min:16|max:16|unique:families,ssn',
            'name' => 'required',
            'phone_number' => 'required|min:10|max:13',
            'gender' => 'required',
            'birth_date' => 'date',
            'email' => 'nullable|email|unique:families,email',
        ];
    }
}
