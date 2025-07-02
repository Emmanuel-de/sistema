@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>Formulario de Recepción</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('recepcion.store') }}">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                 <label for="nuc" class="form-label">NUC:</label> {{-- Aquí se establece el valor del NUC --}}
                                 <input type="text" class="form-control" id="nuc" name="nuc" value="{{ $nuc ?? old('nuc') }}" readonly>
                                  @error('nuc')
                                  <div class="text-danger">{{ $message }}</div>
                              @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="accion_penal" name="accion_penal" value="1" {{ old('accion_penal') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="accion_penal">
                                        Acción Penal Por Particular
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_audiencia" class="form-label">Tipo de Audiencia:</label>
                            <input type="text" class="form-control" id="tipo_audiencia" name="tipo_audiencia" value="{{ old('tipo_audiencia') }}">
                            @error('tipo_audiencia')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quien_presenta" class="form-label">Quién Presenta:</label>
                            <input type="text" class="form-control" id="quien_presenta" name="quien_presenta" value="{{ old('quien_presenta') }}">
                            @error('quien_presenta')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="numero_oficio" class="form-label">Número de Oficio:</label>
                                <input type="text" class="form-control" id="numero_oficio" name="numero_oficio" value="{{ old('numero_oficio') }}">
                                @error('numero_oficio')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="fecha_oficio" class="form-label">Fecha de Oficio:</label>
                                <input type="date" class="form-control" id="fecha_oficio" name="fecha_oficio" value="{{ old('fecha_oficio') }}">
                                @error('fecha_oficio')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="numero_fojas" class="form-label">Número de Hojas:</label>
                                <input type="number" class="form-control" id="numero_fojas" name="numero_fojas" value="{{ old('numero_fojas') }}" min="1">
                                @error('numero_fojas')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="tiene_anexos" name="tiene_anexos" value="1" {{ old('tiene_anexos') ? 'checked' : '' }}>
                                <label class="form-check-label" for="tiene_anexos">
                                    <strong>Anexos</strong>
                                </label>
                            </div>
                        </div>

                        <div id="anexos-section" style="display: {{ old('tiene_anexos') ? 'block' : 'none' }};">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="numero_anexos" class="form-label">Número de Anexos:</label>
                                    <input type="number" class="form-control" id="numero_anexos" name="numero_anexos" value="{{ old('numero_anexos') }}" min="0">
                                    @error('numero_anexos')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion_anexos" class="form-label">Descripción de Anexos:</label>
                                <textarea class="form-control" id="descripcion_anexos" name="descripcion_anexos" rows="3">{{ old('descripcion_anexos') }}</textarea>
                                @error('descripcion_anexos')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary me-md-2" onclick="window.history.back()">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Guardar Recepción
                            </button>
                        </div>
                    </form>
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