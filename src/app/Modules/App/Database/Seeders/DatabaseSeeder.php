<?php

namespace App\Modules\App\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AppSeeder::class,
            // 他のシーダーがあれば追加
        ]);
    }
} 