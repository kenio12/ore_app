<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\App\Models\App;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'unique@example.com',
        ]);

        App::create([
            'name' => 'Test App',
            'description' => 'This is a test app',
            'user_id' => 1,
            'type' => 'web',
            'status' => 'active'
        ]);
    }
}
