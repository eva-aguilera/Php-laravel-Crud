<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto; // Importamos el Modelo Proyecto

class ProyectoController extends Controller
{
    /**
     * 🧾 Listar todos los proyectos
     * GET /api/proyectos
     */
    public function index()
    {
        
        $proyectos = Proyecto::all();
        return view('proyectos.index', compact('proyectos'));
    }
     /**
     * 🆕 Crear un nuevo proyecto
     * POST /api/proyectos
     */
    public function create()
    {
        return view('proyectos.create');
    }
    public function store(Request $request)
    {
       $request->validate([
        'nombre' => 'required|string|max:100',
        'fecha_inicio' => 'required|date', // Usar snake_case si la DB lo usa
        'estado' => 'required|string|max:50',
        'responsable' => 'required|string|max:100',
        'monto' => 'required|numeric|min:0',
    ]);

        Proyecto::create($request->all());

        return redirect()->route('proyectos.index')->with('success', 'Proyecto creado exitosamente.');
    }

      /**
     * 🔍 Obtener un proyecto por su ID
     * GET /api/proyectos/{id}
     */
    public function show($id)
    {
      $proyecto = Proyecto::findOrFail($id);
      return view('proyectos.show', compact('proyecto'));
    }

    /**
     * ✏️ Actualizar un proyecto por su ID
     * PUT /api/proyectos/{id}
     */
    public function update(Request $request, $id)
    {
        $request->validate([
        'nombre' => 'required|string|max:100',
        'fecha_inicio' => 'required|date',
        'estado' => 'required|string|max:50',
        'responsable' => 'required|string|max:100',
        'monto' => 'required|numeric|min:0',
    ]);

    $proyecto = Proyecto::findOrFail($id);
    $proyecto->update($request->all());

    return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado exitosamente.');
    }
    public function edit($id)
    {
    $proyecto = Proyecto::findOrFail($id);
    return view('proyectos.edit', compact('proyecto'));
    }

    /**
    * 🗑️ Eliminar un proyecto por su ID
    * DELETE /api/proyectos/{id}
    */
    public function destroy($id)
    {
       $proyecto = Proyecto::findOrFail($id);
       $proyecto->delete();
       return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado exitosamente.');
    }
}

