<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('database', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->json('databases')->nullable();
            $table->string('other_database')->nullable();
            $table->json('orms')->nullable();
            $table->string('other_orm')->nullable();
            $table->json('caches')->nullable();
            $table->string('other_cache')->nullable();
            $table->json('db_hosting_services')->nullable();
            $table->string('other_db_hosting')->nullable();
            $table->text('database_description')->nullable();
            $table->text('database_versions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('database');
    }
}; 