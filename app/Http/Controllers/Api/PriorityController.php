<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PriorityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $priorities = Priority::withCount('todos')->get();

        return response()->json([
            'success' => true,
            'data' => $priorities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:priorities',
            'color' => 'required|string|max:7',
            'level' => 'required|integer|min:1|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $priority = Priority::create([
            'name' => $request->name,
            'color' => $request->color,
            'level' => $request->level,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Priority created successfully',
            'data' => $priority,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Priority $priority): JsonResponse
    {
        $priority->load(['todos' => function ($query) {
            $query->with(['category', 'status', 'creator', 'assignee']);
        }]);

        return response()->json([
            'success' => true,
            'data' => $priority,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Priority $priority): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:priorities,name,' . $priority->id,
            'color' => 'sometimes|required|string|max:7',
            'level' => 'sometimes|required|integer|min:1|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $priority->update($request->only(['name', 'color', 'level']));

        return response()->json([
            'success' => true,
            'message' => 'Priority updated successfully',
            'data' => $priority,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Priority $priority): JsonResponse
    {
        // Check if priority has todos
        if ($priority->todos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete priority with existing todos',
            ], 422);
        }

        $priority->delete();

        return response()->json([
            'success' => true,
            'message' => 'Priority deleted successfully',
        ]);
    }
}
