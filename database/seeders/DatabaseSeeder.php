<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Enums\TaskPriority;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Project::truncate();
        Task::truncate();

        $users = User::factory()->count(3)->create();

        $projects = Project::factory()
            ->count(10)
            ->create([
                'user_id' => fn() => $users->random()->id,  // losowy właściciel
            ]);

        // Dla każdego projektu tworzymy 5–15 zadań
        foreach ($projects as $project) {
            Task::factory()
                ->count(rand(5, 15))
                ->create([
                    'project_id' => $project->id,
                    'assigned_to' => $users->random()->id,   // losowy przypisany
                    'priority' => TaskPriority::cases()[array_rand(TaskPriority::cases())]->value,
                ]);
        }

        $this->command->info('Dodano ' . $projects->count() . ' projektów i ' . Task::count() . ' zadań!');
    }
}