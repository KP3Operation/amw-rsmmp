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
    | Bearer Token
    |--------------------------------------------------------------------------
    |
    | In order to use SIMRS we also need bearer token to authorize the request.
    |
    */

    'bearer_token' => env('SIMRS_BEARER_TOKEN', "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ODc2NmI0Zi00YTNiLTQwNzMtYjQ1Mi1iZWY3ZTg3M2RmY2QiLCJqdGkiOiJlMzZmN2VmMjNlYTAwNTc5NDFkMDhhYjQ5MWExOTI0YWJkM2UxN2M1M2U1ZTMzNjgwZGNjMGE5MWU5ZTI4MjM4OGNlZDJiMWY5ZTY4ODEzZSIsImlhdCI6MTY4MTI2ODIxMi44MzkzNzIsIm5iZiI6MTY4MTI2ODIxMi44MzkzNzUsImV4cCI6MTcxMjg5MDYxMi41ODE4NjUsInN1YiI6IjIwMjIxMjE1MDk0NDEzLTc2OTYxZGMwMGE1ZDQ4Nzc5NWQyN2NmZjVlMjJjODVjIiwic2NvcGVzIjpbXX0.esBNNYVTJGsbiOM0LKeM05YxQ5mRxONdWK5OIKD8fdR-10LV1s_HJh9Vos0xQ5vAtllvSruQu5gPLP9etcBWBrnvhpDVfOD9GrTJYwihnsl3gHjObkiVKJOXO4fv7Ok2g0bMdqsYocvNEZXsRQhlx7DpPUxQViKBBR0giUydDQI-KprSmv02WA6RWDEElA90qNwn0LzWTQ8kGLhzalO0Py4Dj0R3DRYFfJ1e8S8QfoX2PEBf__ho_hCsreYneMCywepW-VfokndgvmeW8Ww1Gm0WBIUmGLGWkecPI6-k6iNXmAiV3TFcJ2xPYE0dD-c7nLvgeBb6izkNi5uPNUeMlFyMdAHWaqBjZOZ062hpeLmnr7C_rSbeGf5ld2XmgWIN5zkQmubtMa3e-ySpfNdU5PdktMSS4b1s31qHoKsmJC9KmumRIugsVSBx1tjicLocJ0lfPHFvvQJg_qBe15iZexMKShGbfL0CBuf7yd1vXLfhuVTgbfI0eC5T-2rxjjjA_4LDuFLWa-odBu9Cv54bbfmcXA_vPl66eKIvYLT28hLboCZwfrG02A4-UGjKdezACrCpczB9oivW065Y8QUb7EQ8Rs1nwG1X2kEaq7yZMLZ0lkkxqud_toGUnf1JAMIuxuUtwPSXArzVIf2jcu6wNqRiuBQucAiISEQhg2sBDBw"),

    /*
    |--------------------------------------------------------------------------
    | Access Key
    |--------------------------------------------------------------------------
    |
    | In order to use SIMRS we need to send 'access_key' in out request.
    |
    */

    'otp_with_queue' => env('SIMRS_OTP_WITH_QUEUE', false),

];
