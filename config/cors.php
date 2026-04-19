<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CORS — allow kiosk (Electron) and PWA (localhost:3000) to call the API
    |--------------------------------------------------------------------------
    */
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',   // PWA dev server
        'http://127.0.0.1:3000',
        'http://localhost:8080',
        'null',                    // Electron (file:// origin shows as "null")
    ],

    'allowed_origins_patterns' => ['*'],  // allow all in dev

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
