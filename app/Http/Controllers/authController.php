<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class authController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $loginRequest){
        $validated = $loginRequest->validated();
        $credential = $this->authService->login($validated['username'],$validated['password']);
        if(!$credential){
            return ApiResponse::error([
                'username' => ['invalid credential']
            ],'unautenticated','401');
        }
        return ApiResponse::success([
            'token' => $credential['token'],
            'admin' => new UserResource($credential['user'])
        ]);
    }

    public function logout(Request $request){
        $user = $request->user();
        $this->authService->logout($user);
        return ApiResponse::success(null,null, 'logout success');
    }
}
