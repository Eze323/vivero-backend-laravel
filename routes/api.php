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
use App\Http\Controllers\EmbazadoRecordController;
//encargado
use App\Http\Controllers\EncargadoController;


Route::post('/login', [AuthController::class, 'login']);

// Ruta de prueba
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    
    Route::get('/customers', [CustomerController::class, 'getCustomers'])->middleware(RoleMiddleware::class.':admin');
    

    Route::middleware(RoleMiddleware::class.':encargado')->group(function () {
       // Route::get('/products', [ProductController::class, 'index']);
        //Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        //Route::get('/sales', [EncargadoController::class, 'getSales']);
        //Route::post('/sales', [EncargadoController::class, 'storeSale']);
                // Rutas de Ventas para Encargado
                Route::get('/sales', [SaleController::class, 'index']); // Listar ventas
                Route::post('/sales', [SaleController::class, 'store']); // Crear venta
                Route::put('/sales/{sale}', [SaleController::class, 'update']); // <-- Agregar: Ruta para editar venta por ID (usar binding de modelo)
                Route::delete('/sales/{sale}', [SaleController::class, 'destroy']); // <-- Agregar: Ruta para eliminar venta por ID (usar binding de modelo)
                Route::get('/sales/{sale}', [SaleController::class, 'show']); 

        Route::get('/purchases', [PurchaseController::class, 'index']);
        Route::post('/purchases', [PurchaseController::class, 'store']);

        Route::get('/suppliers', [SupplierController::class, 'index']);

        Route::post('/products', [EncargadoController::class, 'storeProduct']);
        Route::get('/products', [EncargadoController::class, 'getProducts']);
    });

    Route::middleware(RoleMiddleware::class.':operario')->group(function () {
        Route::get('/embazado', [EmbazadoRecordController::class, 'index']);
        Route::post('/embazado', [EmbazadoRecordController::class, 'store']);
    });
});
