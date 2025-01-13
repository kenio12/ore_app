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
    |
    | An HTTP or HTTPS URL to notify your application (a webhook) when the process of uploads, deletes, and any API
    | that accepts notification_url has completed.
    |
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),


    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary settings. Cloudinary is a cloud hosted
    | media management service for all file uploads, storage, delivery and transformation needs.
    |
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /**
     * Upload Preset From Cloudinary Dashboard
     * 
     * プリセットを使用することで、クライアントサイドでの直接アップロードが可能になります。
     * セキュリティのため、unsigned_uploadingを有効にしたプリセットを使用してください。
     */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /**
     * スクリーンショットのアップロード設定
     */
    'screenshots' => [
        'folder' => env('CLOUDINARY_FOLDER', 'ore_app/screenshots'),
        'transformation' => [
            'quality' => 'auto',
            'fetch_format' => 'auto',
            'width' => 800,
            'height' => 600,
            'crop' => 'limit'
        ]
    ],

    /**
     * Route to get cloud_image_url from Blade Upload Widget
     */
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE', 'cloudinary.upload'),

    /**
     * Controller action to get cloud_image_url from Blade Upload Widget
     */
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION', 'CloudinaryController@upload'),

    // 基本認証情報を追加
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'secure' => env('CLOUDINARY_SECURE', true),
];
