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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'dagps' => [
        'webhook_token' => env('DAGPS_WEBHOOK_TOKEN'),
        'base_url' => env('DAGPS_BASE_URL', 'http://www.dagps.net'),
        'dashboard_url' => env('DAGPS_DASHBOARD_URL'),
        'cookie' => env('DAGPS_COOKIE'),
        'macid' => env('DAGPS_MACID', '00000000-0000-0000-0000-000000000000'),
        'map_type' => env('DAGPS_MAP_TYPE', 'GOOGLE'),
        'lang' => env('DAGPS_LANG', 'en'),
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

];
