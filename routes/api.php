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
        'users' => UserController::class,
        'merchants' => MerchantController::class,
    ]);

    Route::group(['prefix' => 'products'], function(){
        Route::get('/search/{column}/{value}', [ProductsContoller::class, 'search']);
        Route::delete('multiple-deletion', [ProductsContoller::class, 'multipleDeletion']);
    });

    Route::group(['prefix' => 'users'], function(){
        Route::get('/search/{column}/{value}', [UserController::class, 'search']);
        Route::delete('multiple-deletion', [UserController::class, 'multipleDeletion']);
    });

    Route::group(['prefix' => 'merchants'], function(){
        Route::get('/search/{column}/{value}', [MerchantController::class, 'search']);
        Route::delete('multiple-deletion', [MerchantController::class, 'multipleDeletion']);
    });
});