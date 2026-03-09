<?php

namespace Zuse\TestTool\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TestToolController extends Controller
{
    /**
     * Store form data to database
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|min:10',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create a new user with the form data
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password123'), // Default password
                // If you have a phone field in users table, add it here
                // 'phone' => $request->phone,
            ]);

            // You can also store additional data in a separate table if needed
            // For example, if you have a messages or contacts table:
            // Message::create([
            //     'user_id' => $user->id,
            //     'phone' => $request->phone,
            //     'message' => $request->message,
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully!',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::latest()->take(10)->get(['id', 'name', 'email', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
