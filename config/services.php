<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'facebook' => [
        'client_id'     => '3520961297959781',
        'client_secret' => 'cb18ff4cb13920043523f656d60a13e8',
        'redirect'      => 'https://cars.test/login/facebook/callback',
    ],

    'twitter' => [
        'client_id'     => 'Y3qIzzfAr2VYrsBCT54dMOriJ',
        'client_secret' => 'zbyKDNafuUd58fgObuyrDCz8bZyDPTZAB6y7oFdrlRYINTEPmI',
        'redirect'      => 'https://cars.test/login/twitter/callback',
    ],

    'google' => [
        'client_id'     => '72605513129-lmh6n0p8d8a9r43c2u893tfrsq6co57i.apps.googleusercontent.com',
        'client_secret' => 'lNDMGY0W_0joNgTGyPzvWaV-',
        'redirect'      => 'http://localhost:8000//login/google/callback',
    ],

];
