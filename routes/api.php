<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SaleController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Sin Token)
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// RUTA DE DIAGNÓSTICO
Route::get('/debug-token', function (Request $request) {
    $token = $request->bearerToken();
    if (!$token) {
        return response()->json(['error' => 'No enviaste ningún Token Bearer en el Header']);
    }
    
    $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
    
    if (!$accessToken) {
        return response()->json([
            'error' => 'El token existe en el Header, pero NO existe en la base de datos o es inválido',
            'token_enviado' => $token
        ]);
    }

    return response()->json([
        'success' => 'Token válido y encontrado',
        'usuario' => $accessToken->tokenable,
        'token_id' => $accessToken->id
    ]);
});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Requieren Token Bearer)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {
    
    /**---------------------------
     * --- Gestión de Sesión ---
     -----------------------------*/
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    /**---------------------------
     * --- Recursos API ---
     -----------------------------*/
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('products', ProductsController::class);
    Route::apiResource('sales', SaleController::class)->only(['index', 'store', 'show', 'destroy']);

    /**---------------------------
     * --- Inteligencia de Negocio ---
     -----------------------------*/
    Route::prefix('reports')->group(function () {
        Route::get('/summary', [ReportsController::class, 'salesSummary']);
        Route::get('/by-category', [ReportsController::class, 'salesByCategory']);
        Route::get('/top-clients', [ReportsController::class, 'topClients']);
    });

    /**---------------------------
     * --- Gestión de Inventario ---
     -----------------------------*/
    Route::prefix('inventory')->group(function () {
        Route::get('/low-stock', [InventoryController::class, 'lowStockAlert']);
        Route::get('/valuation', [InventoryController::class, 'inventoryValuation']);
        Route::post('/bulk-price-update', [InventoryController::class, 'bulkPriceUpdate']);
    });

    //NOTA: Verificar las rutas del CRUD que crea, modifica, elimina, agrega, lista los clientes

});
