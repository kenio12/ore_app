<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('basic_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->onDelete('cascade');
            
            // 基本情報（全て必須）
            $table->string('title');                    // アプリ名
            $table->text('description');                // アプリの紹介
            $table->string('status');                   // 公開状態
            $table->string('demo_url');                 // デモURL
            $table->string('github_url');               // GitHubリポジトリURL
            $table->string('app_status');               // アプリの状態
            $table->string('app_type');                 // アプリの種類
            $table->integer('development_period_years'); // 開発期間（年）
            $table->integer('development_period_months');// 開発期間（月）
            $table->json('genres');                     // ジャンル（複数選択可）
            $table->json('screenshots');                // スクリーンショット（1-3枚）
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('basic_info');
    }
}; 