@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <h5 class="fw-bold border-bottom pb-2">Datos Complementarios</h5>

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
        <a href="#" class="btn btn-outline-danger"> {{-- Reemplaza # con la ruta real si existe --}}
            <i class="bi bi-file-earmark-pdf-fill"></i> Ver PDF
        </a>
        <a href="#" class="btn btn-outline-primary"> {{-- Reemplaza # con la ruta real si existe --}}
            <i class="bi bi-save2-fill"></i> Complementar y Turnar
        </a>
        <a href="#" class="btn btn-outline-secondary"> {{-- Reemplaza # con la ruta real si existe --}}
            <i class="bi bi-pencil-fill"></i> Editar
        </a>
        <a href="#" class="btn btn-outline-danger"> {{-- Reemplaza # con la ruta real si existe --}}
            <i class="bi bi-x-circle-fill"></i> Borrar
        </a>
        <a href="{{ route('complementar.create') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-clockwise"></i> Volver a Cargar Datos
        </a>
        <a href="#" class="btn btn-outline-info"> {{-- Reemplaza # con la ruta real si existe --}}
            <i class="bi bi-upc-scan"></i> Digitalizar de nuevo
        </a>
    </div>

    {{-- DOS COLUMNAS: FORMULARIO Y TABLA --}}
    <div class="row">
        {{-- FORMULARIO --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Información General</h6>
                    <form action="{{ route('complementar.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="folio_unico" class="form-label">Folio Único:</label>
                                <input type="text" name="folio_unico" id="folio_unico" class="form-control @error('folio_unico') is-invalid @enderror" value="{{ old('folio_unico') }}">
                                @error('folio_unico')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                               <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
                               <select name="tipo_documento" id="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror">
                                   <option value="">Seleccione una opción</option>
                                   {{-- ¡CORREGIDO! Valores en minúsculas para coincidir con la migración y el controlador --}}
                                   <option value="oficio" {{ old('tipo_documento') == 'oficio' ? 'selected' : '' }}>Oficio</option>
                                   <option value="memorandum" {{ old('tipo_documento') == 'memorandum' ? 'selected' : '' }}>Memorandum</option>
                                   <option value="circular" {{ old('tipo_documento') == 'circular' ? 'selected' : '' }}>Circular</option>
                                   <option value="informe" {{ old('tipo_documento') == 'informe' ? 'selected' : '' }}>Informe</option>
                               </select>
                               @error('tipo_documento')
                                   <div class="invalid-feedback">{{ $message }}</div>
                               @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="nuc" class="form-label">NUC:</label>
                                <input type="text" name="nuc" id="nuc" class="form-control @error('nuc') is-invalid @enderror" value="{{ old('nuc') }}">
                                @error('nuc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="fecha_recepcion" class="form-label">Fecha de Recepción:</label>
                                <input type="date" name="fecha_recepcion" id="fecha_recepcion" class="form-control @error('fecha_recepcion') is-invalid @enderror" value="{{ old('fecha_recepcion') }}">
                                @error('fecha_recepcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="quien_presenta" class="form-label">Quién Presenta:</label>
                                <input type="text" name="quien_presenta" id="quien_presenta" class="form-control @error('quien_presenta') is-invalid @enderror" value="{{ old('quien_presenta') }}">
                                @error('quien_presenta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="numero_hojas" class="form-label">Número de Hojas:</label>
                                {{-- ¡CORREGIDO! 'name' a 'numero_hojas' --}}
                                <input type="number" name="numero_hojas" id="numero_hojas" class="form-control @error('numero_hojas') is-invalid @enderror" value="{{ old('numero_hojas') }}">
                                @error('numero_hojas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="numero_anexos" class="form-label">Número de Anexos:</label>
                                {{-- ¡CORREGIDO! 'name' a 'numero_anexos' --}}
                                <input type="number" name="numero_anexos" id="numero_anexos" class="form-control @error('numero_anexos') is-invalid @enderror" value="{{ old('numero_anexos') }}">
                                @error('numero_anexos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="descripcion" class="form-label">Descripción:</label>
                                <input type="text" name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion') }}">
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="numero_oficio" class="form-label">Número de Oficio:</label>
                                <input type="text" name="numero_oficio" id="numero_oficio" class="form-control @error('numero_oficio') is-invalid @enderror" value="{{ old('numero_oficio') }}">
                                @error('numero_oficio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="fecha_oficio" class="form-label">Fecha de Oficio:</label>
                                <input type="date" name="fecha_oficio" id="fecha_oficio" class="form-control @error('fecha_oficio') is-invalid @enderror" value="{{ old('fecha_oficio') }}">
                                @error('fecha_oficio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="tipo_audiencia" class="form-label">Tipo de Audiencia:</label>
                                <select name="tipo_audiencia" id="tipo_audiencia" class="form-select @error('tipo_audiencia') is-invalid @enderror">
                                    <option value="">Seleccione una opción</option>
                                    {{-- ¡CORREGIDO! Valores en minúsculas para coincidir con la migración y el controlador --}}
                                    <option value="inicial" {{ old('tipo_audiencia') == 'inicial' ? 'selected' : '' }}>Inicial</option>
                                    <option value="intermedia" {{ old('tipo_audiencia') == 'intermedia' ? 'selected' : '' }}>Intermedia</option>
                                    <option value="juicio" {{ old('tipo_audiencia') == 'juicio' ? 'selected' : '' }}>Juicio</option>
                                    <option value="sentencia" {{ old('tipo_audiencia') == 'sentencia' ? 'selected' : '' }}>Sentencia</option>
                                </select>
                                @error('tipo_audiencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="numero_amparo" class="form-label">N. de Amparo:</label>
                                {{-- ¡CORREGIDO! 'name' a 'numero_amparo' --}}
                                <input type="text" name="numero_amparo" id="numero_amparo" class="form-control @error('numero_amparo') is-invalid @enderror" value="{{ old('numero_amparo') }}">
                                @error('numero_amparo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="precedencia" class="form-label">Precedencia:</label>
                                {{-- ¡CORREGIDO! 'name' a 'precedencia' --}}
                                <input type="text" name="precedencia" id="precedencia" class="form-control @error('precedencia') is-invalid @enderror" value="{{ old('precedencia') }}">
                                @error('precedencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="entidad" class="form-label">Entidad:</label>
                                <select name="entidad" id="entidad" class="form-select @error('entidad') is-invalid @enderror">
                                    <option value="">Seleccione una entidad</option>
                                    <option value="Fiscalía General" {{ old('entidad') == 'Fiscalía General' ? 'selected' : '' }}>Fiscalía General</option>
                                    <option value="Defensoría Pública" {{ old('entidad') == 'Defensoría Pública' ? 'selected' : '' }}>Defensoría Pública</option>
                                    <option value="Tribunal de Justicia" {{ old('entidad') == 'Tribunal de Justicia' ? 'selected' : '' }}>Tribunal de Justicia</option>
                                    <option value="Policía Investigadora" {{ old('entidad') == 'Policía Investigadora' ? 'selected' : '' }}>Policía Investigadora</option>
                                </select>
                                @error('entidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="solicita_informe" class="form-label">Solicita Informe:</label>
                                <select name="solicita_informe" id="solicita_informe" class="form-select @error('solicita_informe') is-invalid @enderror">
                                    <option value="">Seleccione una opción</option>
                                    {{-- ¡CORREGIDO! Valores en minúsculas para coincidir con la migración y el controlador --}}
                                    <option value="si" {{ old('solicita_informe') == 'si' ? 'selected' : '' }}>Sí</option>
                                    <option value="no" {{ old('solicita_informe') == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('solicita_informe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check2-square"></i> Guardar Digitalización
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Registros</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Folio Único</th> {{-- Ajustado el nombre para que coincida --}}
                                    <th>Tipo Documento</th>
                                    <th>NUC</th> {{-- Ajustado el nombre para que coincida --}}
                                    <th>Fecha Recepción</th> {{-- Ajustado el nombre para que coincida --}}
                                    <th>Quién Presenta</th> {{-- Ajustado el nombre para que coincida --}}
                                    <th>No. Hojas</th> {{-- Ajustado el nombre para que coincida --}}
                                    <th>No. Anexos</th> {{-- Ajustado el nombre para que coincida --}}
                                    <th>Descripción</th> {{-- Ajustado el nombre para que coincida --}}
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Aquí deberías iterar sobre los documentos si los pasas a la vista --}}
                                @forelse ($documentos ?? [] as $documento)
                                    <tr>
                                        <td>{{ $documento->folio_unico }}</td>
                                        <td>{{ $documento->tipo_documento }}</td>
                                        <td>{{ $documento->nuc }}</td>
                                        <td>{{ $documento->fecha_recepcion }}</td>
                                        <td>{{ $documento->quien_presenta }}</td>
                                        <td>{{ $documento->numero_hojas }}</td>
                                        <td>{{ $documento->numero_anexos }}</td>
                                        <td>{{ $documento->descripcion }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-muted">Sin registros aún</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection