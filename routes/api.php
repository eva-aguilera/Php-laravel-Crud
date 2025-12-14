<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;

// Rutas protegidas por JWT
Route::middleware('jwt.auth')->group(function () {
    
    // CRUD de Proyectos (API)
    Route::get('/proyectos', [ProyectoController::class, 'apiIndex']);
    Route::post('/proyectos', [ProyectoController::class, 'apiStore']);
    Route::get('/proyectos/{id}', [ProyectoController::class, 'apiShow']);
    
    // PUT o PATCH para actualizar
    Route::match(['put', 'patch'], '/proyectos/{id}', [ProyectoController::class, 'apiUpdate']);
    
    Route::delete('/proyectos/{id}', [ProyectoController::class, 'apiDestroy']);
});

// Rutas de Autenticación
Route::post('/register', [ProyectoController::class, 'register']);
Route::post('/login', [ProyectoController::class, 'loginBasic']);