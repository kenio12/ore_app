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
            // 開発人数
            $table->string('dev_team_size')->nullable();
            
            // 開発環境の仮想化
            $table->json('virtualization')->nullable();
            $table->string('other_virtualization')->nullable();
            
            // OS
            $table->string('os_type')->nullable();
            $table->string('os_version')->nullable();
            
            // エディタ/IDE
            $table->json('editors')->nullable();
            $table->string('other_editor')->nullable();
            
            // バージョン管理
            $table->json('version_control')->nullable();
            $table->string('other_version_control')->nullable();
            
            // モニター環境
            $table->string('monitor_count')->nullable();
            $table->string('main_monitor_size')->nullable();
            $table->string('main_monitor_resolution')->nullable();
            $table->text('monitor_details')->nullable();
            
            // 補足情報
            $table->text('dev_env_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apps', function (Blueprint $table) {
            $table->dropColumn([
                'dev_team_size',
                'virtualization',
                'other_virtualization',
                'os_type',
                'os_version',
                'editors',
                'other_editor',
                'version_control',
                'other_version_control',
                'monitor_count',
                'main_monitor_size',
                'main_monitor_resolution',
                'monitor_details',
                'dev_env_notes'
            ]);
        });
    }
};
