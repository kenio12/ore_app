<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataColumnToAppsV2Table extends Migration
{
    public function up()
    {
        Schema::table('apps_v2', function (Blueprint $table) {
            $table->json('data')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('apps_v2', function (Blueprint $table) {
            $table->dropColumn('data');
        });
    }
} 