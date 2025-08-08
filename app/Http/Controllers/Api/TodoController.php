<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = Todo::with(['category', 'priority', 'status', 'creator', 'assignee'])
            ->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });

        // Apply filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }

        if ($request->has('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->has('is_completed')) {
            $query->where('is_completed', $request->boolean('is_completed'));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $todos = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $todos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'priority_id' => 'nullable|exists:priorities,id',
            'status_id' => 'nullable|exists:statuses,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'category_id' => $request->category_id,
            'priority_id' => $request->priority_id,
            'status_id' => $request->status_id,
            'created_by' => $request->user()->id,
            'assigned_to' => $request->assigned_to,
            'is_completed' => false,
        ]);

        $todo->load(['category', 'priority', 'status', 'creator', 'assignee']);

        return response()->json([
            'success' => true,
            'message' => 'Todo created successfully',
            'data' => $todo,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Todo $todo): JsonResponse
    {
        $user = $request->user();
        
        // Check if user has access to this todo
        if ($todo->created_by !== $user->id && $todo->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $todo->load(['category', 'priority', 'status', 'creator', 'assignee', 'comments.user']);

        return response()->json([
            'success' => true,
            'data' => $todo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo): JsonResponse
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'priority_id' => 'nullable|exists:priorities,id',
            'status_id' => 'nullable|exists:statuses,id',
            'assigned_to' => 'nullable|exists:users,id',
            'is_completed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $todo->update($request->only([
            'title', 'description', 'due_date', 'category_id', 
            'priority_id', 'status_id', 'assigned_to', 'is_completed'
        ]));

        $todo->load(['category', 'priority', 'status', 'creator', 'assignee']);

        return response()->json([
            'success' => true,
            'message' => 'Todo updated successfully',
            'data' => $todo,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Todo $todo): JsonResponse
    {
        $user = $request->user();
        
        // Only creator can delete the todo
        if ($todo->created_by !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $todo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully',
        ]);
    }

    /**
     * Toggle completion status of a todo.
     */
    public function toggleComplete(Request $request, Todo $todo): JsonResponse
    {
        $user = $request->user();
        
        // Check if user has access to this todo
        if ($todo->created_by !== $user->id && $todo->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $todo->update([
            'is_completed' => !$todo->is_completed,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todo completion status updated successfully',
            'is_completed' => $todo->is_completed,
        ]);
    }

    /**
     * Get user's todo statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Get all todos for the user
        $todos = Todo::with('status')
            ->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            })->get();
        
        $stats = [
            'total' => $todos->count(),
            'completed' => 0,
            'pending' => 0,
            'overdue' => 0,
        ];
        
        foreach ($todos as $todo) {
            // Determine status based on status relation or is_completed
            $status = $todo->status ? $todo->status->name : ($todo->is_completed ? 'Completed' : 'Pending');
            
            switch (strtolower($status)) {
                case 'completed':
                    $stats['completed']++;
                    break;
                case 'pending':
                case 'in progress':
                case 'on hold':
                case 'cancelled':
                    $stats['pending']++;
                    break;
            }
            
            // Check for overdue (due date is past and not completed)
            if ($todo->due_date && $todo->due_date < now() && strtolower($status) !== 'completed') {
                $stats['overdue']++;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
