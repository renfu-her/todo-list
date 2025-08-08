<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('todos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            return back()->withErrors($validator)->withInput();
        }

        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'category_id' => $request->category_id,
            'priority_id' => $request->priority_id,
            'status_id' => $request->status_id,
            'created_by' => Auth::id(),
            'assigned_to' => $request->assigned_to,
            'is_completed' => false,
        ]);

        return redirect()->route('todos.index')->with('success', 'Todo created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        $user = Auth::user();
        
        // Check if user has access to this todo
        if ($todo->created_by !== $user->id && $todo->assigned_to !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $todo->load(['category', 'priority', 'status', 'creator', 'assignee', 'comments.user']);

        return view('todos.show', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        $user = Auth::user();
        
        // Check if user has access to this todo
        if ($todo->created_by !== $user->id && $todo->assigned_to !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $user = Auth::user();
        
        // Check if user has access to this todo
        if ($todo->created_by !== $user->id && $todo->assigned_to !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'priority_id' => 'nullable|exists:priorities,id',
            'status_id' => 'nullable|exists:statuses,id',
            'assigned_to' => 'nullable|exists:users,id',
            'is_completed' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $todo->update($request->only([
            'title', 'description', 'due_date', 'category_id', 
            'priority_id', 'status_id', 'assigned_to', 'is_completed'
        ]));

        return redirect()->route('todos.index')->with('success', 'Todo updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $user = Auth::user();
        
        // Only creator can delete the todo
        if ($todo->created_by !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $todo->delete();

        return redirect()->route('todos.index')->with('success', 'Todo deleted successfully!');
    }

    /**
     * Toggle todo completion status.
     */
    public function toggleComplete(Todo $todo)
    {
        $user = Auth::user();
        
        // Check if user has access to this todo
        if ($todo->created_by !== $user->id && $todo->assigned_to !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $todo->update(['is_completed' => !$todo->is_completed]);

        return response()->json([
            'success' => true,
            'is_completed' => $todo->is_completed,
        ]);
    }
}
