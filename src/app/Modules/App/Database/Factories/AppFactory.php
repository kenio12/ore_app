<?php

namespace App\Modules\App\Database\Factories;

use App\Models\User;
use App\Modules\App\Models\App;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppFactory extends Factory
{
    protected $model = App::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(array_keys(App::getStatusOptions())),
            'app_type' => $this->faker->randomElement(array_keys(App::getAppTypeOptions())),
            'app_status' => $this->faker->randomElement(array_keys(App::getAppStatusOptions())),
            'development_period_years' => $this->faker->numberBetween(0, 5),
            'development_period_months' => $this->faker->numberBetween(0, 11),
        ];
    }
} 