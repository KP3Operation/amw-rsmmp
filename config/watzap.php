<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Watzap Send Message URL
    |--------------------------------------------------------------------------
    |
    | This value is the url endpoint of Watzap to send whatsapp message.
    |
    */

    'send_message_url' => env('WATZAP_SEND_MESSAGE_URL', 'https://api.watzap.id/v1/send_message'),


    /*
    |--------------------------------------------------------------------------
    | Watzap validate whatsApp number
    |--------------------------------------------------------------------------
    |
    | This value is the url endpoint of Watzap to validate whatsApp phone number.
    |
    */

    'validate_whatsapp_number_url' => env('WATZAP_VALIDATE_WHATSAPP_NUMBER_URL', 'https://api.watzap.id/v1/validate_number'),


    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | In order to use Watzap we need to send 'api_key' in out request.
    |
    */

    'api_key' => env('WATZAP_API_KEY', 'fooBarBazz'),


    /*
    |--------------------------------------------------------------------------
    | Number Key
    |--------------------------------------------------------------------------
    |
    | In order to use Watzap we need to send 'number_key' in out request.
    |
    */

    'number_key' => env('WATZAP_NUMBER_KEY', 'fooBarBazz'),

    /*
    |--------------------------------------------------------------------------
    | Validate whatsApp number
    |--------------------------------------------------------------------------
    |
    | Set this value to true, to validate whatsApp phone number, before send the OTP.
    |
    */

    'validate_whatsapp_number' => env('WATZAP_VALIDATE_WHATSAPP_NUMBER', true),

    /*
    |--------------------------------------------------------------------------
    | Validate WatZap API KEY url
    |--------------------------------------------------------------------------
    |
    | Set this value to true, to validate WatZap API KEY, before send the OTP.
    |
    */

    'validate_api_key_url' => env('WATZAP_VALIDATE_API_KEY_URL', 'https://api.watzap.id/v1/validate_number'),
];
