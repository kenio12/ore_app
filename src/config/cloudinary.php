<?php

/*
 * This file is part of the Laravel Cloudinary package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    */
    'notification_url' => null,
    'cloud_url' => env('CLOUDINARY_URL'),

    // 基本設定
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'secure' => env('CLOUDINARY_SECURE', true),

    // アップロード設定
    'upload_preset' => [
        'folder' => env('CLOUDINARY_FOLDER', 'ore_app/screenshots'),
        'resource_type' => 'image',
        'overwrite' => true,
        'unique_filename' => true,
    ],

    /**
     * Route to get cloud_image_url from Blade Upload Widget
     */
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),

    /**
     * Controller action to get cloud_image_url from Blade Upload Widget
     */
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),

    'folder' => env('CLOUDINARY_FOLDER', 'ore_app/screenshots'),
];
