<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('apps', function (Blueprint $table) {
            // セキュリティ対策
            $table->json('security_measures')->nullable()->after('screenshots');
            $table->json('performance_optimizations')->nullable()->after('security_measures');
            $table->json('testing_tools')->nullable()->after('performance_optimizations');
            $table->json('monitoring_tools')->nullable()->after('testing_tools');
            $table->json('code_quality_tools')->nullable()->after('monitoring_tools');
            $table->text('security_notes')->nullable()->after('code_quality_tools');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apps', function (Blueprint $table) {
            $table->dropColumn([
                'security_measures',
                'performance_optimizations',
                'testing_tools',
                'monitoring_tools',
                'code_quality_tools',
                'security_notes'
            ]);
        });
    }
}; 