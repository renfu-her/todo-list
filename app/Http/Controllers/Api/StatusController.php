<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $statuses = Status::withCount('todos')->get();

        return response()->json([
            'success' => true,
            'data' => $statuses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:statuses',
            'color' => 'required|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $status = Status::create([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status created successfully',
            'data' => $status,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status): JsonResponse
    {
        $status->load(['todos' => function ($query) {
            $query->with(['category', 'priority', 'creator', 'assignee']);
        }]);

        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Status $status): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:statuses,name,' . $status->id,
            'color' => 'sometimes|required|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $status->update($request->only(['name', 'color']));

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $status,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status): JsonResponse
    {
        // Check if status has todos
        if ($status->todos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete status with existing todos',
            ], 422);
        }

        $status->delete();

        return response()->json([
            'success' => true,
            'message' => 'Status deleted successfully',
        ]);
    }
}
