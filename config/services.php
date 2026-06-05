<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
    'client_id'     => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect'      => env('GOOGLE_REDIRECT', 'http://localhost:8000/auth/google/callback'),
    ],

    'payu' => [
    'merchant_id' => env('PAYU_MERCHANT_ID'),
    'api_login'   => env('PAYU_API_LOGIN'),
    'api_key'     => env('PAYU_API_KEY'),
    'public_key'  => env('PAYU_PUBLIC_KEY'),
    'account_id'  => env('PAYU_ACCOUNT_ID'),
    'currency'    => env('PAYU_CURRENCY', 'PEN'),
    'mode'        => env('PAYU_MODE', 'sandbox'),
    'response'    => env('PAYU_RESPONSE_URL'),
    'confirmation'=> env('PAYU_CONFIRMATION_URL'),
    ],
    'docapi' => [
    'provider' => env('DOCAPI_PROVIDER', 'apisperu'),   // apisperu | apiperu
    'token'    => env('DOCAPI_TOKEN'),
    'base'     => env('DOCAPI_BASE', 'https://dniruc.apisperu.com/api/v1'), 
    ],
    'mercadopago' => [
    'public_key' => env('MP_PUBLIC_KEY', ''),
    'token'      => env('MP_ACCESS_TOKEN', ''),
],

];
