<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('frontend', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->json('frontend_languages')->nullable();
            $table->string('other_frontend_language')->nullable();
            $table->json('frontend_frameworks')->nullable();
            $table->string('other_frontend_framework')->nullable();
            $table->json('css_frameworks')->nullable();
            $table->string('other_css_framework')->nullable();
            $table->text('frontend_packages')->nullable();
            $table->text('frontend_versions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('frontend');
    }
}; 