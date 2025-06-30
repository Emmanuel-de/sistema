@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detalle de Recepción</h4>
                    <div class="btn-group">
                        <a href="{{ route('recepcion.edit', $recepcion->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('recepcion.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información Principal -->
                        <div class="col-md-8">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">NUC</h6>
                                    <h5 class="text-primary">{{ $recepcion->nuc }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Fecha de Registro</h6>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($recepcion->created_at)->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h6 class="text-muted mb-1">Tipo de Audiencia</h6>
                                    <p class="mb-0">{{ $recepcion->tipo_audiencia }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h6 class="text-muted mb-1">Quién Presenta</h6>
                                    <p class="mb-0">{{ $recepcion->quien_presenta }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-1">Número de Oficio</h6>
                                    <p class="mb-0">{{ $recepcion->numero_oficio }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-1">Fecha de Oficio</h6>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($recepcion->fecha_oficio)->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-1">Número de Fojas</h6>
                                    <p class="mb-0">
                                        <span class="badge bg-info fs-6">{{ $recepcion->numero_fojas }}</span>
                                    </p>
                                </div>
                            </div>

                            @if($recepcion->tiene_anexos)
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h6 class="text-success mb-2">
                                        <i class="fas fa-paperclip"></i> Anexos
                                    </h6>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-1">Número de Anexos</h6>
                                    <p class="mb-0">
                                        <span class="badge bg-success">{{ $recepcion->numero_anexos }}</span>
                                    </p>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted mb-1">Descripción de Anexos</h6>
                                    <div class="card bg-light">
                                        <div class="card-body py-2">
                                            <p class="mb-0 small">{{ $recepcion->descripcion_anexos ?: 'Sin descripción' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Panel de Estado -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">Estado de la Recepción</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1">Acción Penal por Particular</h6>
                                        @if($recepcion->accion_penal)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-gavel"></i> Sí
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">No</span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1">Tiene Anexos</h6>
                                        @if($recepcion->tiene_anexos)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Sí ({{ $recepcion->numero_anexos }})
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times"></i> No
                                            </span>
                                        @endif
                                    </div>

                                    <hr>
                                    
                                    <div class="mb-2">
                                        <h6 class="text-muted mb-1">Última Actualización</h6>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($recepcion->updated_at)->format('d/m/Y H:i:s') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones Rápidas -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Acciones</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('recepcion.edit', $recepcion->id) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Editar Recepción
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="confirmarEliminacion({{ $recepcion->id }})">
                                            <i class="fas fa-trash"></i> Eliminar Recepción
                                        </button>
                                        <a href="{{ route('recepcion.create') }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Nueva Recepción
                                        </a>
                                        <hr class="my-2">
                                        <button class="btn btn-info btn-sm" onclick="window.print()">
                                            <i class="fas fa-print"></i> Imprimir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>¡Atención!</strong> Estás a punto de eliminar la recepción con NUC: <strong>{{ $recepcion->nuc }}</strong>
                </div>
                <p>Esta acción no se puede deshacer. ¿Estás seguro de que deseas continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/recepcion/${id}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>

<style>
@media print {
    .btn, .card-header .btn-group, .card:last-child {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection