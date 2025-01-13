<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('app_development_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained()->onDelete('cascade');
            
            // 全項目nullable()に変更
            $table->text('motivation')->nullable()->comment('開発動機');
            $table->text('challenges')->nullable()->comment('苦労した点・課題');
            $table->text('devised_points')->nullable()->comment('工夫した点');
            $table->text('learnings')->nullable()->comment('学んだこと');
            $table->text('future_plans')->nullable()->comment('今後の展望');
            $table->text('overall_thoughts')->nullable()->comment('総合感想');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_development_stories');
    }
}; 