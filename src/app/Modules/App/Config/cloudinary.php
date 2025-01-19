<?php

return [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'secure' => env('CLOUDINARY_SECURE', true),
    'folder' => env('CLOUDINARY_FOLDER', 'ore_app/screenshots'),
    
    // デバッグ用の設定を追加
    'debug' => env('CLOUDINARY_DEBUG', false),
    'log_level' => env('CLOUDINARY_LOG_LEVEL', 'error')
]; 