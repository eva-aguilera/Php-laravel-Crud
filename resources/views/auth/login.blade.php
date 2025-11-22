<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; } .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; } input[type="email"], input[type="password"] { width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; } button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; } button:hover { background-color: #0056b3; }</style>
</head>
<body>
    <div class="card">
        <h2>🔑 Inicio de Sesión</h2>

        @if (session('error'))
            <p style="color: red; border: 1px solid red; padding: 5px; background: #ffebeb; border-radius: 4px;">{{ session('error') }}</p>
        @endif
        
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" required value="{{ old('correo') }}">
            
            <label for="clave">Contraseña</label>
            <input type="password" id="clave" name="clave" required>
            
            <button type="submit">Entrar</button>
        </form>
        <p style="text-align: center; margin-top: 15px;"><a href="{{ route('register.show') }}">¿Necesitas una cuenta? Regístrate</a></p>
    </div>
</body>
</html>