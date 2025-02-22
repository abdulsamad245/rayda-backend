<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(
        path: "/api/v1/register",
        summary: "Register a new user",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/RegisterRequest")
        ),
        responses: [
            new OA\Response(response: 201, description: "User registered successfully"),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->authService->register($request->validated());
            return ApiResponse::success('User registered successfully', new UserResource($user), 201);
        } catch (\Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage());
            return ApiResponse::error('Error registering user', null, 500);
        }
    }

    #[OA\Post(
        path: "/api/v1/login",
        summary: "Log in a user",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/LoginRequest")
        ),
        responses: [
            new OA\Response(response: 200, description: "User logged in successfully"),
            new OA\Response(response: 401, description: "Invalid credentials"),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->login($request->validated());

            if (!$user) {
                return ApiResponse::error('Invalid credentials', null, 401);
            }

            return ApiResponse::success('User logged in successfully', [
                'token' => $user->token,
                'user' => new UserResource($user)
            ]);
        } catch (\Exception $e) {
            Log::error('Error logging in user: ' . $e->getMessage());
            return ApiResponse::error('Error logging in user', null, 500);
        }
    }

    #[OA\Post(
        path: "/api/v1/logout",
        summary: "Log out a user",
        tags: ["Authentication"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "User logged out successfully"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function logout()
    {
        try {
            $this->authService->logout(auth()->user());
            return ApiResponse::success('User logged out successfully');
        } catch (\Exception $e) {
            Log::error('Error logging out user: ' . $e->getMessage());
            return ApiResponse::error('Error logging out user', null, 500);
        }
    }

  
}
