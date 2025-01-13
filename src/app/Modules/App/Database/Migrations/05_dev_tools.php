<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dev_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->json('infrastructure')->nullable();
            $table->string('other_infrastructure')->nullable();
            $table->json('ci_cd_tools')->nullable();
            $table->string('other_ci_cd')->nullable();
            $table->json('api_tools')->nullable();
            $table->string('other_api_tools')->nullable();
            $table->json('communication_tools')->nullable();
            $table->string('other_communication_tools')->nullable();
            $table->json('mail_services')->nullable();
            $table->string('other_mail_services')->nullable();
            $table->json('monitoring_tools')->nullable();
            $table->string('other_monitoring_tools')->nullable();
            $table->text('tool_versions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('dev_tools');
    }
}; 