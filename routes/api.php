<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MerchantController;

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

    Route::group(['prefix' => 'users'], function(){
        Route::get('/', [UserController::class, 'index']);
        Route::get('/all', [UserController::class, 'all']);
        Route::get('/search/{column}/{value}', [UserController::class, 'search']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::delete('multiple-deletion', [UserController::class, 'multipleDeletion']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::group(['prefix' => 'merchants'], function(){
        Route::get('/', [MerchantController::class, 'index']);
        Route::get('/all', [MerchantController::class, 'all']);
        Route::get('/search/{column}/{value}', [MerchantController::class, 'search']);
        Route::post('/', [MerchantController::class, 'store']);
        Route::put('/{id}', [MerchantController::class, 'update']);
        Route::get('/{id}', [MerchantController::class, 'show']);
        Route::delete('multiple-deletion', [MerchantController::class, 'multipleDeletion']);
        Route::delete('/{id}', [MerchantController::class, 'destroy']);
    });
});
