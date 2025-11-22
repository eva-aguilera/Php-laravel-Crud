<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Detalle del Proyecto: <span class="text-primary">{{ $proyecto->nombre }}</span></h1>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>ID:</strong> <span class="badge bg-secondary">{{ $proyecto->id }}</span></li>
                    <li class="mb-2">
                        <strong>Fecha de Inicio:</strong> 
                        <span class="badge bg-info text-dark">{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d-m-Y') }}</span>
                    </li>
                    <li class="mb-2">
                        <strong>Estado:</strong> 
                        <span class="badge bg-{{ $proyecto->estado == 'Activo' ? 'success' : ($proyecto->estado == 'Pausado' ? 'warning' : ($proyecto->estado == 'Completado' ? 'dark' : 'danger')) }}">
                            {{ $proyecto->estado }}
                        </span>
                    </li>
                    <li class="mb-2"><strong>Responsable:</strong> {{ $proyecto->responsable }}</li>
                    <li class="mb-2">
                        <strong>Monto:</strong> 
                        <span class="text-success fw-bold">$ {{ number_format($proyecto->monto, 2, ',', '.') }} CLP</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="mt-4">
        
        <div class="d-flex gap-2">
            <a href="{{ route('proyectos.edit', $proyecto->id) }}" class="btn btn-warning">✏️ Editar Proyecto</a>
            <a href="{{ route('proyectos.listado') }}" class="btn btn-secondary">← Volver al Listado</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>