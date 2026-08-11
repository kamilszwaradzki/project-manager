<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:3|max:2000',
        ]);

        $comment = $task->comments()->create([
            'body'    => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        return response()->json($comment->load('user:id,name'), 201);
    }

    public function destroy(Request $request, Comment $comment)
    {
        abort_unless(
            $comment->user_id === $request->user()->id
                || $comment->task->project->user_id === $request->user()->id,
            403
        );

        $comment->delete();

        return response()->json(null, 204);
    }
}
