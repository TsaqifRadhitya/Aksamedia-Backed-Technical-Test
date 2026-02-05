<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\Pagination;
use App\Http\Resources\division;
use App\Http\Services\DivisionService;
use Illuminate\Http\Request;

class divisionController extends Controller
{
    private DivisionService $divisionService;
    public function __construct(DivisionService $divisionService) {
        $this->divisionService = $divisionService;
    }

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
