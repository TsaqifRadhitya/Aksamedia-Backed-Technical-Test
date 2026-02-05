<?php

namespace App\Http\Services;

use App\Models\User;
use Hash;


Class AuthService {
    public function login(string $username, string $password){
        $user = User::where('username',$username)->first();
        if(!Hash::check($password,$user->password)){
            return ;
        }

        $token = $user->createToken('bearer token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout (User $user){
        $user->currentAccessToken()->delete();
    }
}
