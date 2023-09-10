<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\UserAlreadyExistException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\DoctorRegisterRequest;
use App\Http\Requests\Auth\PatientRegisterRequest;
use App\Http\Requests\Auth\UpdatePatientRequest;
use App\Http\Resources\Auth\DoctorRegisterResource;
use App\Http\Resources\Auth\PatientRegisterResource;
use App\Http\Resources\Auth\UpdatePatientResource;
use App\Models\User;
use App\Models\UserPatient;
use App\Services\OtpService\IOTPService;
use Illuminate\Validation\ValidationException;
use DB;
use stdClass;

class RegisterController extends Controller
{
    private IOTPService $otpService;

    public function __construct(IOTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function storePatient(PatientRegisterRequest $request): PatientRegisterResource
    {
        $user = User::with('userPatientData')->where('phone_number', '=', $request->validated('phone_number'))->first();
        if ($user)
            throw new UserAlreadyExistException(__("register.errros.phone_number_already_registered"), 409);

        DB::transaction(function () use ($request) {
            $user = User::create([
                "phone_number" => $request->validated("phone_number"),
                "name" => $request->validated("name"),
                "password" => "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", // password
                "email" => null
            ]);

            UserPatient::create([
                "user_id" => $user->id,
                "ssn" => $request->validated('ssn')
            ]);

            $user->roles()->sync([$request->validated('role')]);

        });

        $user = User::where('phone_number', '=', $request->validated('phone_number'))->first();
        $userPatient = UserPatient::where('user_id', '=', $user->id)->first();

        $otpCode = $this->otpService->sendOtp($user);

        $user->ssn = $userPatient->ssn;
        $user->otp_created_at = $otpCode->created_at;
        $user->otp_updated_at = $otpCode->updated_at;
        $user->otp_timeout = 30000; // miliseconds - 10 seconds

        return new PatientRegisterResource($user);
    }

    public function updatePatient(UpdatePatientRequest $request, string $phoneNumber): UpdatePatientResource
    {
        $user = User::with('userPatientData')->where('phone_number', '=', $phoneNumber)->first();
        if (!$user)
            throw ValidationException::withMessages(["phone_number" => __("login.errros.wrong_phone_number")]);

        $user->update($request->only([
            'name',
            'email',
            'phone_number'
        ]));

        $user->userPatientData()->update($request->only([
            'ssn',
            'birth_date',
            'gender'
        ]));

        return new UpdatePatientResource($user);
    }

    public function storeDoctor(DoctorRegisterRequest $request): DoctorRegisterResource
    {
        throw new \Exception("Unimpelemented");
    }
}
