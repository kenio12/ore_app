<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('backend', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->json('backend_languages')->nullable();
            $table->string('other_backend_language')->nullable();
            $table->json('backend_frameworks')->nullable();
            $table->string('other_backend_framework')->nullable();
            $table->text('backend_packages')->nullable();
            $table->text('backend_versions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('backend');
    }
}; 