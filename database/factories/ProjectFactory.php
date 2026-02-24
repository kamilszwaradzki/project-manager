<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => $this->faker->words(3, true) . ' Project',
            'description' => $this->faker->paragraph(2),
            'status'      => ProjectStatus::cases()[array_rand(ProjectStatus::cases())]->value,
            // 'user_id' będzie nadpisywane w seederze
        ];
    }
}