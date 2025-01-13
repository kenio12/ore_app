<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('basic_dev_environments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->string('dev_team_size')->nullable();
            $table->json('virtualization')->nullable();
            $table->string('other_virtualization')->nullable();
            $table->string('os_type')->nullable();
            $table->string('os_version')->nullable();
            $table->json('editors')->nullable();
            $table->string('other_editor')->nullable();
            $table->json('version_control')->nullable();
            $table->string('other_version_control')->nullable();
            $table->string('monitor_count')->nullable();
            $table->string('main_monitor_size')->nullable();
            $table->string('main_monitor_resolution')->nullable();
            $table->text('monitor_details')->nullable();
            $table->text('dev_env_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('basic_dev_environments');
    }
}; 