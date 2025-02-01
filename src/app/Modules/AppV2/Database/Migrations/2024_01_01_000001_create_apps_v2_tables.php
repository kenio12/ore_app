<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('apps_v2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('demo_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('status')->default('draft');
            $table->string('color')->nullable();
            $table->json('completed_sections')->nullable();
            $table->json('app_types')->nullable();
            $table->json('genres')->nullable();
            $table->string('app_status')->default('in_development');
            $table->integer('development_period_years')->default(0);
            $table->integer('development_period_months')->default(0);
            $table->date('development_start_date')->nullable();
            $table->date('development_end_date')->nullable();
            $table->json('data')->nullable();
            $table->json('hardware_info')->nullable();
            $table->json('dev_env_info')->nullable();
            $table->json('architecture_info')->nullable();
            $table->json('security_info')->nullable();
            $table->json('frontend_info')->nullable();
            $table->json('backend_info')->nullable();
            $table->json('database_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('apps_v2');
    }
}; 