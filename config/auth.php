<?php

return [
    'defaults' => [
        'guard' => 'users',
    ],

    'guards' => [
        'users' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],

        'companies' => [
            'driver' => 'jwt',
            'provider' => 'companies',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        'companies' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Company::class,
        ],
    ],
];
