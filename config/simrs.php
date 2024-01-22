<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SIMRS base URL
    |--------------------------------------------------------------------------
    |
    | This value is the url endpoint of SIMRS rest-api endpoint.
    |
    */

    'base_url' => env('SIMRS_BASE_URL', 'http://103.111.202.214/live/WebService'),

    /*
    |--------------------------------------------------------------------------
    | Access Key
    |--------------------------------------------------------------------------
    |
    | In order to use SIMRS we need to send 'access_key' in out request.
    |
    */

    'access_key' => env('SIMRS_ACCESS_KEY', 'MWApA'),

    /*
    |--------------------------------------------------------------------------
    | OTP code message
    |--------------------------------------------------------------------------
    |
    | We can use this config value to use customize OTP code message.
    | Please do not delete the otp code placeholder ( :otpcode )
    |
    */

    'otp_message' => env('SIMRS_OTP_MESSAGE', 'Kode OTP anda untuk masuk ke aviat web mobile adalah :otpcode'),
];
