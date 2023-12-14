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
];
