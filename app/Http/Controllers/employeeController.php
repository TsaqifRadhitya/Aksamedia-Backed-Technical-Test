<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\Pagination;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\employee;
use App\Http\Services\EmployeeService;
use OpenApi\Attributes as OA;

class employeeController extends Controller
{
    private EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }

    #[OA\Get(
        path: "/api/employees",
        operationId: "getEmployees",
        summary: "Ambil daftar pegawai",
        tags: ["Employees"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(name: "page", in: "query", schema: new OA\Schema(type: "integer", default: 1))]
    #[OA\Parameter(name: "perpage", in: "query", schema: new OA\Schema(type: "integer", default: 10))]
    #[OA\Parameter(name: "name", in: "query", description: "Filter nama", schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "division_id", in: "query", description: "Filter divisi ID", schema: new OA\Schema(type: "string", format: "uuid"))]
    #[OA\Response(
        response: 200,
        description: "Success",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 200),
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(
                        property: "employees",
                        type: "array",
                        items: new OA\Items(ref: "#/components/schemas/EmployeeResource")
                    )
                ]),
                new OA\Property(property: "pagination", type: "object")
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
    public function index(EmployeeRequest $request){
        $page = $request->query('page',1);
        $perpage = $request->query('perpage',10);
        $name = $request->name;
        $division_id = $request->division_id;
        $data = $this->employeeService->getAllEmployee($perpage,$page,$name,$division_id);
        return ApiResponse::success([
            'employees' => $data->items()
        ],Pagination::getMetaData($data));
    }

    #[OA\Get(
        path: "/api/employees/{id}",
        operationId: "showEmployee",
        summary: "Detail pegawai",
        tags: ["Employees"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "string", format: "uuid"))]
    #[OA\Response(
        response: 200,
        description: "Success",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 200),
                new OA\Property(property: "data", ref: "#/components/schemas/EmployeeResource")
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Data Not Found",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 404),
                new OA\Property(property: "message", type: "string", example: "not found")
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
    public function show(EmployeeRequest $request, string $id){
        $data = $this->employeeService->showData($id);
        if(!$data){
            return ApiResponse::error(null,"not found",404);
        }

        return ApiResponse::success($data);
    }

    #[OA\Post(
        path: "/api/employees",
        operationId: "storeEmployee",
        summary: "Tambah pegawai baru",
        tags: ["Employees"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: "multipart/form-data",
            schema: new OA\Schema(ref: "#/components/schemas/EmployeeRequest")
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Created",
        content: new OA\JsonContent(ref: "#/components/schemas/EmployeeResource")
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
    public function store(EmployeeRequest $request){
        $data = $request->validated();
        $file = $request->file('image');
        $newEmployeeData =  $this->employeeService->createData($data,$file);
        return ApiResponse::success(new employee($newEmployeeData),null,'created',201);
    }

    #[OA\Put(
        path: "/api/employees/{id}",
        operationId: "updateEmployee",
        summary: "Update pegawai",
        description: "Gunakan method POST dengan field '_method' = 'PUT' untuk update data yang mengandung file gambar.",
        tags: ["Employees"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "string", format: "uuid"))]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: "multipart/form-data",
            schema: new OA\Schema(ref: "#/components/schemas/EmployeeRequest")
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Updated",
        content: new OA\JsonContent(ref: "#/components/schemas/EmployeeResource")
    )]
    #[OA\Response(
        response: 404,
        description: "Data Not Found",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 404),
                new OA\Property(property: "message", type: "string", example: "not found")
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
    public function update(EmployeeRequest $request,string $id){
        $data = $request->validated();
        $file = $request->file('image');
        $updatedEmployeeData =  $this->employeeService->updateData($id,$data,$file);
        if(!$updatedEmployeeData){
            return ApiResponse::error(null,"not found",404);
        }

        return ApiResponse::success(new employee($updatedEmployeeData),null,'created',201);
    }

    #[OA\Delete(
        path: "/api/employees/{id}",
        operationId: "deleteEmployee",
        summary: "Hapus pegawai",
        tags: ["Employees"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "string", format: "uuid"))]
    #[OA\Response(
        response: 204,
        description: "Succes Delete Data",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 204),
                new OA\Property(property: "message", type: "string", example: "No Content")
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Data Not Found",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "integer", example: 404),
                new OA\Property(property: "message", type: "string", example: "not found")
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
    public function destroy(EmployeeRequest $request,string $id){
        if(!$this->employeeService->deleteData($id)){
            return ApiResponse::error(null,"not found",404);
        }

        return ApiResponse::success(null,null,'no content',204);
    }
}
