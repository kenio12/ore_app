<?php

namespace App\Modules\AppV2\Models;

use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    protected $table = 'screenshots';  // 'screenshots_v2' から 'screenshots' に修正

    protected $fillable = [
        'app_id',
        'cloudinary_public_id',  // CloudinaryServiceと一致
        'url',                   // CloudinaryServiceの'secure_url'と対応
        'order'
    ];

    // アクセサを追加して、モーダル用のデータ形式を統一
    public function getModalDataAttribute()
    {
        return [
            'id' => $this->id,
            'src' => $this->url,
            'public_id' => $this->cloudinary_public_id,
            'order' => $this->order
        ];
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }
} 