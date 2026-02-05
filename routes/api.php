<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\divisionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login',[authController::class,'login']);
Route::delete('/logout',[authController::class,'logout']);

Route::get('/divisions',[divisionController::class,'index']);
