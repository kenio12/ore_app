<?php

namespace App\Modules\AppPost\Policies;

use App\Models\User;
use App\Modules\AppPost\Models\AppPost;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppPostPolicy
{
    use HandlesAuthorization;

    /**
     * 投稿の表示権限
     */
    public function view(User $user, AppPost $post)
    {
        // 公開投稿は誰でも閲覧可能
        if ($post->publish_status === 'public') {
            return true;
        }

        // 非公開投稿は作成者のみ閲覧可能
        return $user->id === $post->user_id;
    }

    /**
     * 投稿の作成権限
     */
    public function create(User $user)
    {
        // 認証済みユーザーは全員投稿可能
        return true;
    }

    /**
     * 投稿の更新権限
     */
    public function update(User $user, AppPost $post)
    {
        // 作成者のみ更新可能
        return $user->id === $post->user_id;
    }

    /**
     * 投稿の削除権限
     */
    public function delete(User $user, AppPost $post)
    {
        // 作成者のみ削除可能
        return $user->id === $post->user_id;
    }
} 