<?php

namespace App\Http\Controllers;

use App\Models\Comment as ModelsComment;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)//: RedirectResponse
    {
        $validated = $request->validate([
            'body' => 'required|string|min:3|max:2000',
        ]);

        $c = $task->comments()->create([
            'body'    => $validated['body'],
            'user_id' => auth()->id(),
        ]);

        return response()->json([
                    'id'         => $c->id,
                    'body'       => $c->body,
                    'created_at' => $c->created_at->diffForHumans(),
                    'user'       => ['name' => $c->user->name ?? 'Użytkownik'],
                ]);
        // return redirect()->back()
        //     ->with('success', 'Komentarz dodany!');
    }

    public function destroy(ModelsComment $comment): RedirectResponse
    {
        // Prosta autoryzacja – tylko autor lub właściciel zadania/projektu
        if ($comment->user_id !== auth()->id() && $comment->task->project->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->back()
            ->with('success', 'Komentarz usunięty.');
    }
}
