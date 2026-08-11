<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json(Project::withCount('tasks')->get());
    }

    public function show(Project $project)
    {
        return response()->json($project->load('tasks'));
    }
}
