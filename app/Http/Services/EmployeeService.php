<?php

namespace App\Http\Services;

use App\Models\employee;

class EmployeeService
{
    public function getAllEmployee(int $perPage, $page, ?string $name = null, ?string $division_id = null){

        $query = employee::with(['division']);

        if($division_id){
            $query = $query->where('division_id','=',$division_id);
        }

        if($name){
            $query->whereRaw('LOWER(name) LIKE ?', [strtolower($name) . '%']);
        }

        return $query->paginate($perPage,['*'],'page',$page)->appends(['perpage' => $perPage, 'name' => $name,'division_id' => $division_id]);

    }

    public function showData(string $id){
        return employee::find($id);
    }

    public function deleteData(string $id){
        return employee::delete($id);
    }
}
