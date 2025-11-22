<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; } .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; } input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; } button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; } button:hover { background-color: #1e7e34; }</style>
</head>
<body>
    <div class="card">
        <h2>➕ Registro</h2>

        @if ($errors->any())
            <div style="color: red; margin-bottom: 15px; border: 1px solid red; padding: 5px; background: #ffebeb; border-radius: 4px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required value="{{ old('nombre') }}">
            
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" required value="{{ old('correo') }}">
            
            <label for="clave">Contraseña</label>
            <input type="password" id="clave" name="clave" required>
            
            <label for="clave_confirmation">Confirmar Contraseña</label>
            <input type="password" id="clave_confirmation" name="clave_confirmation" required>
            
            <button type="submit">Registrarse</button>
        </form>
        <p style="text-align: center; margin-top: 15px;"><a href="{{ route('loginBasic') }}">¿Ya tienes cuenta? Inicia Sesión</a></p>
    </div>
</body>
</html>
