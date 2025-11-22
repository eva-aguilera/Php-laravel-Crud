<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Session;
use Exception; // Para capturar errores de decodificación de JWT

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Obtener token desde la sesión (donde lo guardamos en el AuthController)
        $jwt = Session::get('jwt_token');

        // 2. Verificar si el token existe
        if (!$jwt) {
            // No hay token, redirigir al login (ruta 'loginBasic')
            return redirect()->route('loginBasic')->with('error', 'Por favor inicia sesión.');
        }

        // 3. Obtener la clave secreta desde las variables de entorno
        $secretKey = env('JWT_SECRET_KEY', 'clave_super_secreta_fallback'); 
        
        try {
            // 4. Decodificar y validar el token
            // Si el token es inválido o ha expirado, JWT::decode lanzará una excepción
            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
            
            // Opcional: Si es necesario, puedes guardar el ID del usuario en la Request
            // para que los controladores en las rutas protegidas puedan acceder a él
            $request->merge(['usuario_id' => $decoded->sub]);
            
        } catch (Exception $e) {
            // 5. Manejar error de token expirado/inválido
            Session::forget('jwt_token'); // Limpia el token inválido
            return redirect()->route('loginBasic')->with('error', 'Tu sesión ha expirado o el token es inválido. Inicia sesión de nuevo.');
        }

        // 6. Token válido: Permite continuar con la solicitud a la ruta
        return $next($request);
    }
}