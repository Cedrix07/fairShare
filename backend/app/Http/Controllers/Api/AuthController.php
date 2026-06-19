<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;
    public function register(RegisterRequest $request) {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' =>$validated['password'],
        ]);

        return $this->successResponse($user, 'User registered successfully', 201);
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);

        if(!Auth::attempt($validated)) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'token' => $token,
        ], 'Login successful');
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }
}
