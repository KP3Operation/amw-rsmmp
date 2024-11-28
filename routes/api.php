<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Doctor\AppointmentController;
use App\Http\Controllers\Api\V1\Doctor\FeeController;
use App\Http\Controllers\Api\V1\Doctor\InpatientListController;
use App\Http\Controllers\Api\V1\Doctor\NotificationController;
use App\Http\Controllers\Api\V1\Patient\DoctorScheduleController;
use App\Http\Controllers\Api\V1\Patient\FamilyController;
use App\Http\Controllers\Api\V1\Patient\MedicalHistoryController;
use App\Http\Controllers\Api\V1\Shared\MeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [LoginController::class, 'sendOtp']);
    Route::post('/verification', [LoginController::class, 'authenticate']);
    Route::post('/register/patient', [RegisterController::class, 'storePatient']);
    Route::post('/register/doctor', [RegisterController::class, 'storeDoctor']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/logout', [LogoutController::class, 'invalidateSession']);
        Route::put('/register/patient/{phoneNumber}', [RegisterController::class, 'updatePatient']);
        Route::put('/register/doctor/{phoneNumber}', [RegisterController::class, 'updateDoctor']);

        Route::get('/me', [MeController::class, 'index']);
        Route::put('/me/{id}', [MeController::class, 'update']);
        Route::get('/me/sync', [MeController::class, 'syncData']);

        Route::group(['prefix' => 'patient'], function () {
            Route::apiResource('family', FamilyController::class);
            Route::get('/family/sync/{family}', [FamilyController::class, 'syncFamilyMember']);
            Route::get('/family/fetchsimrs/{family}', [FamilyController::class, 'fetchFamilyDataInSimrs']);
            Route::get('/medical/history/vitalsign', [MedicalHistoryController::class, 'vitalSign']);
            Route::get('/medical/history/prescriptions/detail', [MedicalHistoryController::class, 'prescriptionHistoryDetail']);
            Route::get('/medical/history/prescription', [MedicalHistoryController::class, 'prescriptionHistory']);
            Route::get('/medical/history/labresult/file/{transactionNo}', [MedicalHistoryController::class, 'labResultFile']);
            Route::get('/medical/history/labresult/detail', [MedicalHistoryController::class, 'labResultDetail']);
            Route::get('/medical/history/labresult', [MedicalHistoryController::class, 'labResult']);
            Route::get('/medical/history/encounters/details', [MedicalHistoryController::class, 'encounterListDetail']);
            Route::get('/medical/history/encounters', [MedicalHistoryController::class, 'encounterList']);
            Route::get('/doctor/schedules/format', [DoctorScheduleController::class, 'getAndFormatDoctorSchedules']);
            Route::get('/doctor/schedules', [DoctorScheduleController::class, 'index']);
            Route::post('/appointments/store', [\App\Http\Controllers\Api\V1\Patient\AppointmentController::class, 'store']);
            Route::delete('/appointments', [\App\Http\Controllers\Api\V1\Patient\AppointmentController::class, 'destroy']);
            Route::get('/appointments', [\App\Http\Controllers\Api\V1\Patient\AppointmentController::class, 'index']);
        });

        Route::group(['prefix' => 'doctor'], function () {
            Route::get('/summary/fee', [FeeController::class, 'getOverviewSummaryFee']);
            Route::get('/fee/bytrxdate', [FeeController::class, 'getFeeByTrxDate']);
            Route::get('/inpatient/cppt/registrations', [InpatientListController::class, 'getPatientRegistrationCPPT']);
            Route::get('/inpatient', [InpatientListController::class, 'index']);
            Route::get('/appointments/group', [AppointmentController::class, 'getGroupAppointment']);
            Route::get('/appointments/detail', [AppointmentController::class, 'show']);
            Route::get('/appointments', [AppointmentController::class, 'index']);
            Route::get('/notifications', [NotificationController::class, 'index']);
            Route::put('/notifications/{notification}', [NotificationController::class, 'update']);
            Route::get('/inpatient/rooms', [InpatientListController::class, 'getInpatientRooms']);
            
        });
    });
});
