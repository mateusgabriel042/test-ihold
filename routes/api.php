<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\ProductsContoller;
use App\Http\Controllers\OrderContoller;

Route::group(['prefix' => 'auth'], function(){
    Route::post('/login', [AuthController::class, 'login']);
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'auth'], function(){
        Route::delete('/logout', [AuthController::class, 'logout']);
    });

    Route::apiResources([
        'products' => ProductsContoller::class,
        'orders' => OrderContoller::class
    ]);

    Route::group(['prefix' => 'user'], function(){

        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::delete('/{id}', [UserController::class, 'destroy']);

        Route::get('/search/{column}/{value}', [UserController::class, 'search']);
        Route::get('/all', [UserController::class, 'all']);
        Route::delete('multiple-deletion', [UserController::class, 'multipleDeletion']);
        Route::delete('/remove-role/{idUser}/{idRole}', [UserController::class, 'removeRole']);
        Route::delete('/remove-permission/{idUser}/{idPermission}', [UserController::class, 'removePermission']);
    });
});
