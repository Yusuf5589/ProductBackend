<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//Login task

Route::post('/register', [UserController::class,'registerUser']);

Route::post('/login', [UserController::class,'userLogin']);


Route::post('/forgotpassword', [UserController::class, 'forgotPassword']);

Route::post('/forgotpassword/{token}/{gmail}', [UserController::class, 'forgotPasswordVerify']);



//Product task 

Route::get('/prductfind/{id}', [ProductController::class, "productFind"]);

Route::get('/productall', [ProductController::class, 'productAll']);


Route::middleware("auth:sanctum")->group(function(){

    //Product task 
    Route::post('/createproduct', [ProductController::class, 'createProduct']);

    Route::post('/updateproduct', [ProductController::class, 'updateProduct']);

    Route::get('/deleteproduct/{id}', [ProductController::class, 'deleteProduct']);

    Route::get('/logout/{id}', [UserController::class,'userLogout']);
});