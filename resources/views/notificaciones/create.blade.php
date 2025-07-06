@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Crear Nueva Notificación</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('notificaciones.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-2">
                                <label for="audio" class="form-label">Cargar Audio...</label>
                                <input type="file" class="form-control" id="audio" name="audio" accept="audio/*">
                            </div>
                            <div class="col-md-3">
                                <label for="numero_telefono" class="form-label">Número Telefónico</label>
                                <input type="tel" class="form-control" id="numero_telefono" name="numero_telefono" 
                                       value="{{ old('numero_telefono') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label for="nuc_form" class="form-label">NUC</label>
                                <input type="text" class="form-control" id="nuc_form" name="nuc_form" 
                                       value="{{ old('nuc_form') }}" placeholder="Ingrese NUC">
                            </div>
                            <div class="col-md-3">
                                <label for="fecha_notificacion" class="form-label">Fecha Notificación</label>
                                <select class="form-select" id="fecha_notificacion" name="fecha_notificacion" required>
                                    <option value="">Seleccionar fecha</option>
                                    <option value="lunes" {{ old('fecha_notificacion') == 'lunes' ? 'selected' : '' }}>Lunes</option>
                                    <option value="martes" {{ old('fecha_notificacion', 'martes') == 'martes' ? 'selected' : '' }}>Martes</option>
                                    <option value="miercoles" {{ old('fecha_notificacion') == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                                    <option value="jueves" {{ old('fecha_notificacion') == 'jueves' ? 'selected' : '' }}>Jueves</option>
                                    <option value="viernes" {{ old('fecha_notificacion') == 'viernes' ? 'selected' : '' }}>Viernes</option>
                                    <option value="sabado" {{ old('fecha_notificacion') == 'sabado' ? 'selected' : '' }}>Sábado</option>
                                    <option value="domingo" {{ old('fecha_notificacion') == 'domingo' ? 'selected' : '' }}>Domingo</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="usuario_notifica" class="form-label">Usuario Que Notifica</label>
                                <select class="form-select" id="usuario_notifica" name="usuario_notifica" required>
                                    <option value="">Seleccionar usuario</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('usuario_notifica') == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="seleccione_notificacion" class="form-label">Seleccione Notificación</label>
                                <select class="form-select" id="seleccione_notificacion" name="seleccione_notificacion" required>
                                    <option value="">Seleccionar notificación</option>
                                    <option value="recordatorio_cita" {{ old('seleccione_notificacion') == 'recordatorio_cita' ? 'selected' : '' }}>Recordatorio de cita</option>
                                    <option value="confirmacion_pago" {{ old('seleccione_notificacion') == 'confirmacion_pago' ? 'selected' : '' }}>Confirmación de pago</option>
                                    <option value="aviso_vencimiento" {{ old('seleccione_notificacion') == 'aviso_vencimiento' ? 'selected' : '' }}>Aviso de vencimiento</option>
                                    <option value="notificacion_general" {{ old('seleccione_notificacion') == 'notificacion_general' ? 'selected' : '' }}>Notificación general</option>
                                </select>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="verPdf()">
                                        <i class="fas fa-file-pdf"></i> VER PDF
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="4" 
                                          placeholder="Ingrese observaciones...">{{ old('observaciones') }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> GUARDAR
                                    </button>
                                    <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> VOLVER
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function verPdf() {
        const notificacion = document.getElementById('seleccione_notificacion').value;
        if (notificacion) {
            window.open(`/notificaciones/pdf/${notificacion}`, '_blank');
        } else {
            alert('Por favor seleccione una notificación primero');
        }
    }

    // Validación del formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        const requiredFields = ['numero_telefono', 'fecha_notificacion', 'usuario_notifica', 'seleccione_notificacion'];
        let isValid = true;

        requiredFields.forEach(field => {
            const element = document.getElementById(field);
            if (!element.value.trim()) {
                element.classList.add('is-invalid');
                isValid = false;
            } else {
                element.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Por favor complete todos los campos requeridos');
        }
    });
</script>
@endpush

@push('styles')
<style>
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn {
        border-radius: 5px;
    }

    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endpush
@endsection