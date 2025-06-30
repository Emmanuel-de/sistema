@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Lista de Recepciones</h4>
                    <a href="{{ route('recepcion.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Recepción
                    </a>
                </div>
                <div class="card-body">
                    <!-- Formulario de búsqueda -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('recepcion.search') }}" class="d-flex">
                                <input type="text" class="form-control me-2" name="q" 
                                       placeholder="Buscar por NUC, presentador o número de oficio..." 
                                       value="{{ request('q') }}">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </form>
                        </div>
                        @if(request('q'))
                        <div class="col-md-6">
                            <a href="{{ route('recepcion.index') }}" class="btn btn-link">
                                <i class="fas fa-times"></i> Limpiar búsqueda
                            </a>
                        </div>
                        @endif
                    </div>

                    @if($recepciones->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>NUC</th>
                                        <th>Tipo de Audiencia</th>
                                        <th>Quién Presenta</th>
                                        <th>No. Oficio</th>
                                        <th>Fecha Oficio</th>
                                        <th>Fojas</th>
                                        <th>Anexos</th>
                                        <th>Acción Penal</th>
                                        <th>Fecha Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recepciones as $recepcion)
                                    <tr>
                                        <td>
                                            <strong>{{ $recepcion->nuc }}</strong>
                                        </td>
                                        <td>{{ $recepcion->tipo_audiencia }}</td>
                                        <td>{{ $recepcion->quien_presenta }}</td>
                                        <td>{{ $recepcion->numero_oficio }}</td>
                                        <td>{{ \Carbon\Carbon::parse($recepcion->fecha_oficio)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $recepcion->numero_fojas }}</span>
                                        </td>
                                        <td>
                                            @if($recepcion->tiene_anexos)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> {{ $recepcion->numero_anexos }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times"></i> No
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($recepcion->accion_penal)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-gavel"></i> Sí
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">No</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($recepcion->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('recepcion.show', $recepcion->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('recepcion.edit', $recepcion->id) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmarEliminacion({{ $recepcion->id }})" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center">
                            {{ $recepciones->links() }}
                        </div>

                        <!-- Información de resultados -->
                        <div class="text-muted small mt-2">
                            Mostrando {{ $recepciones->firstItem() }} a {{ $recepciones->lastItem() }} 
                            de {{ $recepciones->total() }} resultados
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay recepciones registradas</h5>
                            @if(request('q'))
                                <p>No se encontraron resultados para "{{ request('q') }}"</p>
                                <a href="{{ route('recepcion.index') }}" class="btn btn-secondary">Ver todas</a>
                            @else
                                <p>Comienza creando tu primera recepción</p>
                                <a href="{{ route('recepcion.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Nueva Recepción
                                </a>
                            @endif
                        </div>
                    @endif
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
                ¿Estás seguro de que deseas eliminar esta recepción? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
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
@endsection