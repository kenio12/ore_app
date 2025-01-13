<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('architectures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->string('architecture_pattern')->nullable();
            $table->string('other_architecture')->nullable();
            $table->json('design_patterns')->nullable();
            $table->string('other_patterns')->nullable();
            $table->text('architecture_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('architectures');
    }
}; 