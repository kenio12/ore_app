<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('app_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // 基本情報
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['in_development', 'completed']);
            $table->enum('publish_status', ['public', 'private']);
            $table->string('github_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->json('genres');
            $table->json('app_types');
            $table->json('screenshots')->nullable();
            $table->integer('development_period_years')->default(0);
            $table->integer('development_period_months')->default(0);

            // 開発ストーリー
            $table->text('motivation')->nullable();
            $table->text('challenges')->nullable();
            $table->text('devised_points')->nullable();
            $table->text('learnings')->nullable();
            $table->text('future_plans')->nullable();

            // 環境情報
            $table->json('hardware_info')->nullable();
            $table->json('software_info')->nullable();
            $table->json('architecture_info')->nullable();
            $table->json('backend_info')->nullable();
            $table->json('frontend_info')->nullable();
            $table->json('database_info')->nullable();
            $table->json('other_info')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_posts');
    }
}; 