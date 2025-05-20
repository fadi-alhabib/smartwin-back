<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MTN Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials and settings used when
    | interacting with the MTN CashMobile Payment Gateway.
    |
    */

    'base_url' => env('MTN_BASE_URL', 'https://cashmobile.mtnsyr.com:9000'),

    /*
    |--------------------------------------------------------------------------
    | Terminal Settings
    |--------------------------------------------------------------------------
    */

    'terminal_id'     => env('MTN_TERMINAL_ID'),
    'terminal_serial' => env('MTN_TERMINAL_SERIAL'),
    'terminal_secret' => env('MTN_TERMINAL_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | RSA Keys
    |--------------------------------------------------------------------------
    |
    | These keys are generated during terminal activation. The private key
    | is used to sign requests. MTN will provide you their public key.
    |
    */

    'private_key_path' => storage_path('app/mtn/private.key'),
    'public_key_path'  => storage_path('app/mtn/public.key'),
    'mtn_public_key_path' => storage_path('app/mtn/mtn_public.key'),

    /*
    |--------------------------------------------------------------------------
    | API Endpoints (Relative to base_url)
    |--------------------------------------------------------------------------
    */

    'endpoints' => [
        'activate'         => '/api/Terminal/Activate',
        'create_invoice'   => '/api/Invoice/Create',
        'start_payment'    => '/api/Payment/Initiate',
        'confirm_payment'  => '/api/Payment/Confirm',
        'start_refund'     => '/api/Refund/Initiate',
        'confirm_refund'   => '/api/Refund/Confirm',
        'cancel_refund'    => '/api/Refund/Cancel',
    ],

];
