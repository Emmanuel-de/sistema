@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Editar Recepción</h4>
                    <div class="btn-group">
                        <a href="{{ route('recepcion.show', $recepcion->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('recepcion.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Editando recepción:</strong> {{ $recepcion->nuc }}
                        <small class="d-block">Registrada el {{ \Carbon\Carbon::parse($recepcion->created_at)->format('d/m/Y H:i') }}</small>
                    </div>

                    <form method="POST" action="{{ route('recepcion.update', $recepcion->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="nuc" class="form-label">NUC: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nuc') is-invalid @enderror" 
                                       id="nuc" name="nuc" value="{{ old('nuc', $recepcion->nuc) }}" required>
                                @error('nuc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="accion_penal" 
                                           name="accion_penal" value="1" 
                                           {{ old('accion_penal', $recepcion->accion_penal) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="accion_penal">
                                        Acción Penal Por Particular
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_audiencia" class="form-label">Tipo de Audiencia: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tipo_audiencia') is-invalid @enderror" 
                                   id="tipo_audiencia" name="tipo_audiencia" 
                                   value="{{ old('tipo_audiencia', $recepcion->tipo_audiencia) }}" required>
                            @error('tipo_audiencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quien_presenta" class="form-label">Quién Presenta: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('quien_presenta') is-invalid @enderror" 
                                   id="quien_presenta" name="quien_presenta" 
                                   value="{{ old('quien_presenta', $recepcion->quien_presenta) }}" required>
                            @error('quien_presenta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="numero_oficio" class="form-label">Número de Oficio: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('numero_oficio') is-invalid @enderror" 
                                       id="numero_oficio" name="numero_oficio" 
                                       value="{{ old('numero_oficio', $recepcion->numero_oficio) }}" required>
                                @error('numero_oficio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="fecha_oficio" class="form-label">Fecha de Oficio: <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_oficio') is-invalid @enderror" 
                                       id="fecha_oficio" name="fecha_oficio" 
                                       value="{{ old('fecha_oficio', $recepcion->fecha_oficio) }}" required>
                                @error('fecha_oficio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="numero_fojas" class="form-label">Número de Fojas: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('numero_fojas') is-invalid @enderror" 
                                       id="numero_fojas" name="numero_fojas" 
                                       value="{{ old('numero_fojas', $recepcion->numero_fojas) }}" min="1" required>
                                @error('numero_fojas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="tiene_anexos" 
                                       name="tiene_anexos" value="1" 
                                       {{ old('tiene_anexos', $recepcion->tiene_anexos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tiene_anexos">
                                    <strong>Anexos</strong>
                                </label>
                            </div>
                        </div>

                        <div id="anexos-section" style="display: {{ old('tiene_anexos', $recepcion->tiene_anexos) ? 'block' : 'none' }};">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="numero_anexos" class="form-label">Número de Anexos:</label>
                                    <input type="number" class="form-control @error('numero_anexos') is-invalid @enderror" 
                                           id="numero_anexos" name="numero_anexos" 
                                           value="{{ old('numero_anexos', $recepcion->numero_anexos) }}" min="0">
                                    @error('numero_anexos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion_anexos" class="form-label">Descripción de Anexos:</label>
                                <textarea class="form-control @error('descripcion_anexos') is-invalid @enderror" 
                                          id="descripcion_anexos" name="descripcion_anexos" 
                                          rows="3">{{ old('descripcion_anexos', $recepcion->descripcion_anexos) }}</textarea>
                                @error('descripcion_anexos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Los campos marcados con <span class="text-danger">*</span> son obligatorios
                                </small>
                            </div>
                            <div class="col-md-6">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('recepcion.show', $recepcion->id) }}" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Actualizar Recepción
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus"></i>
                                <strong>Creado:</strong><br>
                                {{ \Carbon\Carbon::parse($recepcion->created_at)->format('d/m/Y H:i:s') }}
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-calendar-check"></i>
                                <strong>Última actualización:</strong><br>
                                {{ \Carbon\Carbon::parse($recepcion->updated_at)->format('d/m/Y H:i:s') }}
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-hashtag"></i>
                                <strong>ID:</strong> {{ $recepcion->id }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tieneAnexosCheckbox = document.getElementById('tiene_anexos');
    const anexosSection = document.getElementById('anexos-section');
    
    tieneAnexosCheckbox.addEventListener('change', function() {
        if (this.checked) {
            anexosSection.style.display = 'block';
        } else {
            anexosSection.style.display = 'none';
            // Limpiar campos de anexos cuando se oculta la sección
            document.getElementById('numero_anexos').value = '';
            document.getElementById('descripcion_anexos').value = '';
        }
    });
});
</script>
@endsection