<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('security', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->json('security_measures')->nullable();
            $table->string('other_security')->nullable();
            $table->json('performance_optimizations')->nullable();
            $table->string('other_performance')->nullable();
            $table->json('testing_tools')->nullable();
            $table->string('other_testing')->nullable();
            $table->json('code_quality_tools')->nullable();
            $table->string('other_code_quality')->nullable();
            $table->text('security_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('security');
    }
}; 