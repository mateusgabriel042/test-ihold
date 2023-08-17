<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MerchantController;
use App\Http\Controllers\API\ProductsContoller;
use App\Http\Controllers\API\OrderContoller;

Route::group(['prefix' => 'auth'], function(){
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'auth'], function(){
        Route::delete('/logout', [AuthController::class, 'logout']);
    });

    Route::apiResources([
        'products' => ProductsContoller::class,
        'orders' => OrderContoller::class,
        'merchants' => MerchantController::class,
        'user' => UserController::class
    ]);

    Route::group(['prefix' => 'merchants'], function(){
        Route::delete('multiple-deletion', [MerchantController::class, 'multipleDeletion']);
        Route::get('/all', [MerchantController::class, 'all']);
        Route::get('/search/{column}/{value}', [MerchantController::class, 'search']);
        
    });
});