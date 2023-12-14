<?php

namespace App\Http\Requests\Auth;

use App\Models\UserDoctor;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
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
        $userDoctor = UserDoctor::where('user_id', '=', $user->id)->first();

        return [
            'doctorId' => 'required|unique:user_doctors,doctor_id,'.$userDoctor->id,
            'name' => 'required',
            'smfName' => 'required',
        ];
    }
}
