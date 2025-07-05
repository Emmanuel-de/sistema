@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Documentos Pendientes
                        <span class="badge bg-secondary ms-2" id="total-documentos">{{ $documentos->count() ?? 0 }}</span>
                    </h4>
                    <div class="d-flex gap-2">
                        <span class="badge bg-info">
                            <i class="fas fa-user me-1"></i>
                            Doc. Liberados por Auxiliar
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Barra de búsqueda -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="buscar-documento"
                                       placeholder="Introduce el texto para realizar la búsqueda...">
                                <button class="btn btn-outline-secondary" type="button" id="btn-buscar">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <button class="btn btn-outline-danger" type="button" id="btn-vaciar">
                                    <i class="fas fa-times"></i> Vaciar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Turnar
                                </button>
                                <button type="button" class="btn btn-secondary">
                                    <i class="fas fa-minus-circle"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de documentos -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabla-documentos">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th width="10%">No. Documento</th>
                                    <th width="15%">Tipo</th>
                                    <th width="20%">Descripción</th>
                                    <th width="15%">Solicitante</th>
                                    <th width="12%">Fecha Solicitud</th>
                                    <th width="12%">Estado</th>
                                    <th width="11%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documentos ?? [] as $documento)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input documento-checkbox" 
                                               value="{{ $documento->id }}">
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">
                                            {{ $documento->numero ?? 'S/N' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $documento->tipo ?? $documento->tipo_documento ?? 'Sin tipo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" 
                                             title="{{ $documento->descripcion ?? 'Sin descripción' }}">
                                            {{ $documento->descripcion ?? 'Sin descripción' }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-user me-1"></i>
                                        {{ $documento->solicitante ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-1"></i>
                                        @if($documento->fecha_solicitud)
                                            {{ is_string($documento->fecha_solicitud) ? date('d/m/Y', strtotime($documento->fecha_solicitud)) : $documento->fecha_solicitud->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>
                                            Pendiente
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary" 
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-success" 
                                                    title="Procesar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    title="Rechazar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>No hay documentos pendientes</h5>
                                            <p>Todos los documentos han sido procesados.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if(isset($documentos) && method_exists($documentos, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $documentos->links() }}
                    </div>
                    @endif
                </div>

                <!-- Footer con acciones masivas -->
                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success" id="btn-generar">
                                    <i class="fas fa-plus"></i> Generar
                                </button>
                                <button type="button" class="btn btn-primary" id="btn-regresar">
                                    <i class="fas fa-arrow-left"></i> Regresar
                                </button>
                                <button type="button" class="btn btn-info" id="btn-liberar">
                                    <i class="fas fa-unlock"></i> Liberar
                                </button>
                                <button type="button" class="btn btn-warning" id="btn-rechazar">
                                    <i class="fas fa-ban"></i> Rechazar
                                </button>
                                <button type="button" class="btn btn-secondary" id="btn-cerrar">
                                    <i class="fas fa-times"></i> Cerrar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <span id="documentos-seleccionados">0</span> documento(s) seleccionado(s)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmaciones -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirm-message">¿Está seguro de realizar esta acción?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirm-action">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.9em;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de selección múltiple
    const selectAll = document.getElementById('select-all');
    const documentCheckboxes = document.querySelectorAll('.documento-checkbox');
    const contadorSeleccionados = document.getElementById('documentos-seleccionados');
    
    // Seleccionar/deseleccionar todos
    selectAll?.addEventListener('change', function() {
        documentCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        actualizarContador();
    });
    
    // Actualizar contador cuando cambian las selecciones individuales
    documentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', actualizarContador);
    });
    
    function actualizarContador() {
        const seleccionados = document.querySelectorAll('.documento-checkbox:checked').length;
        contadorSeleccionados.textContent = seleccionados;
        
        // Actualizar estado del checkbox "select all"
        if (seleccionados === documentCheckboxes.length && documentCheckboxes.length > 0) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else if (seleccionados > 0) {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
    }
    
    // Funcionalidad de búsqueda
    const inputBuscar = document.getElementById('buscar-documento');
    const btnBuscar = document.getElementById('btn-buscar');
    const btnVaciar = document.getElementById('btn-vaciar');
    
    btnBuscar?.addEventListener('click', realizarBusqueda);
    inputBuscar?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            realizarBusqueda();
        }
    });
    
    btnVaciar?.addEventListener('click', function() {
        inputBuscar.value = '';
        // Aquí puedes agregar lógica para limpiar filtros
        location.reload();
    });
    
    function realizarBusqueda() {
        const termino = inputBuscar.value.trim();
        if (termino) {
            // Aquí implementarías la lógica de búsqueda
            console.log('Buscando:', termino);
        }
    }
    
    // Acciones de los botones del footer
    document.getElementById('btn-generar')?.addEventListener('click', function() {
        mostrarConfirmacion('¿Desea generar los documentos seleccionados?', 'generar');
    });
    
    document.getElementById('btn-liberar')?.addEventListener('click', function() {
        const seleccionados = document.querySelectorAll('.documento-checkbox:checked').length;
        if (seleccionados === 0) {
            alert('Debe seleccionar al menos un documento');
            return;
        }
        mostrarConfirmacion(`¿Desea liberar ${seleccionados} documento(s)?`, 'liberar');
    });
    
    document.getElementById('btn-rechazar')?.addEventListener('click', function() {
        const seleccionados = document.querySelectorAll('.documento-checkbox:checked').length;
        if (seleccionados === 0) {
            alert('Debe seleccionar al menos un documento');
            return;
        }
        mostrarConfirmacion(`¿Desea rechazar ${seleccionados} documento(s)?`, 'rechazar');
    });
    
    function mostrarConfirmacion(mensaje, accion) {
        document.getElementById('confirm-message').textContent = mensaje;
        document.getElementById('confirm-action').onclick = function() {
            ejecutarAccion(accion);
        };
        new bootstrap.Modal(document.getElementById('confirmModal')).show();
    }
    
    function ejecutarAccion(accion) {
        const documentosSeleccionados = Array.from(document.querySelectorAll('.documento-checkbox:checked'))
            .map(cb => cb.value);
        
        // Aquí implementarías las llamadas AJAX o redirecciones según la acción
        console.log(`Ejecutando acción: ${accion}`, documentosSeleccionados);
        
        // Cerrar modal
        bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
    }
});
</script>
@endpush