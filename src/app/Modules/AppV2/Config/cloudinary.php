<?php

return [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'secure' => env('CLOUDINARY_SECURE', true),
    'folder' => env('CLOUDINARY_FOLDER', 'ore_app/screenshots'),
    
    'transformation' => [
        'quality' => 'auto:eco',      // 最高の圧縮率
        'fetch_format' => 'auto',     // 最適なフォーマットに自動変換
        'width' => 800,               // 最大幅を制限
        'crop' => 'limit',            // アスペクト比を保持しながらリサイズ
        'compression' => 'low'        // 低圧縮（画質優先）
    ],
    
    // デバッグ設定
    'debug' => env('CLOUDINARY_DEBUG', false),
    'log_level' => env('CLOUDINARY_LOG_LEVEL', 'error')
]; 