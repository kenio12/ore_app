<?php

namespace App\Modules\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevelopmentStory extends Model
{
    use SoftDeletes;

    protected $table = 'app_development_stories';

    protected $fillable = [
        'app_id',
        'motivation',
        'challenges',
        'devised_points',
        'learnings',
        'future_plans',
        'overall_thoughts'
    ];

    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
} 