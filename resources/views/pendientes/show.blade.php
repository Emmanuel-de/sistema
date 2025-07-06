@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detalles del Documento Pendiente</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Número de Documento:</label>
                                <p class="form-control-plaintext">{{ $documento->numero ?? 'S/N' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo:</label>
                                <p class="form-control-plaintext">{{ $documento->tipo ?? 'Sin tipo' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Solicitante:</label>
                                <p class="form-control-plaintext">{{ $documento->solicitante ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estado:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-warning">{{ ucfirst($documento->estado) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha Solicitud:</label>
                                <p class="form-control-plaintext">
                                    @if($documento->fecha_solicitud)
                                        {{ is_string($documento->fecha_solicitud) ? date('d/m/Y H:i', strtotime($documento->fecha_solicitud)) : $documento->fecha_solicitud->format('d/m/Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Prioridad:</label>
                                <p class="form-control-plaintext">
                                    @if($documento->prioridad)
                                        <span class="badge bg-{{ $documento->prioridad == 'urgente' ? 'danger' : ($documento->prioridad == 'alta' ? 'warning' : 'info') }}">
                                            {{ ucfirst($documento->prioridad) }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Asignado a:</label>
                                <p class="form-control-plaintext">
                                    {{ $documento->asignadoA->name ?? $documento->persona_ordena ?? 'Sin asignar' }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha de Creación:</label>
                                <p class="form-control-plaintext">
                                    {{ $documento->created_at ? $documento->created_at->format('d/m/Y H:i') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($documento->descripcion)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Descripción:</label>
                                    <div class="p-3 bg-light border rounded">
                                        {{ $documento->descripcion }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($documento->observaciones)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Observaciones:</label>
                                    <div class="p-3 bg-light border rounded">
                                        {{ $documento->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success" onclick="procesarDocumento({{ $documento->id }})">
                                    <i class="fas fa-check"></i> PROCESAR
                                </button>
                                <button type="button" class="btn btn-warning" onclick="rechazarDocumento({{ $documento->id }})">
                                    <i class="fas fa-ban"></i> RECHAZAR
                                </button>
                                <a href="{{ route('pendientes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> VOLVER
                                </a>
                            </div>
                        </div>
                    </div>
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

    .form-control-plaintext {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-bottom: 0;
    }

    .btn {
        border-radius: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
function procesarDocumento(id) {
    if (confirm('¿Está seguro de procesar este documento?')) {
        fetch('{{ route("pendientes.liberar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                documentos: [id],
                observaciones: 'Procesado desde vista de detalle'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '{{ route("pendientes.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar el documento');
        });
    }
}

function rechazarDocumento(id) {
    const motivo = prompt('Ingrese el motivo del rechazo:');
    if (motivo !== null && motivo.trim() !== '') {
        fetch('{{ route("pendientes.rechazar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                documentos: [id],
                motivo_rechazo: motivo
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '{{ route("pendientes.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al rechazar el documento');
        });
    }
}
</script>
@endpush
@endsection