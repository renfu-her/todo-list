<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for a specific todo.
     */
    public function index(Request $request, Todo $todo): JsonResponse
    {
        $user = $request->user();
        
        // Check if user has access to this todo
        if ($todo->created_by !== $user->id && $todo->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $comments = $todo->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $comments,
        ]);
    }

    /**
     * Store a newly created comment for a todo.
     */
    public function store(Request $request, Todo $todo): JsonResponse
    {
        $user = $request->user();
        
        // Check if user has access to this todo
        if ($todo->created_by !== $user->id && $todo->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = $todo->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'data' => $comment,
        ], 201);
    }

    /**
     * Display the specified comment.
     */
    public function show(Request $request, Comment $comment): JsonResponse
    {
        $user = $request->user();
        
        // Check if user has access to the todo this comment belongs to
        $todo = $comment->todo;
        if ($todo->created_by !== $user->id && $todo->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $comment->load(['user', 'todo']);

        return response()->json([
            'success' => true,
            'data' => $comment,
        ]);
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment): JsonResponse
    {
        $user = $request->user();
        
        // Only the comment author can update it
        if ($comment->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment,
        ]);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        $user = $request->user();
        
        // Only the comment author or todo creator can delete it
        $todo = $comment->todo;
        if ($comment->user_id !== $user->id && $todo->created_by !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
