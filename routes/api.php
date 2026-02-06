<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\divisionController;
use App\Http\Controllers\employeeController;
use App\Http\Controllers\sqlController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

Route::prefix('/user')->middleware('auth:sanctum')->name('user.')->group(function(){
    Route::get('/',[userController::class,'index']);
});

Route::name('auth.')->group(function(){
    Route::post('/login',[authController::class,'login'])->name('login');
    Route::delete('/logout',[authController::class,'logout'])->name('logout')->middleware('auth:sanctum');
});


Route::prefix('/divisions')->middleware('auth:sanctum')->name('divisions.')->group(function(){
    Route::get('/',[divisionController::class,'index'])->name('index');
});

Route::prefix('/employees')->middleware('auth:sanctum')->name('employees.')->group(function(){
    Route::get("/",[employeeController::class,'index'])->name('index');
    Route::post("/",[employeeController::class,'store'])->name('store');
    Route::get("/{id}",[employeeController::class,'show'])->name('show');
    Route::put("/{id}",[employeeController::class,'update'])->name('update');
    Route::delete("/{id}",[employeeController::class,'destroy'])->name('destroy');
});

Route::get("/nilaiRT",[sqlController::class,'nilaiRT']);

Route::get("/nilaiST",[sqlController::class,'nilaiST']);
