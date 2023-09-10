<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Taptalk.io Send Message API
    |--------------------------------------------------------------------------
    |
    | This value is the API endpoint of taptalk.io send message api.
    |
    */

    'send_message_api' => env('TAPTALK_SEND_MESSAGE_API', 'https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp'),


    /*
    |--------------------------------------------------------------------------
    | Taptalk.io Get Send Status API
    |--------------------------------------------------------------------------
    |
    | This value is the API endpoint of taptalk.io to get send status api.
    |
    */

    'get_send_status_api' => env('TAPTALK_GET_SEND_STATUS_API', 'https://sendtalk-api.taptalk.io/api/v1/message/get_status'),


    /*
    |--------------------------------------------------------------------------
    | Message Type
    |--------------------------------------------------------------------------
    |
    | taptalk.io support to type of message, 'otp' and 'text'. The difference
    | between OTP type and TEXT type is based on the queue of our system.
    | The message withOTPtype will be prioritized first than the other
    | type in sending the message.
    |
    */

    'message_type' => env('TAPTALK_MESSAGE_TYPE', 'otp'),


    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | In order to use taptalk.io we need to add "API-Key" in Headers.
    |
    */

    'api_key' => env('TAPTALK_API_KEY', 'fooBarBazz'),
];
