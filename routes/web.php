<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Rutas que devuelven vistas y manejan la sesión/autenticación
*/

// --- 1. RUTAS DE AUTENTICACIÓN Y VISTAS DE FORMULARIO ---

// Muestra el formulario de Registro (GET /register)
Route::get('/register', [ProyectoController::class, 'showRegisterForm'])->name('register');

// Muestra el formulario de Login (GET /login)
Route::get('/login', [ProyectoController::class, 'showLoginForm'])->name('loginBasic');

// Envía los datos de Registro (POST /register - La lógica de creación)
// NOTA: Esta ruta ya la tenías en tu api.php, pero si maneja la redirección, va aquí.
Route::post('/register', [ProyectoController::class, 'register'])->name('register.submit');

// Envía los datos de Login (POST /login - La lógica de JWT)
// NOTA: Esta ruta ya la tenías en tu api.php, pero si maneja la redirección, va aquí.
Route::post('/login', [ProyectoController::class, 'loginBasic'])->name('login.submit');

// Cierre de Sesión (Elimina el JWT de la sesión)
Route::post('/logout', [ProyectoController::class, 'logout'])->name('logout');


// --- 2. RUTA PROTEGIDA (Página de Proyectos) ---

// Ruta raíz después del login. Requiere el JWT en la sesión para acceder.
Route::middleware('jwt.auth')->group(function () {

    // Listado principal (Página que renderiza el listado de proyectos)
    // Se mantiene el nombre de ruta 'proyectos.listado' para no romper la redirección de login.
    Route::get('/', [ProyectoController::class, 'index'])->name('proyectos.listado');
    
    // Rutas de VISTAS (Web)
Route::get('/proyectos/create', [ProyectoController::class, 'create'])->name('proyectos.create');
Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show');
Route::get('/proyectos/{id}/edit', [ProyectoController::class, 'edit'])->name('proyectos.edit');
// [POST] GUARDA el nuevo proyecto (ESTA ES LA RUTA QUE FALTA)
Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');

// Rutas de ACCIÓN (Web) que tu vista requiere
Route::put('/proyectos/{id}', [ProyectoController::class, 'update'])->name('proyectos.update'); // Para el formulario de edición
Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy'])->name('proyectos.destroy'); // Para el formulario de eliminación
 
    // Si tienes una vista para mostrar un proyecto individual
    Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show');
});