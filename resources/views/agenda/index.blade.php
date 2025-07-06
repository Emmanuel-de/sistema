@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Agenda - {{ $fechaActual->format('F Y') }}
                    </h4>
                    <div class="btn-group" role="group">
                        <a href="{{ route('agenda.index', ['mes' => $fechaActual->copy()->subMonth()->format('Y-m')]) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-chevron-left"></i> Anterior
                        </a>
                        <a href="{{ route('agenda.index', ['mes' => now()->format('Y-m')]) }}" 
                           class="btn btn-outline-secondary">
                            Hoy
                        </a>
                        <a href="{{ route('agenda.index', ['mes' => $fechaActual->copy()->addMonth()->format('Y-m')]) }}" 
                           class="btn btn-outline-primary">
                            Siguiente <i class="fas fa-chevron-right"></i>
                        </a>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevaCitaModal">
                            <i class="fas fa-plus"></i> Nueva Cita
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered calendar-table mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center py-3">Domingo</th>
                                    <th class="text-center py-3">Lunes</th>
                                    <th class="text-center py-3">Martes</th>
                                    <th class="text-center py-3">Miércoles</th>
                                    <th class="text-center py-3">Jueves</th>
                                    <th class="text-center py-3">Viernes</th>
                                    <th class="text-center py-3">Sábado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($semanas as $semana)
                                <tr>
                                    @foreach($semana as $dia)
                                    <td class="calendar-day p-0 {{ $dia['esMesActual'] ? '' : 'text-muted bg-light' }} {{ $dia['esHoy'] ? 'bg-warning bg-opacity-25' : '' }}" 
                                        style="height: 120px; width: 14.28%; vertical-align: top;">

                                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                            <span class="fw-bold {{ $dia['esHoy'] ? 'text-primary' : '' }}">
                                                {{ $dia['numero'] }}
                                            </span>
                                            <button class="btn btn-sm btn-outline-success btn-add-cita" 
                                                    data-fecha="{{ $dia['fecha'] }}" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#nuevaCitaModal"
                                                    title="Agregar cita">
                                                <i class="fas fa-plus" style="font-size: 10px;"></i>
                                            </button>
                                        </div>

                                        <div class="citas-container p-1" style="height: 85px; overflow-y: auto;">
                                            @if(isset($dia['citas']) && $dia['citas']->count() > 0)
                                                @foreach($dia['citas'] as $cita)
                                                <div class="cita-item mb-1 p-1 rounded text-white text-truncate" 
                                                     style="background-color: {{ $cita->color ?? '#007bff' }}; font-size: 11px; cursor: pointer;"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#verCitaModal"
                                                     data-cita-id="{{ $cita->id }}">
                                                    <div class="fw-bold">{{ $cita->hora }}</div>
                                                    <div>{{ Str::limit($cita->titulo, 15) }}</div>
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nueva Cita -->
<div class="modal fade" id="nuevaCitaModal" tabindex="-1" aria-labelledby="nuevaCitaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevaCitaModalLabel">
                    <i class="fas fa-calendar-plus me-2"></i>Nueva Cita
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('agenda.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hora_inicio" class="form-label">Hora Inicio</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_fin" class="form-label">Hora Fin</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>

                    <div class="mb-3">
                        <label for="cliente" class="form-label">Cliente</label>
                        <input type="text" class="form-control" id="cliente" name="cliente">
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Color</label>
                            <select class="form-control" id="color" name="color">
                                <option value="#007bff">Azul</option>
                                <option value="#28a745">Verde</option>
                                <option value="#dc3545">Rojo</option>
                                <option value="#ffc107">Amarillo</option>
                                <option value="#6f42c1">Morado</option>
                                <option value="#fd7e14">Naranja</option>
                                <option value="#20c997">Turquesa</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="cancelada">Cancelada</option>
                                <option value="completada">Completada</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Guardar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver/Editar Cita -->
<div class="modal fade" id="verCitaModal" tabindex="-1" aria-labelledby="verCitaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verCitaModalLabel">
                    <i class="fas fa-eye me-2"></i>Detalles de la Cita
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="citaDetalles">
                <!-- Contenido se carga dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="editarCita">
                    <i class="fas fa-edit me-2"></i>Editar
                </button>
                <button type="button" class="btn btn-danger" id="eliminarCita">
                    <i class="fas fa-trash me-2"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CSS personalizado -->
<style>
.calendar-table td {
    position: relative;
}

.calendar-table .calendar-day:hover {
    background-color: #f8f9fa !important;
}

.btn-add-cita {
    opacity: 0;
    transition: opacity 0.3s;
}

.calendar-day:hover .btn-add-cita {
    opacity: 1;
}

.cita-item:hover {
    transform: scale(1.02);
    transition: transform 0.2s;
}

.table-bordered td {
    border: 1px solid #dee2e6;
}

.calendar-day {
    min-height: 120px;
}

@media (max-width: 768px) {
    .calendar-day {
        min-height: 80px;
    }

    .citas-container {
        height: 50px !important;
    }

    .cita-item {
        font-size: 9px !important;
    }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar click en botón agregar cita
    document.querySelectorAll('.btn-add-cita').forEach(button => {
        button.addEventListener('click', function() {
            const fecha = this.getAttribute('data-fecha');
            document.getElementById('fecha').value = fecha;
        });
    });

    // Manejar click en cita para ver detalles
    document.querySelectorAll('.cita-item').forEach(item => {
        item.addEventListener('click', function() {
            const citaId = this.getAttribute('data-cita-id');
            cargarDetallesCita(citaId);
        });
    });

    // Función para cargar detalles de cita
    function cargarDetallesCita(citaId) {
        fetch(`/agenda/${citaId}`)
            .then(response => response.json())
            .then(data => {
                const detallesHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Fecha:</strong> ${data.fecha}
                        </div>
                        <div class="col-md-6">
                            <strong>Hora:</strong> ${data.hora_inicio} - ${data.hora_fin}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Título:</strong> ${data.titulo}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Cliente:</strong> ${data.cliente || 'No especificado'}
                        </div>
                        <div class="col-md-6">
                            <strong>Teléfono:</strong> ${data.telefono || 'No especificado'}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <strong>Email:</strong> ${data.email || 'No especificado'}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <strong>Descripción:</strong><br>
                            ${data.descripcion || 'Sin descripción'}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Estado:</strong> 
                            <span class="badge bg-${getEstadoColor(data.estado)}">${data.estado}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Color:</strong> 
                            <span class="badge" style="background-color: ${data.color}">${data.color}</span>
                        </div>
                    </div>
                `;

                document.getElementById('citaDetalles').innerHTML = detallesHtml;

                // Configurar botones de acción
                document.getElementById('editarCita').onclick = function() {
                    window.location.href = `/agenda/${citaId}/edit`;
                };

                document.getElementById('eliminarCita').onclick = function() {
                    if (confirm('¿Estás seguro de que quieres eliminar esta cita?')) {
                        eliminarCita(citaId);
                    }
                };
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los detalles de la cita');
            });
    }

    // Función para eliminar cita
    function eliminarCita(citaId) {
        fetch(`/agenda/${citaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar la cita');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la cita');
        });
    }

    // Función para obtener color del estado
    function getEstadoColor(estado) {
        switch(estado) {
            case 'pendiente': return 'warning';
            case 'confirmada': return 'success';
            case 'cancelada': return 'danger';
            case 'completada': return 'info';
            default: return 'secondary';
        }
    }
});
</script>
@endsection