<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
    <x-uf-display />    

        <h1 class="mb-4">📋 Listado de Proyectos</h1>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <p class="mb-3">
            <a href="{{ route('proyectos.create') }}" class="btn btn-primary">➕ Crear Nuevo Proyecto</a>
        </p>

        @if ($proyectos->isEmpty())
            <div class="alert alert-info">No hay proyectos registrados.</div>
        @else
            <table class="table table-striped table-hover"> 
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Responsable</th>
                        <th>Monto (CLP)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proyectos as $proyecto)
                        <tr>
                            <td>{{ $proyecto->id }}</td>
                            <td>{{ $proyecto->nombre }}</td>
                            <td><span class="badge bg-{{ $proyecto->estado == 'Activo' ? 'success' : ($proyecto->estado == 'Pausado' ? 'warning' : ($proyecto->estado == 'Completado' ? 'info' : 'secondary')) }}">{{ $proyecto->estado }}</span></td>
                            <td>{{ $proyecto->responsable }}</td>
                            <td>$ {{ number_format($proyecto->monto, 2, ',', '.') }}</td>
                            
                            <td class="text-center" style="width: 250px;"> 
                                
                                <div class="d-flex justify-content-center align-items-center">
                                    
                                    <a href="{{ route('proyectos.show', $proyecto->id) }}" class="btn btn-sm btn-outline-secondary me-1">Ver</a>
                                    
                                    <a href="{{ route('proyectos.edit', $proyecto->id) }}" class="btn btn-sm btn-warning me-1">Editar</a> 
                                    
                                    <form action="{{ route('proyectos.destroy', $proyecto->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Está seguro de eliminar este proyecto?');" 
                                                class="btn btn-sm btn-danger">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="dropdown-item">
        Cerrar Sesión
    </button>
</form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>