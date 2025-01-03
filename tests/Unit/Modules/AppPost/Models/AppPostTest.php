<?php

namespace Tests\Unit\Modules\AppPost\Models;

use App\Models\User;
use App\Modules\AppPost\Models\AppPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_投稿は所有者を持つ(): void
    {
        $user = User::factory()->create();
        $post = AppPost::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    public function test_投稿は配列としてスクリーンショットを持つ(): void
    {
        $post = AppPost::factory()->create([
            'screenshots' => ['image1.jpg', 'image2.jpg']
        ]);

        $this->assertIsArray($post->screenshots);
        $this->assertCount(2, $post->screenshots);
    }

    public function test_投稿は配列として環境情報を持つ(): void
    {
        $post = AppPost::factory()->create([
            'hardware_info' => ['development_env' => ['Mac', 'Windows']],
            'software_info' => ['editors' => ['VSCode']],
            'backend_info' => ['languages' => ['PHP']],
            'frontend_info' => ['languages' => ['JavaScript']],
            'database_info' => ['databases' => ['MySQL']],
            'architecture_info' => ['patterns' => ['MVC']],
            'other_info' => ['infrastructure' => ['AWS']],
        ]);

        $this->assertIsArray($post->hardware_info);
        $this->assertIsArray($post->software_info);
        $this->assertIsArray($post->backend_info);
        $this->assertIsArray($post->frontend_info);
        $this->assertIsArray($post->database_info);
        $this->assertIsArray($post->architecture_info);
        $this->assertIsArray($post->other_info);
    }

    public function test_投稿はソフトデリートされる(): void
    {
        $post = AppPost::factory()->create();
        
        $post->delete();

        $this->assertSoftDeleted('app_posts', ['id' => $post->id]);
        $this->assertDatabaseHas('app_posts', ['id' => $post->id]);
    }

    public function test_公開済み投稿のスコープが機能する(): void
    {
        // 公開投稿を3件作成
        AppPost::factory()->count(3)->public()->create();
        
        // 非公開投稿を2件作成
        AppPost::factory()->count(2)->create(['publish_status' => 'private']);

        $publicPosts = AppPost::where('publish_status', 'public')->get();
        
        $this->assertCount(3, $publicPosts);
    }

    public function test_完成済み投稿のスコープが機能する(): void
    {
        // 完成済み投稿を3件作成
        AppPost::factory()->count(3)->completed()->create();
        
        // 開発中投稿を2件作成
        AppPost::factory()->count(2)->create(['status' => 'in_development']);

        $completedPosts = AppPost::where('status', 'completed')->get();
        
        $this->assertCount(3, $completedPosts);
    }
} 