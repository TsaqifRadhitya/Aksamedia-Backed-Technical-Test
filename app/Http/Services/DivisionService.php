<?php

namespace App\Http\Services;
use App\Models\division;

class DivisionService{
    public function getAllDivision(int $currentPage = 1, int $perPage = 10, string $search = null){
        $query = division::query();
        if ($search) {
            $query->whereRaw('LOWER(name) LIKE ?', [strtolower($search) . '%']);
        }

        return $query->paginate($perPage,['*'],'page',$currentPage
    )->appends(['perpage' => $perPage, 'search' => $search]);
    }
}
