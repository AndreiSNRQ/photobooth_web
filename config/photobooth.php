<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Storage Driver
    |--------------------------------------------------------------------------
    | Options: 'local', 'cloudinary'
    */
    'storage_driver' => env('PHOTOBOOTH_STORAGE_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Session Expiry
    |--------------------------------------------------------------------------
    | Hours before a gallery session expires
    */
    'session_expiry_hours' => env('PHOTOBOOTH_SESSION_EXPIRY_HOURS', 24),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    | Shared secret between kiosk and this server. Leave null to disable.
    */
    'api_key' => env('PHOTOBOOTH_API_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Pricing
    |--------------------------------------------------------------------------
    */
    'default_price' => env('PHOTOBOOTH_DEFAULT_PRICE', 100),
];
