<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('project')->latest()->paginate(15);
        return view('task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        return view('task.form', [
            'project' => $project,
            'task'    => new Task(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'status'      => 'required|in:todo,in-progress,review,done',
            'due_date'    => 'nullable|date|after_or_equal:today',
        ]);

        $project->tasks()->create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Zadanie zostało dodane!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Task $task)
    {
        // Opcjonalna walidacja: czy task należy do projektu
        abort_unless($task->project_id === $project->id, 404);

        return view('task.form', compact('project', 'task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        abort_unless($task->project_id === $project->id, 404);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'status'      => 'required|in:todo,in-progress,review,done',
            'due_date'    => 'nullable|date|after_or_equal:today',
        ]);

        $task->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Zadanie zostało zaktualizowane!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Task $task)
    {
        abort_unless($task->project_id === $project->id, 404);

        $task->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Zadanie usunięte.');
    }
}
