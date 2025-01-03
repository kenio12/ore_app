<?php

namespace Database\Seeders;

use App\Modules\AppPost\Database\Seeders\AppPostSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AppPostSeeder::class,
        ]);
    }
} 