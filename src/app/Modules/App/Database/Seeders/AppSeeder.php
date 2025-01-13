<?php

namespace App\Modules\App\Database\Seeders;

use App\Modules\App\Models\App;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    public function run()
    {
        // テストデータの作成
        App::factory()->count(10)->create();
    }
} 