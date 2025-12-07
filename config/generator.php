<?php

return [
    /**
     * If any input file(image) as default will use options below.
     */
    'image' => [
        'disk' => 'public',
        'default' => 'https://placehold.co/300?text=No+Image+Available',
        'crop' => true,
        'aspect_ratio' => true,
        'width' => 300,
        'height' => 300,
    ],

    'format' => [
        'first_year' => 1970,
        'date' => 'Y-m-d',
        'month' => 'Y/m',
        'time' => 'H:i',
        'datetime' => 'Y-m-d H:i:s',
        'limit_text' => 100,
    ],

    /**
     * Sidebar menu configuration
     * route_name: explicit route name (without .index), if not provided will be auto-generated
     */
    'sidebars' => [
        [
            'header' => 'Master Data',
            'permissions' => ['factory view', 'paket view', 'client view', 'mesin view', 'unit view'],
            'menus' => [
                [
                    'title' => 'Data Pabrik',
                    'icon' => '<i class="bi bi-building"></i>',
                    'route' => '/factories',
                    'route_name' => 'factories.index',
                    'permission' => 'factory view',
                    'permissions' => [],
                    'submenus' => [],
                ],
                [
                    'title' => 'Data Paket',
                    'icon' => '<i class="bi bi-box-seam"></i>',
                    'route' => '/pakets',
                    'route_name' => 'pakets.index',
                    'permission' => 'paket view',
                    'permissions' => [],
                    'submenus' => [],
                ],
                [
                    'title' => 'Data Client',
                    'icon' => '<i class="bi bi-people"></i>',
                    'route' => '/clients',
                    'route_name' => 'clients.index',
                    'permission' => 'client view',
                    'permissions' => [],
                    'submenus' => [],
                ],
                [
                    'title' => 'Data Mesin',
                    'icon' => '<i class="bi bi-gear"></i>',
                    'route' => '/mesins',
                    'route_name' => 'mesins.index',
                    'permission' => 'mesin view',
                    'permissions' => [],
                    'submenus' => [],
                ],
                [
                    'title' => 'Satuan',
                    'icon' => '<i class="bi bi-rulers"></i>',
                    'route' => '/units',
                    'route_name' => 'units.index',
                    'permission' => 'unit view',
                    'permissions' => [],
                    'submenus' => [],
                ],
            ],
        ],
        [
            'header' => 'Pembelian Paket',
            'permissions' => ['transaksi pembelian view', 'laporan pembelian view'],
            'menus' => [
                [
                    'title' => 'Transaksi Pembelian',
                    'icon' => '<i class="bi bi-cart-plus"></i>',
                    'route' => '/transaksi-pembelians',
                    'route_name' => 'transaksi-pembelians.index',
                    'permission' => 'transaksi pembelian view',
                    'permissions' => [],
                    'submenus' => [],
                ],
                [
                    'title' => 'Laporan Pembelian',
                    'icon' => '<i class="bi bi-file-earmark-bar-graph"></i>',
                    'route' => '/laporan-pembelian',
                    'route_name' => 'laporan-pembelian.index',
                    'permission' => 'laporan pembelian view',
                    'permissions' => [],
                    'submenus' => [],
                ],
            ],
        ],
        [
            'header' => 'Proses Mesin',
            'permissions' => ['transaksi mesin view', 'ringkasan mesin view', 'nota pabrik view', 'nota penjualan view'],
            'menus' => [
                [
                    'title' => 'Transaksi Mesin',
                    'icon' => '<i class="bi bi-cpu"></i>',
                    'route' => '/transaksi-mesins',
                    'route_name' => 'transaksi-mesins.index',
                    'permission' => 'transaksi mesin view',
                    'permissions' => [],
                    'submenus' => [],
                ],
                [
                    'title' => 'Ringkasan',
                    'icon' => '<i class="bi bi-clipboard-data"></i>',
                    'route' => '/ringkasan-mesin',
                    'route_name' => 'ringkasan-mesin.index',
                    'permission' => 'ringkasan mesin view',
                    'permissions' => [],
                    'submenus' => [],
                ],
                [
                    'title' => 'Cetak Nota Pabrik',
                    'icon' => '<i class="bi bi-printer"></i>',
                    'route' => '/nota-pabrik',
                    'route_name' => 'nota-pabrik.index',
                    'permission' => 'nota pabrik view',
                    'permissions' => [],
                    'submenus' => [],
                ],
                [
                    'title' => 'Cetak Nota Penjualan',
                    'icon' => '<i class="bi bi-receipt"></i>',
                    'route' => '/nota-penjualan',
                    'route_name' => 'nota-penjualan.index',
                    'permission' => 'nota penjualan view',
                    'permissions' => [],
                    'submenus' => [],
                ],
            ],
        ],
        [
            'header' => 'File Manager',
            'permissions' => ['nota history view'],
            'menus' => [
                [
                    'title' => 'Riwayat Nota',
                    'icon' => '<i class="bi bi-archive"></i>',
                    'route' => '/nota-histories',
                    'route_name' => 'nota-histories.index',
                    'permission' => 'nota history view',
                    'permissions' => [],
                    'submenus' => [],
                ],
            ],
        ],
        [
            'header' => 'Settings',
            'permissions' => ['user view', 'role & permission view'],
            'menus' => [
                [
                    'title' => 'Users',
                    'icon' => '<i class="bi bi-people-fill"></i>',
                    'route' => '/users',
                    'route_name' => 'users.index',
                    'permission' => 'user view',
                    'permissions' => [],
                    'submenus' => [],
                ],
                [
                    'title' => 'Roles & Permissions',
                    'icon' => '<i class="bi bi-person-check-fill"></i>',
                    'route' => '/roles',
                    'route_name' => 'roles.index',
                    'permission' => 'role & permission view',
                    'permissions' => [],
                    'submenus' => [],
                ],
            ],
        ],
    ],
];
