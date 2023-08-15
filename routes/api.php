<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;

Route::group(['prefix' => 'auth'], function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::apiResources([
    'products' => ProductsContoller::class,
    'orders' => OrderContoller::class
]);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'auth'], function(){
        Route::delete('/logout', [AuthController::class, 'logout']);
    });

    Route::group(['prefix' => 'user'], function(){
        Route::get('/', [UserController::class, 'index']);
        Route::get('/all', [UserController::class, 'all']);
        Route::get('/search/{column}/{value}', [UserController::class, 'search']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::delete('multiple-deletion', [UserController::class, 'multipleDeletion']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::delete('/remove-role/{idUser}/{idRole}', [UserController::class, 'removeRole']);
        Route::delete('/remove-permission/{idUser}/{idPermission}', [UserController::class, 'removePermission']);
    });
});
