<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\Pagination;
use App\Http\Resources\division;
use App\Http\Services\DivisionService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class divisionController extends Controller
{
    private DivisionService $divisionService;
    public function __construct(DivisionService $divisionService) {
        $this->divisionService = $divisionService;
    }

    #[OA\Get(
        path: "/api/divisions",
        operationId: "getDivisions",
        summary: "Ambil daftar divisi",
        tags: ["Master Data"],
        description: "Mengambil data divisi dengan fitur pagination dan pencarian.",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(
        name: "page",
        in: "query",
        description: "Halaman yang ingin ditampilkan",
        required: false,
        schema: new OA\Schema(type: "integer", default: 1)
    )]
    #[OA\Parameter(
        name: "perpage",
        in: "query",
        description: "Jumlah data per halaman",
        required: false,
        schema: new OA\Schema(type: "integer", default: 10)
    )]
    #[OA\Parameter(
        name: "search",
        in: "query",
        description: "Kata kunci pencarian nama divisi",
        required: false,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "Data berhasil diambil",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "string", example: "success"),
                new OA\Property(property: "message", type: "string", example: "Data retrieved successfully"),
                new OA\Property(
                    property: "data",
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "divisions",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/DivisionResource")
                        )
                    ]
                ),
                new OA\Property(
                    property: "pagination",
                    type: "object",
                    description: "Metadata Pagination",
                    properties: [
                        new OA\Property(property: "current_page", type: "integer", example: 1),
                        new OA\Property(property: "last_page", type: "integer", example: 5),
                        new OA\Property(property: "per_page", type: "integer", example: 10),
                        new OA\Property(property: "total", type: "integer", example: 50)
                    ]
                )
            ]
        )
    )]
    public function index(Request $request){
        $page = $request->query('page',1);
        $perpage = $request->query('perpage',10);
        $search = $request->search;
        $data = $this->divisionService->getAllDivision($page,$perpage,$search);
        return ApiResponse::success([
            'divisions' => division::collection($data->collect())
        ],Pagination::getMetaData($data));
    }
}
