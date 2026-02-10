<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Sin Token)
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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
     * --- Gestión de Clientes ---
     -----------------------------*/
    Route::get('/clients-index', [ClientController::class, 'index']);              // Listar todos los clientes
    Route::post('/clients-create', [ClientController::class, 'store']);             // Crear nuevo cliente
    Route::get('/clients/{id}', [ClientController::class, 'show']);          // Ver cliente específico
    Route::put('/clients/{id}', [ClientController::class, 'update']);        // Actualizar cliente (completo)
    Route::patch('/clients/{id}', [ClientController::class, 'update']);      // Actualizar cliente (parcial)
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

});