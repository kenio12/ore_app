<?php

namespace Tests\Feature\Modules\AppPost;

use App\Models\User;
use App\Modules\AppPost\Models\AppPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AppPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_ユーザーは一覧画面を閲覧できる(): void
    {
        // 公開済みの投稿を作成
        AppPost::factory()->count(5)->public()->create();
        
        // 非公開の投稿を作成
        AppPost::factory()->count(3)->create(['publish_status' => 'private']);

        $response = $this->get(route('app-posts.index'));

        $response->assertStatus(200)
            ->assertViewIs('AppPost::Cards.index')
            ->assertViewHas('posts')
            ->assertSee('新規投稿');

        // 公開済みの投稿のみが表示されることを確認
        $this->assertEquals(5, $response->viewData('posts')->count());
    }

    public function test_認証済みユーザーは新規投稿できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('app-posts.store'), [
            'title' => 'テストアプリ',
            'description' => 'テスト説明',
            'status' => 'completed',
            'publish_status' => 'public',
            'screenshots' => [
                UploadedFile::fake()->image('screenshot.jpg')
            ],
            'backend_info' => [
                'languages' => ['PHP', 'Python']
            ],
            // 他の必要なデータ...
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('app_posts', [
            'title' => 'テストアプリ',
            'user_id' => $user->id,
        ]);
    }

    public function test_投稿者は自身の投稿を編集できる(): void
    {
        $user = User::factory()->create();
        $post = AppPost::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('app-posts.update', $post), [
            'title' => '更新後のタイトル',
            'description' => $post->description,
            'status' => $post->status,
            'publish_status' => $post->publish_status,
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('app_posts', [
            'id' => $post->id,
            'title' => '更新後のタイトル',
        ]);
    }

    public function test_投稿者以外は投稿を編集できない(): void
    {
        $post = AppPost::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->put(route('app-posts.update', $post), [
                'title' => '更新後のタイトル',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('app_posts', [
            'id' => $post->id,
            'title' => '更新後のタイトル',
        ]);
    }

    public function test_投稿者は自身の投稿を削除できる(): void
    {
        $user = User::factory()->create();
        $post = AppPost::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete(route('app-posts.destroy', $post));

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSoftDeleted($post);
    }

    public function test_非公開の投稿は作成者のみが閲覧できる(): void
    {
        $user = User::factory()->create();
        $post = AppPost::factory()->create([
            'user_id' => $user->id,
            'publish_status' => 'private',
        ]);

        // 未認証ユーザーはアクセスできない
        $this->get(route('app-posts.show', $post))
            ->assertNotFound();

        // 他のユーザーもアクセスできない
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser)
            ->get(route('app-posts.show', $post))
            ->assertNotFound();

        // 作成者はアクセスできる
        $this->actingAs($user)
            ->get(route('app-posts.show', $post))
            ->assertOk();
    }
} 