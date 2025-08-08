<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment for a todo.
     */
    public function store(Request $request, Todo $todo)
    {
        $request->validate([
            'content' => 'required|string|max:65535',
        ]);

        // Check if user has access to this todo
        if ($todo->created_by !== Auth::id() && $todo->assigned_to !== Auth::id()) {
            return back()->with('error', '您沒有權限對此任務發表評論');
        }

        $comment = $todo->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', '評論已成功發表');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        // Check if user can delete this comment
        if ($comment->user_id !== Auth::id()) {
            // Check if user is the todo creator
            $todo = Todo::find($comment->todo_id);
            if (!$todo || $todo->created_by !== Auth::id()) {
                return back()->with('error', '您沒有權限刪除此評論');
            }
        }

        $comment->delete();

        return back()->with('success', '評論已成功刪除');
    }
}
