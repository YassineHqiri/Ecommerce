<?php

use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ServicePackController;
use App\Http\Controllers\Api\Admin\CrmController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('public')->group(function () {
    Route::get('/service-packs', [PublicController::class, 'servicePacks']);
    Route::post('/orders', [PublicController::class, 'order']);
    Route::post('/contact', [PublicController::class, 'contact']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/dashboard', [DashboardController::class, 'index']);
        
        Route::get('/orders', [OrderController::class, 'index']);
        Route::patch('/orders/{order}', [OrderController::class, 'updateStatus']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
        
        Route::get('/service-packs', [ServicePackController::class, 'index']);
        Route::post('/service-packs', [ServicePackController::class, 'store']);
        Route::get('/service-packs/{servicePack}', [ServicePackController::class, 'show']);
        Route::put('/service-packs/{servicePack}', [ServicePackController::class, 'update']);
        Route::delete('/service-packs/{servicePack}', [ServicePackController::class, 'destroy']);

        // CRM
        Route::prefix('crm')->group(function () {
            Route::get('/stats',                              [CrmController::class, 'stats']);
            Route::get('/pipeline',                          [CrmController::class, 'pipeline']);
            Route::get('/leads',                             [CrmController::class, 'index']);
            Route::post('/leads',                            [CrmController::class, 'store']);
            Route::get('/leads/{lead}',                      [CrmController::class, 'show']);
            Route::put('/leads/{lead}',                      [CrmController::class, 'update']);
            Route::delete('/leads/{lead}',                   [CrmController::class, 'destroy']);
            Route::post('/leads/{lead}/notes',               [CrmController::class, 'addNote']);
            Route::delete('/leads/{lead}/notes/{note}',      [CrmController::class, 'deleteNote']);
        });
    });
});
