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

// Rutas de Vistas básicas solicitadas [cite: 64, 65]
Route::get('/register', [ProyectoController::class, 'showRegisterForm'])->name('register');
Route::get('/login', [ProyectoController::class, 'showLoginForm'])->name('loginBasic');

// Rutas de API (Funciones del controlador) 
Route::post('/register', [ProyectoController::class, 'register']);
Route::post('/login', [ProyectoController::class, 'loginBasic']);

// Ruta protegida que usa el Middleware JWT [cite: 66]
Route::get('/', function () {
    return view('proyectos.index');
})->middleware('jwt.auth')->name('proyectos.listado');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

