<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->json('screenshots')->nullable()->comment('スクリーンショット画像の配列');
            $table->string('demo_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('status')->default('draft')
                ->comment('公開ステータス: draft, published');
            $table->string('color')->nullable();
            $table->json('completed_sections')->nullable();
            $table->string('app_type')
                ->comment('アプリの種類: web_app, ios_app, android_app, windows_app, mac_app, linux_app, game, other');
            $table->json('genres')->nullable()->comment('ジャンル（複数選択可）');
            $table->string('app_status')
                ->comment('開発状態: completed（完成）, in_development（開発中）');
            $table->integer('development_period_years')->default(0)->comment('開発期間（年）');
            $table->integer('development_period_months')->default(0)->comment('開発期間（月）');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('app_screenshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_screenshots');
        Schema::dropIfExists('apps');
    }
}; 