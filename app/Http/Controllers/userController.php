<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Http\Services\userService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class userController extends Controller
{

    private userService $userService;

    public function __construct(userService $userService) {
        $this->userService = $userService;
    }

    #[OA\Get(
        path: "/api/user",
        operationId: "getProfile",
        summary: "Ambil profil user yang sedang login",
        tags: ["User"],
        description: "Mengembalikan detail data user berdasarkan token Bearer yang dikirim.",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data profil",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "string", example: "success"),
                new OA\Property(property: "message", type: "string", example: "User profile retrieved"),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/UserResource"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: "Unauthenticated / Token Invalid",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "string", example: "error"),
                new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
            ]
        )
    )]
    public function index(Request $request){
        $user = $this->userService->getUser($request->user()->id);
        return ApiResponse::success(new UserResource($user));
    }
}
