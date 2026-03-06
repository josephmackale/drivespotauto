<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shop Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the shop themes.
    |
    */

    'shop-default' => 'drivespot',

    'shop' => [
        'default' => [
            'name' => 'Default',
            'assets_path' => 'public/themes/shop/drivespot',
            'views_path' => 'resources/themes/default/views',

            'vite' => [
                'hot_file' => 'shop-default-vite.hot',
                'build_directory' => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
        'drivespot' => [
            'name' => 'DriveSpot Theme',

            'assets_path' => 'public/themes/shop/drivespot',
            'views_path'  => 'resources/themes/drivespot/views',

            'vite' => [
                'hot_file' => 'shop-default-vite.hot',
                'build_directory' => 'themes/shop/drivespot/build',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the admin themes.
    |
    */

    'admin-default' => 'default',

    'admin' => [
        'default' => [
            'name' => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path' => 'resources/admin-themes/default/views',

            'vite' => [
                'hot_file' => 'admin-default-vite.hot',
                'build_directory' => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
