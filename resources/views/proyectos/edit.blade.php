<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">✍️ Editar Proyecto: <span class="text-warning">{{ $proyecto->nombre }}</span></h1>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <h5 class="alert-heading">¡Error de Validación!</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('proyectos.update', $proyecto->id) }}" class="row g-3">
            @csrf 
            @method('PUT') <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required 
                       class="form-control @error('nombre') is-invalid @enderror" 
                       value="{{ old('nombre', $proyecto->nombre) }}">
                @error('nombre') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            <div class="col-md-6">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" required 
                       class="form-control @error('fecha_inicio') is-invalid @enderror" 
                       value="{{ old('fecha_inicio', $proyecto->fecha_inicio->format('Y-m-d')) }}">
                @error('fecha_inicio') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            <div class="col-md-4">
                <label for="estado" class="form-label">Estado:</label>
                <select name="estado" id="estado" required 
                        class="form-select @error('estado') is-invalid @enderror">
                    @php $selectedEstado = old('estado', $proyecto->estado); @endphp
                    <option value="Activo" {{ $selectedEstado == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Pausado" {{ $selectedEstado == 'Pausado' ? 'selected' : '' }}>Pausado</option>
                    <option value="Completado" {{ $selectedEstado == 'Completado' ? 'selected' : '' }}>Completado</option>
                    <option value="Pendiente" {{ $selectedEstado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                </select>
                @error('estado') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            <div class="col-md-4">
                <label for="responsable" class="form-label">Responsable:</label>
                <input type="text" name="responsable" id="responsable" required 
                       class="form-control @error('responsable') is-invalid @enderror" 
                       value="{{ old('responsable', $proyecto->responsable) }}">
                @error('responsable') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            <div class="col-md-4">
                <label for="monto" class="form-label">Monto (CLP):</label>
                <input type="number" step="0.01" name="monto" id="monto" required 
                       class="form-control @error('monto') is-invalid @enderror" 
                       value="{{ old('monto', $proyecto->monto) }}">
                @error('monto') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-warning">Actualizar Proyecto</button>
            </div>
        </form>
        
        <hr class="mt-4">
        <a href="{{ route('proyectos.listado') }}" class="btn btn-secondary">← Volver al Listado</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>