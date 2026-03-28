<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [

    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'teacher' => [
        'driver' => 'session',
        'provider' => 'teachers',
    ],

    'cr' => [   // 👈 NEW GUARD
        'driver' => 'session',
        'provider' => 'cr_info',
    ],
],

    'providers' => [

    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],

    'teachers' => [
        'driver' => 'eloquent',
        'model' => App\Models\Teacher::class,
    ],

    'cr_info' => [   // 👈 NEW PROVIDER
        'driver' => 'eloquent',
        'model' => App\Models\CrInfo::class,
    ],
],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'teachers' => [   // optional, ikiwa unataka reset password kwa teacher
            'provider' => 'teachers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'courses' => [    // optional, ikiwa unataka reset password kwa course
            'provider' => 'courses',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
