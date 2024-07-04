<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class,'registerUser']);

Route::post('/login', [UserController::class,'userLogin']);


Route::post('/forgotpassword', [UserController::class, 'forgotPassword']);

Route::post('/forgotpassword/{token}/{gmail}', [UserController::class, 'forgotPasswordVerify']);


Route::middleware("auth:sanctum")->group(function(){
    Route::get('/logout/{id}', [UserController::class,'userLogout']);
});