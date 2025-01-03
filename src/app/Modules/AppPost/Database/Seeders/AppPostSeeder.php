<?php

namespace App\Modules\AppPost\Database\Seeders;

use App\Models\User;
use App\Modules\AppPost\Models\AppPost;
use Illuminate\Database\Seeder;

class AppPostSeeder extends Seeder
{
    public function run(): void
    {
        // テストユーザーを作成
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 公開済みの完成アプリを5件作成
        AppPost::factory()
            ->count(5)
            ->public()
            ->completed()
            ->create([
                'user_id' => $user->id,
            ]);

        // 開発中のアプリを3件作成
        AppPost::factory()
            ->count(3)
            ->public()
            ->create([
                'user_id' => $user->id,
                'status' => 'in_development',
            ]);

        // 非公開のアプリを2件作成
        AppPost::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
                'publish_status' => 'private',
            ]);

        // その他のユーザーの投稿を10件作成
        AppPost::factory()
            ->count(10)
            ->public()
            ->create();
    }
} 