<?php

namespace App\Modules\AppPost\Database\Factories;

use App\Models\User;
use App\Modules\AppPost\Models\AppPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppPostFactory extends Factory
{
    protected $model = AppPost::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['in_development', 'completed']),
            'publish_status' => $this->faker->randomElement(['public', 'private']),
            'github_url' => $this->faker->url(),
            'demo_url' => $this->faker->url(),
            'screenshots' => [$this->faker->imageUrl()],
            'motivation' => $this->faker->paragraph(),
            'challenges' => $this->faker->paragraph(),
            'devised_points' => $this->faker->paragraph(),
            'learnings' => $this->faker->paragraph(),
            'future_plans' => $this->faker->paragraph(),
            'hardware_info' => [
                'development_env' => ['Mac', 'Windows'],
            ],
            'software_info' => [
                'editors' => ['VSCode', 'IntelliJ IDEA'],
            ],
            'backend_info' => [
                'languages' => ['PHP', 'Python'],
            ],
            'frontend_info' => [
                'languages' => ['JavaScript', 'TypeScript'],
            ],
            'database_info' => [
                'databases' => ['MySQL', 'PostgreSQL'],
            ],
            'architecture_info' => [
                'patterns' => ['MVC', 'Clean Architecture'],
            ],
            'other_info' => [
                'infrastructure' => ['AWS', 'GCP'],
            ],
        ];
    }

    /**
     * 公開状態の投稿を生成
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'publish_status' => 'public',
        ]);
    }

    /**
     * 完成状態の投稿を生成
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }
} 