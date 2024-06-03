<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return response()->json([
                'errors' => 'Incorrect credentials.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();
        $userToken = $user->createToken('AppToken')->plainTextToken;

        return response()->json([
            'message' => 'You have logged in successfully.',
            'token' => $userToken,
            'user' => $user
        ], Response::HTTP_OK);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json([
            'message' => 'User registered successfully.'
        ], Response::HTTP_CREATED);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'You have been successfully logged out.'
        ], Response::HTTP_OK);
    }
}
