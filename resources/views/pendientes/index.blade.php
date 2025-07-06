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
                            <form id="search-form">
                                @csrf
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="buscar-documento"
                                           name="buscar"
                                           placeholder="Introduce el texto para realizar la búsqueda...">
                                    <button class="btn btn-outline-secondary" type="submit" id="btn-buscar">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                    <button class="btn btn-outline-danger" type="button" id="btn-vaciar">
                                        <i class="fas fa-times"></i> Vaciar
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-secondary" id="btn-turnar-header">
                                    <i class="fas fa-undo"></i> Turnar
                                </button>
                                <button type="button" class="btn btn-secondary" id="btn-eliminar-header">
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
                            <tbody id="tabla-body">
                                @include('pendientes.partials.table-rows', ['documentos' => $documentos])
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if(isset($documentos) && method_exists($documentos, 'links'))
                    <div class="d-flex justify-content-center mt-3" id="pagination-container">
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
                <div id="extra-input" style="display: none;">
                    <label for="modal-input" class="form-label">Observaciones:</label>
                    <textarea class="form-control" id="modal-input" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirm-action">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para turnar documentos -->
<div class="modal fade" id="turnarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Turnar Documentos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="turnar-usuario" class="form-label">Seleccionar Usuario:</label>
                    <select class="form-select" id="turnar-usuario">
                        <option value="">Seleccione un usuario</option>
                        <!-- Se llenará dinámicamente -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="turnar-observaciones" class="form-label">Observaciones:</label>
                    <textarea class="form-control" id="turnar-observaciones" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="ejecutar-turnar">Turnar</button>
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

    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentAction = null;
    let selectedDocuments = [];

    // Elementos del DOM
    const selectAll = document.getElementById('select-all');
    const contadorSeleccionados = document.getElementById('documentos-seleccionados');
    const tablaBody = document.getElementById('tabla-body');
    const searchForm = document.getElementById('search-form');
    const inputBuscar = document.getElementById('buscar-documento');
    const btnVaciar = document.getElementById('btn-vaciar');

    // Inicializar funcionalidades
    initializeCheckboxes();
    initializeSearch();
    initializeButtons();
    initializeTableButtons();

    // Funcionalidad de selección múltiple
    function initializeCheckboxes() {
        // Seleccionar/deseleccionar todos
        selectAll?.addEventListener('change', function() {
            const documentCheckboxes = document.querySelectorAll('.documento-checkbox');
            documentCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            actualizarContador();
        });
        
        // Actualizar contador cuando cambian las selecciones individuales
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('documento-checkbox')) {
                actualizarContador();
            }
        });
    }
    
    function actualizarContador() {
        const documentCheckboxes = document.querySelectorAll('.documento-checkbox');
        const seleccionados = document.querySelectorAll('.documento-checkbox:checked').length;
        contadorSeleccionados.textContent = seleccionados;
        
        selectedDocuments = Array.from(document.querySelectorAll('.documento-checkbox:checked'))
            .map(cb => cb.value);
        
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
    function initializeSearch() {
        searchForm?.addEventListener('submit', function(e) {
            e.preventDefault();
            realizarBusqueda();
        });
        
        btnVaciar?.addEventListener('click', function() {
            inputBuscar.value = '';
            location.reload();
        });
    }
    
    function realizarBusqueda() {
        const termino = inputBuscar.value.trim();
        
        // Mostrar indicador de carga
        tablaBody.classList.add('loading');
        
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('termino', termino);
        
        fetch('{{ route("pendientes.search") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                tablaBody.innerHTML = data.html;
                document.getElementById('total-documentos').textContent = data.total;
                actualizarContador();
                initializeTableButtons(); // Reinicializar botones de la tabla
            } else {
                showAlert('No se encontraron resultados', 'warning');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al realizar la búsqueda', 'danger');
        })
        .finally(() => {
            tablaBody.classList.remove('loading');
        });
    }
    
    // Inicializar botones principales
    function initializeButtons() {
        // Botón Generar
        document.getElementById('btn-generar')?.addEventListener('click', function() {
            window.location.href = '{{ route("pendientes.create") }}';
        });
        
        // Botón Regresar
        document.getElementById('btn-regresar')?.addEventListener('click', function() {
            window.history.back();
        });
        
        // Botón Liberar
        document.getElementById('btn-liberar')?.addEventListener('click', function() {
            if (selectedDocuments.length === 0) {
                showAlert('Debe seleccionar al menos un documento', 'warning');
                return;
            }
            mostrarConfirmacion(
                `¿Desea liberar ${selectedDocuments.length} documento(s)?`, 
                'liberar',
                true
            );
        });
        
        // Botón Rechazar
        document.getElementById('btn-rechazar')?.addEventListener('click', function() {
            if (selectedDocuments.length === 0) {
                showAlert('Debe seleccionar al menos un documento', 'warning');
                return;
            }
            mostrarConfirmacion(
                `¿Desea rechazar ${selectedDocuments.length} documento(s)?`, 
                'rechazar',
                true
            );
        });
        
        // Botón Cerrar
        document.getElementById('btn-cerrar')?.addEventListener('click', function() {
            if (confirm('¿Está seguro que desea cerrar esta ventana?')) {
                window.close();
            }
        });
        
        // Botones del header
        document.getElementById('btn-turnar-header')?.addEventListener('click', function() {
            if (selectedDocuments.length === 0) {
                showAlert('Debe seleccionar al menos un documento', 'warning');
                return;
            }
            mostrarModalTurnar();
        });
        
        document.getElementById('btn-eliminar-header')?.addEventListener('click', function() {
            if (selectedDocuments.length === 0) {
                showAlert('Debe seleccionar al menos un documento', 'warning');
                return;
            }
            mostrarConfirmacion(
                `¿Desea eliminar ${selectedDocuments.length} documento(s)?`, 
                'eliminar'
            );
        });
    }
    
    // Inicializar botones de la tabla
    function initializeTableButtons() {
        // Botones Ver
        document.querySelectorAll('.btn-ver').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentoId = this.dataset.id;
                window.location.href = `{{ route('pendientes.index') }}/${documentoId}`;
            });
        });
        
        // Botones Procesar individuales
        document.querySelectorAll('.btn-procesar').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentoId = this.dataset.id;
                selectedDocuments = [documentoId];
                mostrarConfirmacion('¿Desea procesar este documento?', 'liberar', true);
            });
        });
        
        // Botones Rechazar individuales
        document.querySelectorAll('.btn-rechazar-individual').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentoId = this.dataset.id;
                selectedDocuments = [documentoId];
                mostrarConfirmacion('¿Desea rechazar este documento?', 'rechazar', true);
            });
        });
    }
    
    function mostrarConfirmacion(mensaje, accion, requiereInput = false) {
        currentAction = accion;
        document.getElementById('confirm-message').textContent = mensaje;
        
        const extraInput = document.getElementById('extra-input');
        if (requiereInput) {
            extraInput.style.display = 'block';
            document.getElementById('modal-input').setAttribute('required', 'required');
        } else {
            extraInput.style.display = 'none';
            document.getElementById('modal-input').removeAttribute('required');
        }
        
        new bootstrap.Modal(document.getElementById('confirmModal')).show();
    }
    
    function mostrarModalTurnar() {
        // Cargar usuarios
        fetch('{{ route("pendientes.index") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(() => {
            // Por simplicidad, agregar opciones estáticas. En producción, cargar desde API
            const select = document.getElementById('turnar-usuario');
            select.innerHTML = `
                <option value="">Seleccione un usuario</option>
                <option value="1">Usuario 1</option>
                <option value="2">Usuario 2</option>
                <option value="3">Usuario 3</option>
            `;
            new bootstrap.Modal(document.getElementById('turnarModal')).show();
        });
    }
    
    // Evento para confirmar acciones
    document.getElementById('confirm-action')?.addEventListener('click', function() {
        const observaciones = document.getElementById('modal-input').value;
        ejecutarAccion(currentAction, observaciones);
    });
    
    // Evento para ejecutar turnar
    document.getElementById('ejecutar-turnar')?.addEventListener('click', function() {
        const usuario = document.getElementById('turnar-usuario').value;
        const observaciones = document.getElementById('turnar-observaciones').value;
        
        if (!usuario) {
            showAlert('Debe seleccionar un usuario', 'warning');
            return;
        }
        
        ejecutarAccion('turnar', observaciones, usuario);
    });
    
    function ejecutarAccion(accion, observaciones = '', turnarA = null) {
        const data = {
            _token: document.querySelector('meta[name="csrf-token"]').content,
            documentos: selectedDocuments
        };
        
        if (observaciones) {
            data.observaciones = observaciones;
        }
        
        if (accion === 'rechazar') {
            data.motivo_rechazo = observaciones;
        }
        
        if (accion === 'turnar' && turnarA) {
            data.turnar_a = turnarA;
        }
        
        let url;
        switch (accion) {
            case 'liberar':
                url = '{{ route("pendientes.liberar") }}';
                break;
            case 'rechazar':
                url = '{{ route("pendientes.rechazar") }}';
                break;
            case 'turnar':
                url = '{{ route("pendientes.turnar") }}';
                break;
            case 'eliminar':
                // Implementar ruta de eliminación
                url = '{{ route("pendientes.index") }}/eliminar';
                break;
            default:
                showAlert('Acción no válida', 'danger');
                return;
        }
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                // Cerrar modales
                bootstrap.Modal.getInstance(document.getElementById('confirmModal'))?.hide();
                bootstrap.Modal.getInstance(document.getElementById('turnarModal'))?.hide();
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert(data.message || 'Error al ejecutar la acción', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al ejecutar la acción', 'danger');
        });
    }
    
    function showAlert(message, type = 'info') {
        // Crear alerta Bootstrap
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
@endpush