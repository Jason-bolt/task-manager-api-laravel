<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        return response()->json(
            [
                'message' => 'Server working'
            ]
            );
    }

    public function register(Request $request) {
        try {
            $validator = validator($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            return response()->json([
                'message' => 'User created successfully',
                'data' => $newUser
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'data' => null
            ], 500);
        }
    }

    public function login(Request $request) {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('api-token')->plainTextToken;

                return response()->json([
                    'message' => 'User logged in',
                    'token' => $token
                ], 200);
            }

            return response()->json([
                'message' => 'Invalid credentials',
                'data' => null
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'data' => null
            ], 500);
        }
    }
    
    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'User logged out',
                'data' => null
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'data' => null
            ], 500);
        }
    }
}
