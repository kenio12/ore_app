<?php

namespace App\Modules\AppV2\Policies;

use App\Models\User;
use App\Modules\AppV2\Models\App;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppPolicy
{
    use HandlesAuthorization;

    /**
     * アプリの更新が許可されているかを判定
     */
    public function update(User $user, App $app)
    {
        return $user->id === $app->user_id;
    }

    /**
     * 自動保存が許可されているかを判定
     */
    public function autosave(User $user, ?App $app = null)
    {
        // 新規作成の場合は常に許可
        if ($app === null) {
            return true;
        }
        // 既存のアプリの場合は所有者のみ許可
        return $user->id === $app->user_id;
    }
} 