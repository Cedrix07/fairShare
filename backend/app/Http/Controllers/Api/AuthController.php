<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

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
        return response()->json(['message' => 'Login!']);
    }
}
