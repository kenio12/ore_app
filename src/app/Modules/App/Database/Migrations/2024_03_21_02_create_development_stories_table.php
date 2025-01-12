<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('development_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->onDelete('cascade');
            
            // 開発ストーリー（全て任意）
            $table->text('motivation')->nullable();      // 開発動機
                                                        // - このアプリを作ろうと思ったきっかけ
                                                        // - 解決したかった課題
                                                        // - 目指した理想の状態
                                                        // - 個人的な興味や関心

            $table->text('challenges')->nullable();      // 苦労した点・課題

            $table->text('devised_points')->nullable();  // 工夫した点

            $table->text('learnings')->nullable();       // 学んだこと

            $table->text('future_plans')->nullable();    // 今後の展望

            $table->text('overall_thoughts')->nullable(); // 総合感想
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_stories');
    }
}; 