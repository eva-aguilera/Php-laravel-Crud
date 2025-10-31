<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController; // ¡ESENCIAL!

// 1. Listar todos los proyectos
Route::get('/', [ProyectoController::class, 'index'])->name('proyectos.index'); 

// 2. Mostrar la vista para crear un nuevo proyecto
Route::get('/proyectos/crear', [ProyectoController::class, 'create'])->name('proyectos.create'); 

// 3. Guardar el nuevo proyecto (POST)
Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store'); 

// 4. Mostrar un proyecto específico por ID (GET)
Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show'); 

// 5. Mostrar el formulario de edición (GET)
Route::get('/proyectos/{id}/edit', [ProyectoController::class, 'edit'])->name('proyectos.edit'); 

// 6. Actualizar un proyecto específico por ID (PUT/PATCH)
Route::put('/proyectos/{id}', [ProyectoController::class, 'update'])->name('proyectos.update'); 

// 7. Eliminar un proyecto por ID (DELETE)
Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy'])->name('proyectos.destroy');