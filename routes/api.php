<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/proyectos', [ProyectoController::class, 'index']);        // Listar todos
Route::get('/proyectos/{id}', [ProyectoController::class, 'show']);    // Ver uno
Route::post('/proyectos', [ProyectoController::class, 'store']);       // Crear
Route::put('/proyectos/{id}', [ProyectoController::class, 'update']);  // Actualizar
Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy']); // Eliminar


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

