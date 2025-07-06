@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Notificaciones via Celular</h4>
                </div>
                <div class="card-body">
                    <!-- Sección de búsqueda -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="GET" action="{{ route('notificaciones.index') }}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="nuc" class="form-label">NUC</label>
                                                <input type="text" class="form-control" id="nuc" name="nuc" value="{{ request('nuc') }}" placeholder="Ingrese número NUC">
                                            </div>
                                            <div class="col-md-4">
                                                <label>&nbsp;</label>
                                                <div class="d-flex gap-2 align-items-end">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-search"></i> BUSCAR
                                                    </button>
                                                    <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary">
                                                        <i class="fas fa-refresh"></i> LIMPIAR
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Crear Nueva Notificación -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <a href="{{ route('notificaciones.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> CREAR NUEVA NOTIFICACIÓN
                            </a>
                        </div>
                    </div>

                    <!-- Tabla de resultados -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>NUC</th>
                                    <th>TELÉFONO</th>
                                    <th>FECHA NOTIFICACIÓN</th>
                                    <th>USUARIO NOTIFICA</th>
                                    <th>TIPO NOTIFICACIÓN</th>
                                    <th>OBSERVACIONES</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notificaciones as $notificacion)
                                    <tr>
                                        <td>{{ $notificacion->nuc ?? 'N/A' }}</td>
                                        <td>{{ $notificacion->numero_telefono }}</td>
                                        <td>{{ $notificacion->fecha_notificacion ? $notificacion->fecha_notificacion->format('d/m/Y H:i') : '' }}</td>
                                        <td>{{ $notificacion->usuarioNotifica->name ?? $notificacion->persona_notifico }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $notificacion->seleccione_notificacion)) }}</td>
                                        <td>{{ Str::limit($notificacion->observaciones, 50) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('notificaciones.show', $notificacion) }}" class="btn btn-info btn-sm" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('notificaciones.edit', $notificacion) }}" class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('notificaciones.destroy', $notificacion) }}" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta notificación?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No se encontraron notificaciones</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($notificaciones->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $notificaciones->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn {
        border-radius: 5px;
    }

    .btn-group .btn {
        margin-right: 2px;
    }
</style>
@endpush
@endsection