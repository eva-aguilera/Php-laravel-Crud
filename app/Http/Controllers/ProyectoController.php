<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Proyecto; // Importamos el Modelo Proyecto

use Illuminate\Support\Facades\Hash; // Para cifrar la clave (Requerimiento 3: Cifrado)
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB; // Opcional: para usar transacciones
use Firebase\JWT\JWT; // Para generar el token
use Carbon\Carbon; // Para manejar la expiración del token

class ProyectoController extends Controller


{
    // Función para mostrar la vista de Login (Requerimiento 6)
    public function showLoginForm() {
        return view('auth.login');
    }

    // Función para mostrar la vista de Registro (Requerimiento 6)
    public function showRegisterForm() {
        return view('auth.register');
    }

    /**
     * Función de Registro de Usuario (Requerimiento 3)
     */
    public function register(Request $request)
    {
        // 1. Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|unique:usuarios', // 'unique:usuarios' verifica que no exista
            'clave' => 'required|string|min:6|confirmed', // 'confirmed' requiere un campo 'clave_confirmation'
        ], [
            // Mensajes de error personalizados
            'correo.unique' => 'El correo electrónico ya está registrado.',
            'clave.confirmed' => 'La confirmación de la clave no coincide.',
        ]);

        try {
            // 2. Crear el usuario
            $user = Usuario::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                // El campo 'clave' se cifra automáticamente gracias al 'cast' definido en el modelo Usuario
                'clave' => $request->clave, 
            ]);

            // Opcional: Iniciar sesión inmediatamente después del registro
            return $this->generateAndStoreJWT($user);
            
        } catch (\Exception $e) {
            // Manejo de error de base de datos
            return redirect()->back()->withInput()->with('error', 'Error al intentar registrar el usuario.');
        }
    }

    /**
     * Función de Inicio de Sesión y generación de JWT (Requerimiento 3)
     */
    public function loginBasic(Request $request)
    {
        // 1. Obtener los datos y validarlos
        $request->validate([
            'correo' => 'required|email',
            'clave' => 'required',
        ]);

        // 2. Traer usuario
        $user = Usuario::where('correo', $request->correo)->first();

        // 3. Verificar credenciales: usuario existe Y la contraseña es correcta
        if ($user && Hash::check($request->clave, $user->clave)) {
            
            // 4. Generar y almacenar el JWT (cumple Requerimiento: devuelve un JWT)
            return $this->generateAndStoreJWT($user);
        }

        // 5. Si las credenciales no son válidas
        return redirect()->back()->withInput()->with('error', 'Credenciales inválidas.');
    }

    /**
     * Lógica para generar y almacenar el JWT (función auxiliar)
     */
    private function generateAndStoreJWT(Usuario $user)
    {
        $now = Carbon::now();
        // Definición del Payload (datos del token)
        $payload = [
            'sub' => $user->id,
            'name' => $user->nombre,
            'iat' => $now->timestamp,
            'exp' => $now->addHour()->timestamp // expira en 1 hora (60 minutos * 60 segundos)
        ];

        // Obtener la clave secreta desde .env (Requerimiento 3: Configuración)
        $secretKey = env('JWT_SECRET_KEY', 'clave_super_secreta_fallback'); 
        
        // Codificar el token
        $token = JWT::encode($payload, $secretKey, 'HS256');

        // Guardar el token en sesión (como se sugiere en tu ejemplo clase2.txt)
        Session::put('jwt_token', $token);

        // Redirigir al listado de proyectos (ruta protegida)
        return redirect()->route('proyectos.listado')->with('success', '¡Inicio de sesión exitoso!');
    }
    
   

    public function logout()
    {
        // 1. Eliminar el token JWT de la sesión
        Session::forget('jwt_token');

        // 2. Redirigir al usuario a la página de login (la ruta sin protección)
        return redirect()->route('loginBasic')->with('status', 'Has cerrado sesión exitosamente.');
    }

    /**
     * 🧾 Listar todos los proyectos
     * GET /api/proyectos
     */
   // public function index()
    //{
        
       // $proyectos = Proyecto::all();
        //return view('proyectos.index', compact('proyectos'));
   // }
 
public function index()
{
    // 1. Obtener la lista de proyectos
    $proyectos = Proyecto::all(); // O solo los proyectos del usuario logueado
    
    // 2. Retornar la vista, pasando la variable $proyectos
    // Usa 'compact('proyectos')' o el array asociativo. Ambos son válidos.
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

        return redirect()->route('proyectos.listado')->with('success', 'Proyecto creado exitosamente.');
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

    return redirect()->route('proyectos.listado')->with('success', 'Proyecto actualizado exitosamente.');
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
       return redirect()->route('proyectos.listado')->with('success', 'Proyecto eliminado exitosamente.');
    }
}


