<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('apps_v2', function (Blueprint $table) {
            // 新しいカラムを追加するだけ
            $table->text('development_trigger')->nullable(); // 開発のきっかけ
            $table->text('development_hardship')->nullable(); // 開発の苦しい話
            $table->text('development_tearful')->nullable(); // 開発の泣ける話
            $table->text('development_enjoyable')->nullable(); // 開発の楽しい話
            $table->text('development_funny')->nullable(); // 開発の笑える話
            $table->text('development_impression')->nullable(); // 開発を通して感じたこと
            $table->text('development_oneword')->nullable(); // 開発を終えて一言
        });
    }

    public function down()
    {
        Schema::table('apps_v2', function (Blueprint $table) {
            // 追加したカラムを削除
            $table->dropColumn([
                'development_trigger',
                'development_hardship',
                'development_tearful',
                'development_enjoyable',
                'development_funny',
                'development_impression',
                'development_oneword'
            ]);
        });
    }
}; 