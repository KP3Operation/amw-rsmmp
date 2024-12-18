<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Exceptions\RestApiException;
use App\Exceptions\SimrsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StoreFamilyRequest;
use App\Http\Requests\Patient\UpdateFamilyRequest;
use App\Http\Resources\Patient\StoreFamilyResource;
use App\Http\Resources\Patient\UpdateFamilyResource;
use App\Models\Family;
use App\Models\User;
use App\Models\OtpCode;
use App\Services\SimrsService\DoctorService\IDoctorService;
use App\Services\SimrsService\PatientService\IPatientService;
use App\Services\OtpService\Watzap\IWatzapOtpService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class FamilyController extends Controller
{   
    private IWatzapOtpService $otpService;
    private IPatientService $patientService;
    private IDoctorService $doctorService;

    public function __construct(IWatzapOtpService $otpService, IPatientService $patientService, IDoctorService $doctorService)
    {
        $this->otpService = $otpService;
        $this->patientService = $patientService;
        $this->doctorService = $doctorService;
    }

    public function index()
    {
        $user = User::findOrFail(auth()->user()->id);
        $serviceUnitLists = $this->patientService->getServiceUnitList('', '');
        $paramedics = $this->doctorService->getDoctors('');

        return response()->json([
            'families' => $user->families,
            'service_units' => $serviceUnitLists->data,
            'paramedics' => $paramedics->data,
        ]);
    }

    public function store(StoreFamilyRequest $request): StoreFamilyResource
    {
        $otp = OtpCode::where('code',$request->code)->where('user_id',auth()->user()->id)->first();
        if(!$otp) {
            throw new RestApiException('kode OTP tidak sesuai.', 404);
        }
        //$phoneNumber = format_phone_number($request->validated('phone_number'));

        $responseFirstAttempt = $this->patientService->getPatientFamilies($request->ssn, $request->phone_number);
        $patientData = $responseFirstAttempt->data->first();
        $family = new Family();
        $family->user_id = auth()->user()->id;
        $family->ssn = $patientData->ssn;
        $family->name = $patientData->firstName . ' ' . $patientData->middleName . ' ' . $patientData->lastName;
        $family->phone_number = format_phone_number($patientData->phoneNo);
        $family->email = $patientData->email;
        $family->gender = $patientData->sex == 'F' ? 'Perempuan' : 'Laki-Laki';
        $family->birth_date = Carbon::parse($patientData->birthDate);
        $family->patient_id = $patientData->patientId;
        $family->medical_no = $patientData->medicalNo;
        $family->guarantor_id = $patientData->guarantorId;
        $family->guarantor_name = "SELF";
        $success = $family->save();
        if(!$success) {
            throw new RestApiException('Data keluarga gagal disimpan.', 404);
        } 

        OtpCode::where('user_id', '=', auth()->user()->id)->delete();
        
        // 'ssn' => $patientData->ssn,
        //     'name' => $patientData->firstName . ' ' . $patientData->middleName . ' ' . $patientData->lastName,
        //     'phone_number' => $patientData->phoneNo,
        //     'gender' => $patientData->sex == 'F' ? 'Perempuan' : 'Laki-Laki',
        //     'birth_date' => Carbon::parse($patientData->birthDate),
        //     'email' => $patientData->email,
        //     'guarantor_id' => $patientData->guarantorId,
        //     'patient_id' => $patientData->patientId,
        //     'medical_no' => $patientData->medicalNo,

        
        // $family = Family::create($request->only(
        //     'ssn',
        //     'name',
        //     'gender',
        //     'birth_date',
        //     'email'
        // ) + [
        //     'user_id' => auth()->user()->id,
        //     'patient_id' => null,
        //     'medical_no' => null,
        //     'phone_number' => $phoneNumber,
        //     'guarantor_name' => 'SELF',
            
        // ]);

        return new StoreFamilyResource($family);
    }

    public function show(Family $family)
    {
        return response()->json([
            'family' => $family,
        ]);
    }

    public function update(UpdateFamilyRequest $request, Family $family): UpdateFamilyResource
    {
        $familySimrsDataFirstAttempt = $this->patientService->getPatientFamilies($family->ssn, $family->phone_number);
        $familyData = $familySimrsDataFirstAttempt->data->first();

        if (!$familyData) {
            $familySimrsDataSecondAttempt = $this->patientService->getPatientFamilies($family->ssn, '');
            $familyData = $familySimrsDataSecondAttempt->data->first();

            if (!$familyData) {
                // if there is no data that mean the family is not registered in SIMRS
                $family->update($request->only(
                    'ssn',
                    'name',
                    'phone_number',
                    'gender',
                    'birth_date',
                    'email'
                ));
            } else {
                $family->update($request->only(
                    'ssn',
                    'name',
                    'phone_number',
                    'gender',
                    'birth_date',
                    'email'
                ) + [
                    'user_id' => auth()->user()->id,
                    'guarantor_id' => $familyData->guarantorId,
                    'guarantor_name' => 'SELF',
                    'patient_id' => $familyData->patientId,
                    'medical_no' => $familyData->medicalNo,
                ]);
            }
        }



        return new UpdateFamilyResource($family);
    }

    public function destroy(Family $family)
    {
        $family->delete();

        return response()->json([], 204);
    }

    public function fetchNewFamilyDataInSimrs(Request $request) {
        $request->validate([
            'ssn' => 'required|min:16|max:16|unique:families,ssn',
            'phone_number' => 'required|min:10|max:13'
        ]);

        $phoneNumber = format_phone_number($request->phone_number);
        //return response()->json(['success' => false, 'message' => 'OK', 'phone'=>$phoneNumber]);
        $responseFirstAttempt = $this->patientService->getPatientFamilies($request->ssn, $phoneNumber);
        $patientData = $responseFirstAttempt->data->first();
        if (!$patientData) {
            throw new RestApiException('Keluarga tidak terdaftar di SIMRS / ada ketidaksesuaian NIK dan No telepon', 404);
        }

        // Delete old user otp codes
        OtpCode::where('user_id', '=', auth()->user()->id)->delete();
        $otpCode = generate_otp(6);

        $otpCodeData = OtpCode::create([
            'user_id' => auth()->user()->id,
            'code' => $otpCode,
            'status' => 'unverified',
            'message_id' => null,
            'updated_at' => Carbon::now(),
        ]);

        try {
            $this->otpService->sendOtp(config('app.calling_code').$phoneNumber, (string) $otpCode);
        } catch (\Exception $e) {
            $otpCodeData->delete();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
            //throw new SimrsException($e->getMessage(), $e->getCode());
        }

        return response()->json(['success' => true, 'message' => 'OK','data' => $patientData, 'phone'=>$phoneNumber]);
    }

    public function fetchFamilyDataInSimrs(Family $family)
    {
        $responseFirstAttempt = $this->patientService->getPatientFamilies($family->ssn, $family->phone_number);
        $patientData = $responseFirstAttempt->data->first();

        if (!$patientData) {
            $responseSecondtAttempt = $this->patientService->getPatientFamilies($family->ssn, '');
            $patientData = $responseSecondtAttempt->data->first();

            if (!$patientData) {
                throw new RestApiException('Keluarga tidak terdaftar di SIMRS', 404);
            }
        }

        $family->name = $patientData->firstName . ' ' . $patientData->middleName . ' ' . $patientData->lastName;
        $family->birth_date = $patientData->birthDate;
        $family->patient_id = $patientData->patientId;
        $family->medical_no = $patientData->medicalNo;
        $family->gender = $patientData->gender;
        $family->email = ($patientData->email == '') ? $family->email : $patientData->email;

        return response()->json([
            'family' => $family,
        ]);
    }

    public function syncFamilyMember(Family $family)
    {
        $responseFirstAttempt = $this->patientService->getPatientFamilies($family->ssn, $family->phone_number);
        $patientData = $responseFirstAttempt->data->first();

        if (!$patientData) {
            $responseSecondtAttempt = $this->patientService->getPatientFamilies($family->ssn, '');
            $patientData = $responseSecondtAttempt->data->first();

            if (!$patientData) {
                throw new RestApiException('Keluarga tidak terdaftar di SIMRS', 404);
            }
        }

        $family->update([
            'ssn' => $patientData->ssn,
            'name' => $patientData->firstName . ' ' . $patientData->middleName . ' ' . $patientData->lastName,
            'phone_number' => $patientData->phoneNo,
            'gender' => $patientData->sex == 'F' ? 'Perempuan' : 'Laki-Laki',
            'birth_date' => Carbon::parse($patientData->birthDate),
            'email' => $patientData->email,
            'guarantor_id' => $patientData->guarantorId,
            'patient_id' => $patientData->patientId,
            'medical_no' => $patientData->medicalNo,

        ]);

        return response([], 204);
    }
}
