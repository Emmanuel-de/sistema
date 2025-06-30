@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Lista de Digitalizaciones</h4>
                    <a href="{{ route('digitalizar.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Digitalización
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filtros de búsqueda -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" action="{{ route('digitalizar.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="tipo_filter" class="form-label">Tipo:</label>
                                        <select class="form-select" id="tipo_filter" name="tipo">
                                            <option value="">Todos los tipos</option>
                                            <option value="carpeta_preliminar" {{ request('tipo') == 'carpeta_preliminar' ? 'selected' : '' }}>Carpeta Preliminar</option>
                                            <option value="carpeta_procesal" {{ request('tipo') == 'carpeta_procesal' ? 'selected' : '' }}>Carpeta Procesal</option>
                                            <option value="amparo" {{ request('tipo') == 'amparo' ? 'selected' : '' }}>Amparo</option>
                                            <option value="oficio" {{ request('tipo') == 'oficio' ? 'selected' : '' }}>Oficio</option>
                                            <option value="evidencia" {{ request('evidencia') == 'evidencia' ? 'selected' : '' }}>Evidencia</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nuc_filter" class="form-label">NUC:</label>
                                        <input type="text" class="form-control" id="nuc_filter" name="nuc" 
                                               value="{{ request('nuc') }}" placeholder="Buscar por NUC">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="presentado_por_filter" class="form-label">Presentado Por:</label>
                                        <input type="text" class="form-control" id="presentado_por_filter" name="presentado_por" 
                                               value="{{ request('presentado_por') }}" placeholder="Buscar por presentado por">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="fecha_filter" class="form-label">Fecha:</label>
                                        <input type="date" class="form-control" id="fecha_filter" name="fecha" 
                                               value="{{ request('fecha') }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                        <a href="{{ route('digitalizar.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i> Limpiar
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($digitalizaciones->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo</th>
                                        <th>NUC</th>
                                        <th>Presentado Por</th>
                                        <th>Fecha Presentación</th>
                                        <th>OCR</th>
                                        <th>Archivos</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($digitalizaciones as $digitalizacion)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">#{{ $digitalizacion->id }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst(str_replace('_', ' ', $digitalizacion->tipo)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $digitalizacion->nuc }}</strong>
                                            </td>
                                            <td>{{ $digitalizacion->presentado_por }}</td>
                                            <td>{{ \Carbon\Carbon::parse($digitalizacion->fecha_presentacion)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($digitalizacion->ocr)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Sí
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-times"></i> No
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $digitalizacion->total_archivos ?? 0 }} archivo(s)
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $digitalizacion->estado == 'completado' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($digitalizacion->estado ?? 'pendiente') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('digitalizar.show', $digitalizacion->id) }}" 
                                                       class="btn btn-sm btn-outline-info" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('digitalizar.edit', $digitalizacion->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('digitalizar.destroy', $digitalizacion->id) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                title="Eliminar"
                                                                onclick="return confirm('¿Está seguro de eliminar esta digitalización?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center">
                            {{ $digitalizaciones->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay digitalizaciones registradas</h5>
                            <p class="text-muted">Comience creando una nueva digitalización</p>
                            <a href="{{ route('digitalizar.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nueva Digitalización
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection