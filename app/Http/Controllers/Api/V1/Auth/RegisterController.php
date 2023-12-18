<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\RestApiException;
use App\Exceptions\SimrsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterDoctorRequest;
use App\Http\Requests\Auth\RegisterPatientRequest;
use App\Http\Requests\Auth\UpdateDoctorRequest;
use App\Http\Requests\Auth\UpdatePatientRequest;
use App\Http\Resources\Auth\RegisterPatientResource;
use App\Http\Resources\Auth\UpdateDoctorResource;
use App\Models\OtpCode;
use App\Models\User;
use App\Models\UserDoctor;
use App\Models\UserPatient;
use App\Services\OtpService\Watzap\IWatzapOtpService;
use App\Services\SimrsService\DoctorService\IDoctorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    private IWatzapOtpService $otpService;

    private IDoctorService $doctorService;

    public function __construct(IWatzapOtpService $otpService, IDoctorService $doctorService)
    {
        $this->otpService = $otpService;
        $this->doctorService = $doctorService;
    }

    /**
     * @throws \Throwable
     */
    public function storePatient(RegisterPatientRequest $request): RegisterPatientResource
    {
        $user = User::with('userPatientData')
            ->where('phone_number', '=', format_phone_number($request->validated('phoneNumber')))
            ->first();

        if ($user) {
            throw new RestApiException('No. Handphone sudah terdaftar', 409);
        }

        $userPatientBySsn = UserPatient::where('ssn', $request->validated('ssn'))->first();

        if ($userPatientBySsn) {
            throw new RestApiException('NIK sudah terdaftar', 402);
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'phone_number' => format_phone_number($request->validated('phoneNumber')),
                'name' => $request->validated('name'),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'email' => null,
            ]);

            UserPatient::create([
                'user_id' => $user->id,
                'ssn' => $request->validated('ssn'),
            ]);

            $user->roles()->sync([$request->validated('role')]);
        });

        $user = User::with('userPatientData')
            ->where('phone_number', '=', format_phone_number($request->validated('phoneNumber')))
            ->first();


        // Delete old user otp codes
        OtpCode::where('user_id', '=', $user->id)->delete();

        $otpCode = generate_otp(6);
        // if it is local env force the otpCode to 12345; for dev only
        // if (config('app.env') == 'local') {
        //     $otpCode = 12345;
        // }

        $otpCodeData = OtpCode::create([
            'user_id' => $user->id,
            'code' => $otpCode,
            'status' => 'unverified',
            'message_id' => null,
            'updated_at' => Carbon::now(),
        ]);

        try {
            $sendOtpResult = $this->otpService->sendOtp($user->phone_number, (string) $otpCode);
        } catch (\Exception $e) {
            $user->delete();
            $otpCodeData->delete();
            throw new SimrsException($e->getMessage(), $e->getCode());
        }

        $resource = new \stdClass();
        $resource->ssn = $user->userPatientData->ssn === '' ? $request->validated('ssn') : $user->userPatientData->ssn;
        $resource->otpCreatedAt = $otpCodeData->created_at;
        $resource->otpUpdatedAt = $otpCodeData->updated_at;
        $resource->otpTimeout = 30000; // miliseconds - 10 seconds
        $resource->userId = $user->id;
        $resource->name = $user->name;

        return new RegisterPatientResource($resource);
    }

    /**
     * @throws \Throwable
     */
    public function updatePatient(UpdatePatientRequest $request, string $phoneNumber)
    {
        $user = User::with('userPatientData')->where('phone_number', '=', $phoneNumber)->first();
        if (!$user) {
            throw new RestApiException('No. Handphone salah', 422);
        }

        DB::transaction(function () use ($user, $request) {
            $user->update([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'phone_number' => $request->validated('phoneNumber'),
            ]);

            $user->userPatientData()->update([
                'ssn' => $request->validated('ssn'),
                'birth_date' => $request->validated('birthDate'),
                'gender' => $request->validated('gender'),
            ]);
        });

        $user = User::with('userPatientData')->where('id', '=', $user->id)->first();

        if (!$user) {
            throw new RestApiException('Terjadi kesalahan sistem. Mohon menghubungi tim support kami', 500);
        }

        $resource = collect($user)->mapWithKeys(function ($value, $key) {
            return [Str::camel($key) => $value];
        })->toArray();

        return response()->json($resource);
    }

    /**
     * @throws \Throwable
     * @throws RestApiException
     */
    public function storeDoctor(RegisterDoctorRequest $request)
    {
        $user = User::where('phone_number', '=', $request->validated('phoneNumber'))->first();
        if ($user) {
            throw new RestApiException('No. Handphone sudah terdaftar', 409);
        }

        $userDoctor = UserDoctor::where('doctor_id', '=', $request->validated('doctorId'))->first();
        if ($userDoctor) {
            throw new RestApiException('ID Dokter sudah terdaftar', 409);
        }

        $simrsDoctorData = $this->doctorService->getDoctors($request->validated('doctorId'));
        if (!$simrsDoctorData->data->first()) {
            throw new RestApiException('ID Dokter tidak ditemukan pada SIMRS', 404);
        }

        DB::transaction(function () use ($request, $simrsDoctorData) {

            $simrsUserDoctor = $simrsDoctorData->data->first();

            $user = User::create([
                'phone_number' => $request->validated('phoneNumber'),
                'name' => $simrsUserDoctor->paramedicName,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'email' => null,
            ]);

            UserDoctor::create([
                'user_id' => $user->id,
                'doctor_id' => $request->validated('doctorId'),
                'smf_id' => $simrsUserDoctor->smfId,
                'smf_name' => $simrsUserDoctor->smfName,
                'sync_at' => Carbon::now(),
            ]);

            $user->roles()->sync([$request->validated('role')]);
        });

        $user = User::with('userDoctorData')
            ->where('phone_number', '=', $request->validated('phoneNumber'))->first();


        // Delete old user otp codes
        OtpCode::where('user_id', '=', $user->id)->delete();

        $otpCode = generate_otp(6);
        // if it is local env force the otpCode to 12345; for dev only
        // if (config('app.env') == 'local') {
        //     $otpCode = 12345;
        // }

        OtpCode::where('user_id', '=', $user->id)->delete();

        $otpCodeData = OtpCode::create([
            'user_id' => $user->id,
            'code' => $otpCode,
            'status' => 'unverified',
            'message_id' => null,
            'updated_at' => Carbon::now(),
        ]);

        try {
            $this->otpService->sendOtp($user->phone_number, (string) $otpCode);
        } catch (\Exception $e) {
            $user->delete();
            $otpCodeData->delete();
            throw new SimrsException($e->getMessage(), $e->getCode());
        }

        $user->smf_name = $user->userDoctorData->smf_name;
        $user->doctor_id = $user->userDoctorData->doctor_id;
        $user->doctor_photo = $user->userDoctorData->photo64;
        $user->otp_created_at = $otpCodeData->created_at;
        $user->otp_updated_at = $otpCodeData->updated_at;
        $user->otp_timeout = 30000; // miliseconds - 10 seconds

        $resource = collect($user)->mapWithKeys(function ($value, $key) {
            return [Str::camel($key) => $value];
        })->toArray();

        return response()->json($resource);
    }

    /**
     * @throws \Throwable
     * @throws RestApiException
     */
    public function updateDoctor(UpdateDoctorRequest $request, string $phoneNumber): UpdateDoctorResource
    {
        $user = User::with('userDoctorData')->where('phone_number', '=', $phoneNumber)->first();
        if (!$user) {
            throw new RestApiException('NO. Handphone salah', 422);
        }

        DB::transaction(function () use ($user, $request) {
            $user->update($request->only([
                'name',
            ]));

            $user->userDoctorData()->update([
                'doctor_id' => $request->validated('doctorId'),
                'smf_name' => $request->validated('smfName'),
            ]);
        });

        return new UpdateDoctorResource($user);
    }
}
