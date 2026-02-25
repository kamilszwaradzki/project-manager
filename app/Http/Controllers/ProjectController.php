<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::paginate(15);
        return view('project.index', [
            'projects' => $projects,
            // 'filter' => $request->filter(), // todo: add filters to index
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('project.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:not-started,in-progress,cancelled,completed,on-hold',
        ]);

        $project = Project::create($validated + ['user_id' => auth()->id()]);

        return Redirect::route('projects.show', ['project' => $project])->with('status', 'project-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('project.show', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('project.form', ['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:not-started,in-progress,cancelled,completed,on-hold',
        ]);

        $project->update($validated);

        return Redirect::route('projects.edit', ['project' => $project])->with('status', 'project-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project_name = $project->title;
        $project->delete();

        return Redirect::route('projects.index')->with('status', 'project-deleted')->with('p_name', $project_name);
    }
}
