<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\EmbazadoRecord;
use App\Models\Purchase;
use App\Models\Supplier;
use Carbon\Carbon;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Middleware\RoleMiddleware;

Route::post('/login', [AuthController::class, 'login']);

// Ruta de prueba
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    
    Route::get('/customers', [CustomerController::class, 'index'])->middleware(RoleMiddleware::class.':admin');
    

    Route::middleware(RoleMiddleware::class.':encargado')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        Route::get('/sales', [SaleController::class, 'index']);
        Route::post('/sales', [SaleController::class, 'store']);

        Route::get('/purchases', [PurchaseController::class, 'index']);
        Route::post('/purchases', [PurchaseController::class, 'store']);

        Route::get('/suppliers', [SupplierController::class, 'index']);
    });

    Route::middleware(RoleMiddleware::class.':operario')->group(function () {
        Route::get('/embazado', [EmbazadoRecordController::class, 'index']);
        Route::post('/embazado', [EmbazadoRecordController::class, 'store']);
    });
});
