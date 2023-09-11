<?php

namespace App\Http\Requests\Auth;

use App\Models\UserPatient;
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
        $userPatient = UserPatient::where('user_id', '=', $user->id)->first();

        return [
            "phone_number" => "required|min:10|max:13|unique:users,phone_number," . $user->id,
            "ssn" => "required|min:16|max:16|unique:user_patients,ssn," . $userPatient->id,
            "name" => "required|string",
            "gender" => "required",
            "birth_date" => "required|date",
            "email" => "nullable|email|unique:users,email," . $user->id
        ];
    }
}
