<?php

namespace App\Http\Requests\Patient;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CancelAppointmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_no' => 'required',
            'note' => 'required|string|min:3|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_no.required' => 'No. appointment tidak valid',
            'note.required' => 'Alasan pembatalan tidak boleh kosong.',
            'note.max' => 'Panjang alasan pembatalan melebihi 100 karakter.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'pembatalan konsultasi gagal',
            'errors' => $validator->errors(),
        ], 422));
    }
}
