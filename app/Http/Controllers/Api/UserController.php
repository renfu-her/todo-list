<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of users for assignment.
     */
    public function index(): JsonResponse
    {
        $users = User::select('id', 'name', 'email')->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $user->only(['id', 'name', 'email', 'created_at']),
        ]);
    }
}
