<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// Ruta pública para Login
Route::post('/login', [AuthController::class, 'login']);

// Grupo de rutas protegidas (Solo se puede entrar con Token)
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Obtener el usuario logueado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);
});