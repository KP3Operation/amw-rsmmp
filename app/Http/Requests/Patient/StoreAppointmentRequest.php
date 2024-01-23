<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
            'patient_id' => 'nullable',
            'patient_name' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'appointment_date' => 'required|date',
            'service_unit_id' => 'required',
            'paramedic_id' => 'required',
            'is_family_member' => 'nullable',
            'family_id' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'patient_name.required' => 'Nama Pasien tidak valid',
            'patient_id.required' => 'ID Pasien tidak valid',
            'birth_date.required' => 'Mohon pilih Tanggal dan Waktu yang valid',
            'birth_date.date' => 'Mohon pilih Tanggal yang valid',
            'appointment_date.required' => 'Mohon pilih Tanggal dan Waktu',
            'appointment_date.date' => 'Mohon pilih Tanggal dan Waktu yang valid',
            'service_unit_id.required' => 'Mohon pilih Unit Pelayanan',
            'paramedic_id.required' => 'Mohon pilih Paramedis',
            'gender.required' => 'Jenis Kelamin Pasien tidak valid',
        ];
    }
}
