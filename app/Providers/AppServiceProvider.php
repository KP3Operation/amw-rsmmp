<?php

namespace App\Providers;

use App\Services\OtpService\IOtpBaseApi;
use App\Services\OtpService\OtpBaseApi;
use App\Services\OtpService\Watzap\IWatzapOtpService;
use App\Services\OtpService\Watzap\WatzapOtpService;
use App\Services\SimrsService\DoctorService\DoctorService;
use App\Services\SimrsService\DoctorService\IDoctorService;
use App\Services\SimrsService\ISimrsBaseApi;
use App\Services\SimrsService\PatientService\IPatientService;
use App\Services\SimrsService\PatientService\PatientService;
use App\Services\SimrsService\SimrsBaseApi;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // binding interface -> implementation
        $this->app->bind(IWatzapOtpService::class, WatzapOtpService::class);
        $this->app->bind(IPatientService::class, PatientService::class);
        $this->app->bind(IDoctorService::class, DoctorService::class);
        $this->app->bind(ISimrsBaseApi::class, SimrsBaseApi::class);
        $this->app->bind(IOtpBaseApi::class, OtpBaseApi::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set localization
        $this->app->setLocale(config('app.locale'));

        // Set php.ini
        ini_set('read', 120);
        ini_set('max_input_time', 120);
        ini_set('memory_limit', '100M');
    }
}
