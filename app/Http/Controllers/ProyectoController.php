<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Proyecto; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Session;
use Firebase\JWT\JWT; 
use Carbon\Carbon; 
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProyectoController extends Controller
{
    // ====================================================================
    // 1. LÓGICA DE AUTENTICACIÓN (Compartida: Web (Session/Redirect) y API (Token))
    // ====================================================================

    public function showLoginForm() {
        return view('auth.login');
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    /**
     * Función de Registro de Usuario 
     * Usado tanto por Web como por API (Si se accede desde /api/register)
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|unique:usuarios', 
            'clave' => 'required|string|min:6|confirmed', 
        ], [
            'correo.unique' => 'El correo electrónico ya está registrado.',
            'clave.confirmed' => 'La confirmación de la clave no coincide.',
        ]);

        try {
            $user = Usuario::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'clave' => $request->clave, 
            ]);
            
            return $this->generateAndStoreJWT($user);
            
        } catch (\Exception $e) {
            // Manejo de error para Web (redirección)
            return redirect()->back()->withInput()->with('error', 'Error al intentar registrar el usuario.');
        }
    }

    /**
     * Función de Inicio de Sesión y generación de JWT
     */
    public function loginBasic(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'clave' => 'required',
        ]);

        $user = Usuario::where('correo', $request->correo)->first();

        if ($user && Hash::check($request->clave, $user->clave)) {
            return $this->generateAndStoreJWT($user);
        }

        return redirect()->back()->withInput()->with('error', 'Credenciales inválidas.');
    }

    /**
     * Lógica auxiliar para generar y almacenar el JWT (Respuesta dual)
     */
    private function generateAndStoreJWT(Usuario $user)
    {
        $now = Carbon::now();
        $payload = [
            'sub' => $user->id,
            'name' => $user->nombre,
            'iat' => $now->timestamp,
            'exp' => $now->addHour()->timestamp 
        ];

        $secretKey = env('JWT_SECRET_KEY', 'clave_super_secreta_fallback'); 
        $token = JWT::encode($payload, $secretKey, 'HS256');

        // Si la petición viene de API (ej: Postman), devuelve el JSON del token
        if (request()->wantsJson() || request()->is('api/*')) {
             return response()->json(['token' => $token, 'user' => $user->nombre], 200);
        }
        
        // Si la petición viene de Web, guarda el token en sesión y redirige
        Session::put('jwt_token', $token);
        return redirect()->route('proyectos.listado')->with('success', '¡Inicio de sesión exitoso!');
    }
    
    public function logout()
    {
        Session::forget('jwt_token');
        // Usar el nombre de ruta 'loginBasic' (definido en web.php)
        return redirect()->route('loginBasic')->with('status', 'Has cerrado sesión exitosamente.');
    }

    // ====================================================================
    // 2. LÓGICA DE VISTAS WEB (Redirecciones)
    // ====================================================================
    
    // [GET] Muestra la lista de proyectos (Web)
    public function index()
    {
        $proyectos = Proyecto::all();
        return view('proyectos.index', compact('proyectos'));
    }

    // [GET] Muestra el formulario de creación (Web)
    public function create()
    {
        return view('proyectos.create');
    }

    // [POST] Guarda un nuevo proyecto (Web: Redirección)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:50',
            'responsable' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0',
        ]);

        try {
            Proyecto::create($validatedData);
            return redirect()->route('proyectos.listado')->with('success', 'Proyecto creado exitosamente.'); 
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'No se pudo crear el proyecto.');
        }
    }

    // [GET] Muestra el detalle de un proyecto (Web)
    public function show($id)
    {
        try {
            $proyecto = Proyecto::findOrFail($id);
            return view('proyectos.show', compact('proyecto'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('proyectos.listado')->with('error', 'Proyecto no encontrado.');
        }
    }

    // [GET] Muestra el formulario de edición (Web)
    public function edit($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        return view('proyectos.edit', compact('proyecto'));
    }

    // [PUT] Actualiza un proyecto (Web: Redirección)
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:50',
            'responsable' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0',
        ]);

        try {
            $proyecto = Proyecto::findOrFail($id);
            $proyecto->update($validatedData);
            return redirect()->route('proyectos.listado')->with('success', 'Proyecto actualizado exitosamente.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('proyectos.listado')->with('error', 'Proyecto no encontrado para actualizar.');
        }
    }

    // [DELETE] Elimina un proyecto (Web: Redirección)
    public function destroy($id)
    {
        try {
            $proyecto = Proyecto::findOrFail($id);
            $proyecto->delete();
            return redirect()->route('proyectos.listado')->with('success', 'Proyecto eliminado exitosamente.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('proyectos.listado')->with('error', 'Proyecto no encontrado para eliminar.');
        }
    }

    // ====================================================================
    // 3. LÓGICA DE API REST (JSON y Códigos HTTP requeridos)
    // ====================================================================
    
    /**
     * [GET] API: Búsqueda de todos los proyectos
     * Requerimiento: Respuesta 200 (arreglo vacío si no hay datos)
     */
    public function apiIndex()
    {
        $proyectos = Proyecto::all();
        return response()->json($proyectos, 200); 
    }

    /**
     * [POST] API: Agregar un proyecto
     * Requerimiento: Validación de campos requeridos y Respuesta 201
     */
    public function apiStore(Request $request)
    {
        // Si la validación falla, Laravel devuelve automáticamente 422 Unprocessable Entity
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:50',
            'responsable' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0',
        ]);

        try {
            $proyecto = Proyecto::create($validatedData);
            // Cumple: Código de respuesta 201 Created
            return response()->json($proyecto, 201); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo crear el proyecto.'], 500);
        }
    }

    /**
     * [GET] API: Búsqueda de un proyecto por su ID
     * Requerimiento: Respuesta 200 o 404
     */
    public function apiShow($id)
    {
        try {
            $proyecto = Proyecto::findOrFail($id);
            // Cumple: Código de respuesta 200 y devuelve todos los campos
            return response()->json($proyecto, 200); 
        } catch (ModelNotFoundException $e) {
            // Cumple: Si el ID no existe, retorna 404
            return response()->json(['error' => 'Proyecto no encontrado.'], 404);
        }
    }

    /**
     * [PUT/PATCH] API: Actualizar un proyecto por su ID
     * Requerimiento: Respuesta 200 o 404
     */
    public function apiUpdate(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:50',
            'responsable' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0',
        ]);
        
        try {
            $proyecto = Proyecto::findOrFail($id);
            $proyecto->update($validatedData);

            // Cumple: Código de respuesta 200 y devuelve los campos actualizados
            return response()->json($proyecto, 200); 
        } catch (ModelNotFoundException $e) {
            // Cumple: Si el ID no existe, retorna 404
            return response()->json(['error' => 'Proyecto no encontrado para actualizar.'], 404);
        }
    }

    /**
     * [DELETE] API: Eliminar un proyecto por su ID
     * Requerimiento: Respuesta 204 o 404
     */
    public function apiDestroy($id)
    {
        try {
            $proyecto = Proyecto::findOrFail($id);
            $proyecto->delete();
            
            // Cumple: Código de respuesta 204 No Content (respuesta vacía)
            return response()->noContent(); 
        } catch (ModelNotFoundException $e) {
            // Cumple: Si el ID no existe, retorna 404
            return response()->json(['error' => 'Proyecto no encontrado para eliminar.'], 404);
        }
    }
}