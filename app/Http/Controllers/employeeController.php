<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\Pagination;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\employee;
use App\Http\Services\EmployeeService;

class employeeController extends Controller
{
    private EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }
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

    public function show(EmployeeRequest $request, string $id){
        $data = $this->employeeService->showData($id);
        if(!$data){
            return ApiResponse::error(null,"not found",404);
        }

        return ApiResponse::success($data);
    }

    public function store(EmployeeRequest $request){
        $data = $request->validated();
        $file = $request->file('image');
        $newEmployeeData =  $this->employeeService->createData($data,$file);
        return ApiResponse::success(new employee($newEmployeeData),null,'created',201);
    }

    public function update(EmployeeRequest $request,string $id){
        $data = $request->validated();
        $file = $request->file('image');
        $updatedEmployeeData =  $this->employeeService->updateData($id,$data,$file);
        if(!$updatedEmployeeData){
            return ApiResponse::error(null,"not found",404);
        }

        return ApiResponse::success(new employee($updatedEmployeeData),null,'created',201);
    }

    public function destroy(EmployeeRequest $request,string $id){
        if(!$this->employeeService->deleteData($id)){
            return ApiResponse::error(null,"not found",404);
        }

        return ApiResponse::success(null,null,'no content',204);
    }
}
