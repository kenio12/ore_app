<?php

namespace Tests\Unit\Modules\AppPost\Policies;

use App\Models\User;
use App\Modules\AppPost\Models\AppPost;
use App\Modules\AppPost\Policies\AppPostPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppPostPolicyTest extends TestCase
{
    use RefreshDatabase;

    private AppPostPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new AppPostPolicy();
    }

    public function test_公開投稿は誰でも閲覧できる(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = AppPost::factory()->create([
            'user_id' => $user->id,
            'publish_status' => 'public',
        ]);

        $this->assertTrue($this->policy->view($otherUser, $post));
    }

    public function test_非公開投稿は作成者のみ閲覧できる(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = AppPost::factory()->create([
            'user_id' => $user->id,
            'publish_status' => 'private',
        ]);

        $this->assertTrue($this->policy->view($user, $post));
        $this->assertFalse($this->policy->view($otherUser, $post));
    }

    public function test_認証済みユーザーは投稿を作成できる(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->create($user));
    }

    public function test_作成者は投稿を更新できる(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = AppPost::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $post));
        $this->assertFalse($this->policy->update($otherUser, $post));
    }

    public function test_作成者は投稿を削除できる(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = AppPost::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $post));
        $this->assertFalse($this->policy->delete($otherUser, $post));
    }
} 