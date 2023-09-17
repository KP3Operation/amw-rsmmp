<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\InvalidDoctorId;
use App\Exceptions\UserAlreadyExistException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterDoctorRequest;
use App\Http\Requests\Auth\RegisterPatientRequest;
use App\Http\Requests\Auth\UpdateDoctorRequest;
use App\Http\Requests\Auth\UpdatePatientRequest;
use App\Http\Resources\Auth\RegisterDoctorResource;
use App\Http\Resources\Auth\RegisterPatientResource;
use App\Http\Resources\Auth\UpdateDoctorResource;
use App\Http\Resources\Auth\UpdatePatientResource;
use App\Models\User;
use App\Models\UserDoctor;
use App\Models\UserPatient;
use App\Services\OtpService\OtpWrapper\IOtpWrapperService;
use App\Services\SimrsService\DoctorService\IDoctorService;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use DB;

class RegisterController extends Controller
{
    private IOtpWrapperService $otpService;
    private IDoctorService $doctorService;

    public function __construct(IOtpWrapperService $otpService, IDoctorService $doctorService)
    {
        $this->otpService = $otpService;
        $this->doctorService = $doctorService;
    }

    public function storePatient(RegisterPatientRequest $request): RegisterPatientResource
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

        return new RegisterPatientResource($user);
    }

    public function updatePatient(UpdatePatientRequest $request, string $phoneNumber): UpdatePatientResource
    {
        $user = User::with('userPatientData')->where('phone_number', '=', $phoneNumber)->first();
        if (!$user)
            throw ValidationException::withMessages(["phone_number" => __("login.errros.wrong_phone_number")]);

        DB::transaction(function () use ($user, $request) {
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
        });

        return new UpdatePatientResource($user);
    }

    public function storeDoctor(RegisterDoctorRequest $request): RegisterDoctorResource
    {
        $userDoctor = UserDoctor::where('doctor_id', '=', $request->validated('doctor_id'))->first();
        if ($userDoctor)
            throw new UserAlreadyExistException(__("register.errros.doctor_id_already_registered"), 409);

        $simrsDoctorData = $this->doctorService->getDoctors($request->doctor_id);
        if (!$simrsDoctorData->data->first())
            throw ValidationException::withMessages(["doctor_id" => __("register.errros.invalid_doctor_id")]);

        DB::transaction(function () use ($request, $simrsDoctorData) {

            $simrsUserDoctor = $simrsDoctorData->data->first();

            $user = User::create([
                "phone_number" => $request->validated("phone_number"),
                "name" => $simrsUserDoctor->paramedicName,
                "password" => "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", // password
                "email" => null
            ]);

            UserDoctor::create([
                "user_id" => $user->id,
                "doctor_id" => $request->validated('doctor_id'),
                "smf_id" => $simrsUserDoctor->smfId,
                "smf_name" => $simrsUserDoctor->smfName,
                "sync_at" => Carbon::now()
            ]);

            $user->roles()->sync([$request->validated('role')]);
        });

        $user = User::where('phone_number', '=', $request->validated('phone_number'))->first();
        $userDoctor = UserDoctor::where('user_id', '=', $user->id)->first();

        $otpCode = $this->otpService->sendOtp($user);

        $user->smf_name = $userDoctor->smf_name;
        $user->doctor_id = $userDoctor->doctor_id;
        $user->otp_created_at = $otpCode->created_at;
        $user->otp_updated_at = $otpCode->updated_at;
        $user->otp_timeout = 30000; // miliseconds - 10 seconds

        return new RegisterDoctorResource($user);
    }

    public function updateDoctor(UpdateDoctorRequest $request, string $phoneNumber): UpdateDoctorResource
    {
        $user = User::with('userDoctorData')->where('phone_number', '=', $phoneNumber)->first();
        if (!$user)
            throw ValidationException::withMessages(["doctor_id" => __("register.errros.invalid_doctor_id")]);

        DB::transaction(function () use ($user, $request) {
            $user->update($request->only([
                'name'
            ]));

            $user->userDoctorData()->update($request->only([
                'doctor_id',
                'smf_name'
            ]));
        });

        return new UpdateDoctorResource($user);
    }
}
