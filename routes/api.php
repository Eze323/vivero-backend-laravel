<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmbazadoRecordController;
use App\Http\Controllers\EncargadoController;
use App\Http\Middleware\RoleMiddleware;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/test', function () {
    return response()->json([
        'message' => 'API funcionando correctamente',
        'status' => 'OK',
        'db_connection' => DB::connection()->getPdo() ? 'Conectado' : 'No conectado',
    ]);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/customers', [CustomerController::class, 'getCustomers'])->middleware(RoleMiddleware::class.':admin');

    Route::middleware(RoleMiddleware::class.':encargado')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        Route::get('/sales', [SaleController::class, 'index']);
        Route::post('/sales', [SaleController::class, 'store']);
        Route::put('/sales/{sale}', [SaleController::class, 'update']);
        Route::delete('/sales/{sale}', [SaleController::class, 'destroy']);
        Route::get('/sales/{sale}', [SaleController::class, 'show']);

        Route::get('/purchases', [PurchaseController::class, 'index']);
        Route::post('/purchases', [PurchaseController::class, 'store']);
        Route::apiResource('invoices', InvoiceController::class);

        Route::get('/suppliers', [SupplierController::class, 'index']);

        Route::put('/products/{product}/stock', [EncargadoController::class, 'updateStock']);

        
    });

    Route::middleware(RoleMiddleware::class.':operario')->group(function () {
        Route::get('/embazado', [EmbazadoRecordController::class, 'index']);
        Route::post('/embazado', [EmbazadoRecordController::class, 'store']);
    });
});