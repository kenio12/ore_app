<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('apps_v2', function (Blueprint $table) {
            $table->dropColumn('screenshots');
        });
    }

    public function down()
    {
        Schema::table('apps_v2', function (Blueprint $table) {
            $table->json('screenshots')->nullable();
        });
    }
}; 