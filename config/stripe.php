<?php

return [
    'keys' => [
        'secret' => env('STRIPE_SECRET'),
        'public' => env('STRIPE_KEY'),
    ],
    'settings' => [
        'currency' => env('STRIPE_CURRENCY', 'usd'),
        'api_version' => env('STRIPE_API_VERSION', '2023-08-16'),
    ],
];