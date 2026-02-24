<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status'      => TaskStatus::cases()[array_rand(TaskStatus::cases())]->value,
            'priority'    => TaskPriority::cases()[array_rand(TaskPriority::cases())]->value,
            'due_date'    => $this->faker->dateTimeBetween('now', '+3 months'),
            // 'project_id' i 'assigned_to' nadpisywane w seederze
        ];
    }
}