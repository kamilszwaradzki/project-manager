<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with('project:id,title');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->integer('project_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority'));
        }

        $tasks = $query->orderByDesc('id')->get();

        if ($request->filled('tag')) {
            $tag = (string) $request->string('tag');
            $tasks = $tasks->filter(fn (Task $task) => in_array($tag, $task->tagsList(), true))->values();
        }

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:todo,in-progress,review,done',
            'priority'    => 'nullable|in:low,medium,high,urgent',
            'tags'        => 'nullable|string|max:255',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['tags'] = Task::normalizeTags($validated['tags'] ?? null);

        $task = Task::create($validated);

        return response()->json($task->load('project:id,title'), 201);
    }

    public function show(Task $task)
    {
        return response()->json($task->load('project:id,title', 'comments.user:id,name'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'project_id'  => 'sometimes|required|exists:projects,id',
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status'      => 'sometimes|required|in:todo,in-progress,review,done',
            'priority'    => 'sometimes|required|in:low,medium,high,urgent',
            'tags'        => 'sometimes|nullable|string|max:255',
            'due_date'    => 'sometimes|nullable|date',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
        ]);

        if (array_key_exists('tags', $validated)) {
            $validated['tags'] = Task::normalizeTags($validated['tags']);
        }

        $task->update($validated);

        return response()->json($task->fresh()->load('project:id,title'));
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }
}
