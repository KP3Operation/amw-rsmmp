<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
        $user = auth('sanctum')->user();

        return [
            'name' => 'required|string',
            'gender' => 'required',
            'birth_date' => 'required|date',
            'email' => 'nullable|email|unique:users,email,'.$user->id,
        ];
    }
}
