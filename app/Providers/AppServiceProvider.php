<?php

namespace App\Providers;

use App\Services\OtpService\OtpWrapper\IOtpWrapperService;
use App\Services\OtpService\OtpWrapper\OtpWrapperService;
use App\Services\OtpService\Watzap\IWatzapOtpService;
use App\Services\OtpService\Watzap\WatzapOtpService;
use App\Services\SimrsService\DoctorService\DoctorService;
use App\Services\SimrsService\DoctorService\IDoctorService;
use App\Services\SimrsService\PatientService\IPatientService;
use App\Services\SimrsService\PatientService\PatientService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // binding interface -> implementation
        $this->app->bind(IOtpWrapperService::class, OtpWrapperService::class);
        $this->app->bind(IWatzapOtpService::class, WatzapOtpService::class);
        $this->app->bind(IPatientService::class, PatientService::class);
        $this->app->bind(IDoctorService::class, DoctorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set localization
        $this->app->setLocale(config('app.locale'));
    }
}
