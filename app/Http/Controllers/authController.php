<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class authController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(
        path: "/api/login",
        operationId: "login",
        summary: "Masuk ke dalam aplikasi",
        tags: ["Auth"],
        description: "Menukarkan username dan password dengan token Bearer."
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/LoginRequest"
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Login Berhasil",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 200),
                new OA\Property(property: "message", type: "string", example: "Login successful"),
                new OA\Property(
                    property: "data",
                    type: "object",
                    properties: [
                        new OA\Property(property: "token", type: "string", example: "1|AbCdEf..."),
                        new OA\Property(
                            property: "admin",
                            ref: "#/components/schemas/UserResource"
                        )
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: "Gagal Autentikasi / Credential Salah",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 401),
                new OA\Property(property: "message", type: "string", example: "unauthenticated"),
                new OA\Property(
                    property: "errors",
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "username",
                            type: "array",
                            items: new OA\Items(type: "string", example: "invalid credential")
                        )
                    ]
                )
            ]
        )
    )]
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

    #[OA\Delete(
        path: "/api/logout",
        operationId: "logout",
        summary: "Keluar dari aplikasi",
        tags: ["Auth"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Logout Berhasil",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 200),
                new OA\Property(property: "message", type: "string", example: "logout success"),
                new OA\Property(property: "data", example: null)
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: "Unauthenticated / Token Invalid",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 401),
                new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
            ]
        )
    )]
    public function logout(Request $request){
        $user = $request->user();
        $this->authService->logout($user);
        return ApiResponse::success(null,null, 'logout success');
    }
}
