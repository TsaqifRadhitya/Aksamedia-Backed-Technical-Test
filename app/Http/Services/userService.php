<?php

namespace App\Http\Services;

use App\Models\User;

class userService
{
    public function getUser(string $id){
        return User::find($id);
    }
}
