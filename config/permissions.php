<?php

return [
    'modules' => [
        'dashboard' => 'Dashboard',
        'cars' => 'Cars',
        'customers' => 'Customers',
        'payments' => 'Payments',
        'expenses' => 'Expenses',
        'agreements' => 'Agreements',
        'gps_logs' => 'DAGPS KM Logs',
        'users_manage' => 'Users & Roles',
        'permissions_manage' => 'Permissions',
    ],

    'defaults' => [
        'admin' => [
            'dashboard' => true,
            'cars' => true,
            'customers' => true,
            'payments' => true,
            'expenses' => true,
            'agreements' => true,
            'gps_logs' => true,
            'users_manage' => true,
            'permissions_manage' => true,
        ],
        'customer' => [
            'dashboard' => true,
            'cars' => false,
            'customers' => false,
            'payments' => false,
            'expenses' => false,
            'agreements' => false,
            'gps_logs' => false,
            'users_manage' => false,
            'permissions_manage' => false,
        ],
    ],
];
