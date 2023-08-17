<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MerchantController;
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

    Route::group(['prefix' => 'users'], function(){
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/search/{column}/{value}', [UserController::class, 'search']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::delete('multiple-deletion', [UserController::class, 'multipleDeletion']);
    });

    Route::group(['prefix' => 'merchants'], function(){
        Route::get('/', [MerchantController::class, 'index']);
        Route::post('/', [MerchantController::class, 'store']);
        Route::get('/search/{column}/{value}', [MerchantController::class, 'search']);
        Route::put('/{id}', [MerchantController::class, 'update']);
        Route::get('/{id}', [MerchantController::class, 'show']);
        Route::delete('multiple-deletion', [MerchantController::class, 'multipleDeletion']);
        Route::delete('/{id}', [MerchantController::class, 'destroy']);
    });
});
