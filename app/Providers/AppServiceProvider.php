<?php

namespace App\Providers;

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Services\OtpService\IOTPService;
use App\Services\OtpService\WatzapOTPService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        // binding interface -> implementation
        $this->app->when(LoginController::class)
            ->needs(IOTPService::class)
            ->give(function () {
                return new WatzapOTPService();
            });


        $this->app->when(RegisterController::class)
            ->needs(IOTPService::class)
            ->give(function () {
                return new WatzapOTPService();
            });
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
