@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <h5 class="fw-bold border-bottom pb-2">Editar Datos Complementarios</h5>

    {{-- Mensajes de Sesión --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6>Errores de Validación:</h6>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- BOTONES SUPERIORES --}}
    <div class="d-flex flex-wrap gap-3 my-3">
        <a href="{{ route('complementar.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle-fill"></i> Volver al Listado
        </a>
        <a href="#" class="btn btn-outline-danger"> {{-- Reemplaza # con la ruta real si existe --}}
            <i class="bi bi-file-earmark-pdf-fill"></i> Ver PDF
        </a>
        <a href="#" class="btn btn-outline-primary"> {{-- Reemplaza # con la ruta real si existe --}}
            <i class="bi bi-save2-fill"></i> Complementar y Turnar
        </a>
        {{-- Asegúrate de que $documento exista antes de intentar acceder a su id --}}
        @if (isset($documento) && $documento->id)
            <form action="{{ route('complementar.destroy', $documento->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar este registro?');" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle-fill"></i> Borrar
                </button>
            </form>
        @endif
    </div>

    {{-- FORMULARIO DE EDICIÓN --}}
    <div class="row justify-content-center">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Información General</h6>
                    {{-- El método PUT/PATCH se usa para actualizaciones --}}
                    {{-- Asegúrate de que $documento exista antes de intentar usar su id --}}
                    @if (isset($documento))
                        <form action="{{ route('complementar.update', $documento->id) }}" method="POST">
                            @csrf
                            @method('PUT') {{-- Importante para las actualizaciones --}}

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="folio_unico" class="form-label">Folio Único:</label>
                                    <input type="text" name="folio_unico" id="folio_unico" class="form-control @error('folio_unico') is-invalid @enderror" value="{{ old('folio_unico', $documento->folio_unico) }}">
                                    @error('folio_unico')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                   <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
                                   <select name="tipo_documento" id="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror">
                                        <option value="">Seleccione una opción</option>
                                        <option value="oficio" {{ old('tipo_documento', $documento->tipo_documento) == 'oficio' ? 'selected' : '' }}>Oficio</option>
                                        <option value="memorandum" {{ old('tipo_documento', $documento->tipo_documento) == 'memorandum' ? 'selected' : '' }}>Memorandum</option>
                                        <option value="circular" {{ old('tipo_documento', $documento->tipo_documento) == 'circular' ? 'selected' : '' }}>Circular</option>
                                        <option value="informe" {{ old('tipo_documento', $documento->tipo_documento) == 'informe' ? 'selected' : '' }}>Informe</option>
                                   </select>
                                   @error('tipo_documento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                   @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="nuc" class="form-label">NUC:</label>
                                    <input type="text" name="nuc" id="nuc" class="form-control @error('nuc') is-invalid @enderror" value="{{ old('nuc', $documento->nuc) }}">
                                    @error('nuc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="fecha_recepcion" class="form-label">Fecha de Recepción:</label>
                                    <input type="date" name="fecha_recepcion" id="fecha_recepcion" class="form-control @error('fecha_recepcion') is-invalid @enderror" value="{{ old('fecha_recepcion', $documento->fecha_recepcion) }}">
                                    @error('fecha_recepcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="quien_presenta" class="form-label">Quién Presenta:</label>
                                    <input type="text" name="quien_presenta" id="quien_presenta" class="form-control @error('quien_presenta') is-invalid @enderror" value="{{ old('quien_presenta', $documento->quien_presenta) }}">
                                    @error('quien_presenta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="numero_hojas" class="form-label">Número de Hojas:</label>
                                    <input type="number" name="numero_hojas" id="numero_hojas" class="form-control @error('numero_hojas') is-invalid @enderror" value="{{ old('numero_hojas', $documento->numero_hojas) }}">
                                    @error('numero_hojas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="numero_anexos" class="form-label">Número de Anexos:</label>
                                    <input type="number" name="numero_anexos" id="numero_anexos" class="form-control @error('numero_anexos') is-invalid @enderror" value="{{ old('numero_anexos', $documento->numero_anexos) }}">
                                    @error('numero_anexos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="descripcion" class="form-label">Descripción:</label>
                                    <input type="text" name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion', $documento->descripcion) }}">
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="numero_oficio" class="form-label">Número de Oficio:</label>
                                    <input type="text" name="numero_oficio" id="numero_oficio" class="form-control @error('numero_oficio') is-invalid @enderror" value="{{ old('numero_oficio', $documento->numero_oficio) }}">
                                    @error('numero_oficio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="fecha_oficio" class="form-label">Fecha de Oficio:</label>
                                    <input type="date" name="fecha_oficio" id="fecha_oficio" class="form-control @error('fecha_oficio') is-invalid @enderror" value="{{ old('fecha_oficio', $documento->fecha_oficio) }}">
                                    @error('fecha_oficio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="tipo_audiencia" class="form-label">Tipo de Audiencia:</label>
                                    <select name="tipo_audiencia" id="tipo_audiencia" class="form-select @error('tipo_audiencia') is-invalid @enderror">
                                        <option value="">Seleccione una opción</option>
                                        <option value="inicial" {{ old('tipo_audiencia', $documento->tipo_audiencia) == 'inicial' ? 'selected' : '' }}>Inicial</option>
                                        <option value="intermedia" {{ old('tipo_audiencia', $documento->tipo_audiencia) == 'intermedia' ? 'selected' : '' }}>Intermedia</option>
                                        <option value="juicio" {{ old('tipo_audiencia', $documento->tipo_audiencia) == 'juicio' ? 'selected' : '' }}>Juicio</option>
                                        <option value="sentencia" {{ old('tipo_audiencia', $documento->tipo_audiencia) == 'sentencia' ? 'selected' : '' }}>Sentencia</option>
                                    </select>
                                    @error('tipo_audiencia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="numero_amparo" class="form-label">N. de Amparo:</label>
                                    <input type="text" name="numero_amparo" id="numero_amparo" class="form-control @error('numero_amparo') is-invalid @enderror" value="{{ old('numero_amparo', $documento->numero_amparo) }}">
                                    @error('numero_amparo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="precedencia" class="form-label">Precedencia:</label>
                                    <input type="text" name="precedencia" id="precedencia" class="form-control @error('precedencia') is-invalid @enderror" value="{{ old('precedencia', $documento->precedencia) }}">
                                    @error('precedencia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="entidad" class="form-label">Entidad:</label>
                                    <select name="entidad" id="entidad" class="form-select @error('entidad') is-invalid @enderror">
                                        <option value="">Seleccione una entidad</option>
                                        <option value="Fiscalía General" {{ old('entidad', $documento->entidad) == 'Fiscalía General' ? 'selected' : '' }}>Fiscalía General</option>
                                        <option value="Defensoría Pública" {{ old('entidad', $documento->entidad) == 'Defensoría Pública' ? 'selected' : '' }}>Defensoría Pública</option>
                                        <option value="Tribunal de Justicia" {{ old('entidad', $documento->entidad) == 'Tribunal de Justicia' ? 'selected' : '' }}>Tribunal de Justicia</option>
                                        <option value="Policía Investigadora" {{ old('entidad', $documento->entidad) == 'Policía Investigadora' ? 'selected' : '' }}>Policía Investigadora</option>
                                    </select>
                                    @error('entidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="solicita_informe" class="form-label">Solicita Informe:</label>
                                    <select name="solicita_informe" id="solicita_informe" class="form-select @error('solicita_informe') is-invalid @enderror">
                                        <option value="">Seleccione una opción</option>
                                        <option value="si" {{ old('solicita_informe', $documento->solicita_informe) == 'si' ? 'selected' : '' }}>Sí</option>
                                        <option value="no" {{ old('solicita_informe', $documento->solicita_informe) == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('solicita_informe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-save-fill"></i> Actualizar Digitalización
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="text-muted text-center">No se pudieron cargar los datos para edición.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nucInput = document.getElementById('nuc');

        // Función principal para autocompletar
        function autocompleteRecepcionFields() {
            const nuc = nucInput.value.trim();

            if (nuc.length > 0) { // Solo si hay algo escrito en el NUC
                fetch(`{{ route('complementar.getRecepcionData') }}?nuc=${nuc}`)
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 404) {
                                console.log('No se encontraron datos para el NUC:', nuc);
                            } else {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && !data.error && !data.message) {
                            console.log('Datos recibidos para NUC:', data);

                            const fieldsToAutocomplete = {
                                // Mapeo de nombres de columna de 'recepciones' a IDs de campos en 'complementar.blade.php'
                                'fecha_oficio': data.fecha_oficio,
                                'quien_presenta': data.quien_presenta,
                                'numero_hojas': data.numero_fojas, // Mapea 'numero_fojas' de recepciones a 'numero_hojas' del formulario
                                'numero_anexos': data.numero_anexos,
                                'descripcion': data.descripcion_anexos, // Mapea 'descripcion_anexos' de recepciones a 'descripcion' del formulario
                                // Si necesitas autocompletar más campos de 'recepciones' en el formulario 'complementar',
                                // asegúrate de que existan en la tabla 'recepciones' y añádelos aquí.
                                'tipo_audiencia': data.tipo_audiencia,
                                'numero_oficio': data.numero_oficio,
                            };

                            for (const fieldId in fieldsToAutocomplete) {
                                const inputField = document.getElementById(fieldId);
                                // Solo autocompleta si el campo está vacío
                                if (inputField && fieldsToAutocomplete[fieldId] !== undefined && fieldsToAutocomplete[fieldId] !== null) {
                                    if (!inputField.value || inputField.value === '') { // Esta condición es clave para no sobrescribir datos existentes
                                        if (inputField.tagName === 'SELECT') {
                                            const optionExists = Array.from(inputField.options).some(option => option.value === fieldsToAutocomplete[fieldId]);
                                            if (optionExists) {
                                                inputField.value = fieldsToAutocomplete[fieldId];
                                            } else {
                                                console.warn(`Opción "${fieldsToAutocomplete[fieldId]}" no encontrada para el select "${fieldId}".`);
                                            }
                                        } else {
                                            inputField.value = fieldsToAutocomplete[fieldId];
                                        }
                                    }
                                }
                            }
                        } else if (data.message) {
                             console.log('Mensaje del servidor:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener datos de recepción:', error);
                    });
            }
        }

        nucInput.addEventListener('blur', autocompleteRecepcionFields);

        // Si hay un NUC precargado (en edición), intenta autocompletar al cargar la página
        if (nucInput.value.trim() !== '') {
            autocompleteRecepcionFields();
        }
    });
</script>
@endsection
