<?php

return [

    'defaults' => [
        'guard' => 'web',
        // Không cần 'passwords' nếu không dùng reset mật khẩu
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset Settings
    |--------------------------------------------------------------------------
    |
    | Bạn có thể bỏ qua nếu không sử dụng reset mật khẩu.
    |
    */
    // 'passwords' => [
    //     'customers' => [
    //         'provider' => 'customers',
    //         'table' => 'password_resets',
    //         'expire' => 60,
    //         'throttle' => 60,
    //     ],
    //     'admins' => [
    //         'provider' => 'admins',
    //         'table' => 'password_resets',
    //         'expire' => 60,
    //         'throttle' => 60,
    //     ],
    // ],

    'password_timeout' => 10800,

];
