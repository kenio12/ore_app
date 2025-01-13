<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hardware', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->json('device_types')->nullable();
            $table->string('other_device')->nullable();
            $table->string('cpu_type')->nullable();
            $table->text('cpu')->nullable();
            $table->string('memory_size')->nullable();
            $table->text('memory')->nullable();
            $table->json('storage_types')->nullable();
            $table->text('storage')->nullable();
            $table->text('other_hardware')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('hardware');
    }
}; 