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
];
