<?php

return [

    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        'role_pivot_key' => null,
        'permission_pivot_key' => null,
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'team_id',
    ],

    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'events_enabled' => false,
    'teams' => false,
    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,
    'use_passport_client_credentials' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],

    /**
     * Permissions for the application
     */
    'permissions' => [
        // Settings
        [
            'group' => 'users',
            'access' => [
                'user view',
                'user create',
                'user edit',
                'user delete',
            ],
        ],
        [
            'group' => 'roles & permissions',
            'access' => [
                'role & permission view',
                'role & permission create',
                'role & permission edit',
                'role & permission delete',
            ],
        ],

        // Master Data
        [
            'group' => 'factories',
            'access' => [
                'factory view',
                'factory create',
                'factory edit',
                'factory delete',
            ],
        ],
        [
            'group' => 'pakets',
            'access' => [
                'paket view',
                'paket create',
                'paket edit',
                'paket delete',
            ],
        ],
        [
            'group' => 'clients',
            'access' => [
                'client view',
                'client create',
                'client edit',
                'client delete',
            ],
        ],
        [
            'group' => 'mesins',
            'access' => [
                'mesin view',
                'mesin create',
                'mesin edit',
                'mesin delete',
            ],
        ],
        [
            'group' => 'units',
            'access' => [
                'unit view',
                'unit create',
                'unit edit',
                'unit delete',
            ],
        ],

        // Pembelian Paket
        [
            'group' => 'transaksi pembelian',
            'access' => [
                'transaksi pembelian view',
                'transaksi pembelian create',
                'transaksi pembelian edit',
                'transaksi pembelian delete',
            ],
        ],
        [
            'group' => 'laporan pembelian',
            'access' => [
                'laporan pembelian view',
            ],
        ],

        // Proses Mesin
        [
            'group' => 'transaksi mesin',
            'access' => [
                'transaksi mesin view',
                'transaksi mesin create',
                'transaksi mesin edit',
                'transaksi mesin delete',
            ],
        ],
        [
            'group' => 'ringkasan mesin',
            'access' => [
                'ringkasan mesin view',
            ],
        ],
        [
            'group' => 'nota pabrik',
            'access' => [
                'nota pabrik view',
                'nota pabrik print',
            ],
        ],
        [
            'group' => 'nota penjualan',
            'access' => [
                'nota penjualan view',
                'nota penjualan print',
            ],
        ],

        // File Manager
        [
            'group' => 'nota history',
            'access' => [
                'nota history view',
                'nota history delete',
            ],
        ],
    ],
];
