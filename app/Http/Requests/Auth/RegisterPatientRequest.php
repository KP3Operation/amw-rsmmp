<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPatientRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "phone_number" => "required|min:10|max:13|unique:users,phone_number",
            "ssn" => "required|min:16|max:16|unique:user_patients,ssn",
            "name" => "required|string",
            "role" => "required"
        ];
    }
}
