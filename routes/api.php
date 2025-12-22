<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController; // <--- Importamos el controlador aquí

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Sin Token)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Requieren Token Bearer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    
    // --- Gestión de Sesión ---
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);




    // --- Gestión de Clientes (CRUD Completo) ---
    //xpande esa única línea en múltiples endpoints
    // Esta sola línea crea 5 rutas: GET(index), POST(store), GET(show), PUT(update), DELETE(destroy)
    Route::apiResource('/clients', ClientController::class);
});