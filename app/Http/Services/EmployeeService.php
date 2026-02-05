<?php

namespace App\Http\Services;

use App\Models\employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    public function createData(Array $data,UploadedFile $image){
        $path = $image->store('uploads', 'public');
        $fullLink = url(Storage::url($path));
        $data['image'] = $fullLink;
        return employee::create($data);
    }

    public function updateData(string $id,Array $data,UploadedFile $image){
        $employee = employee::find($id);
        if(!$employee){
            return null;
        }

        $oldPath = str_replace(url('storage') . '/', '', $employee->image);
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $path = $image->store('uploads', 'public');
        $fullLink = url(Storage::url($path));
        $data['image'] = $fullLink;
        $employee->update($data);
        return $employee;
    }

    public function showData(string $id){
        return employee::find($id);
    }

    public function deleteData(string $id){
        return employee::delete($id);
    }
}
