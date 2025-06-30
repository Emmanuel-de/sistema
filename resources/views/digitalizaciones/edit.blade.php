@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Editar Digitalización #{{ $digitalizacion->id }}</h4>
                    <a href="{{ route('digitalizar.show', $digitalizacion->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <h5>Por favor, corrige los siguientes errores:</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('digitalizar.update', $digitalizacion->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Sección de Datos -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">DATOS</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="tipo" class="form-label">Tipo:</label>
                                        <select class="form-select" id="tipo" name="tipo" required>
                                            <option value="">Seleccionar tipo...</option>
                                            <option value="carpeta_preliminar" {{ old('tipo', $digitalizacion->tipo) == 'carpeta_preliminar' ? 'selected' : '' }}>Carpeta Preliminar</option>
                                            <option value="carpeta_procesal" {{ old('tipo', $digitalizacion->tipo) == 'carpeta_procesal' ? 'selected' : '' }}>Carpeta Procesal</option>
                                            <option value="amparo" {{ old('tipo', $digitalizacion->tipo) == 'amparo' ? 'selected' : '' }}>Amparo</option>
                                            <option value="oficio" {{ old('tipo', $digitalizacion->tipo) == 'oficio' ? 'selected' : '' }}>Oficio</option>
                                            <option value="evidencia" {{ old('tipo', $digitalizacion->tipo) == 'evidencia' ? 'selected' : '' }}>Evidencia</option>
                                        </select>
                                        @error('tipo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check mt-4">
                                                    <input class="form-check-input" type="checkbox" id="ocr" name="ocr" value="1" {{ old('ocr', $digitalizacion->ocr) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="ocr">
                                                        <strong>OCR</strong> (Reconocimiento Óptico de Caracteres)
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check mt-4">
                                                    <input class="form-check-input" type="checkbox" id="visor" name="visor" value="1" {{ old('visor', $digitalizacion->visor) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="visor">
                                                        <strong>Visor</strong> - Mostrar vista previa de archivos
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="nuc" class="form-label">NUC:</label>
                                    <input type="text" class="form-control" id="nuc" name="nuc" 
                                           value="{{ old('nuc', $digitalizacion->nuc) }}" required>
                                    @error('nuc')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="presentado_por" class="form-label">Presentado Por:</label>
                                    <input type="text" class="form-control" id="presentado_por" name="presentado_por" 
                                           value="{{ old('presentado_por', $digitalizacion->presentado_por) }}" required>
                                    @error('presentado_por')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="fecha_presentacion" class="form-label">Fecha de Presentación:</label>
                                    <input type="date" class="form-control" id="fecha_presentacion" name="fecha_presentacion" 
                                           value="{{ old('fecha_presentacion', $digitalizacion->fecha_presentacion ? $digitalizacion->fecha_presentacion->format('Y-m-d') : '') }}" required>
                                    @error('fecha_presentacion')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="comentario" class="form-label">Comentario:</label>
                                    <textarea class="form-control" id="comentario" name="comentario" rows="3" 
                                              placeholder="Observaciones adicionales...">{{ old('comentario', $digitalizacion->comentario) }}</textarea>
                                    @error('comentario')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado:</label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="pendiente" {{ old('estado', $digitalizacion->estado ?? 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="procesando" {{ old('estado', $digitalizacion->estado) == 'procesando' ? 'selected' : '' }}>Procesando</option>
                                        <option value="completado" {{ old('estado', $digitalizacion->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                                        <option value="error" {{ old('estado', $digitalizacion->estado) == 'error' ? 'selected' : '' }}>Error</option>
                                    </select>
                                    @error('estado')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Archivos Actuales -->
                        @if(isset($digitalizacion->archivos) && count($digitalizacion->archivos) > 0)
            
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">ARCHIVOS ACTUALES</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($digitalizacion->archivos as $index => $archivo)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body text-center">
                                                        @php
                                                            $extension = pathinfo($archivo['nombre'] ?? 'archivo', PATHINFO_EXTENSION);
                                                            $iconClass = 'fa-file';
                                                            if(in_array(strtolower($extension), ['pdf'])) $iconClass = 'fa-file-pdf';
                                                            elseif(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'])) $iconClass = 'fa-file-image';
                                                        @endphp

                                                        <i class="fas {{ $iconClass }} fa-2x text-primary mb-2"></i>
                                                        <h6 class="card-title small">{{ $archivo['nombre'] ?? 'Archivo ' . ($index + 1) }}</h6>
                                                        <p class="card-text text-muted small">
                                                            {{ isset($archivo['tamaño']) ? number_format($archivo['tamaño'] / 1024, 2) . ' KB' : 'N/A' }}
                                                        </p>
                                                        <div class="btn-group-vertical w-100">
                                                            @if(isset($archivo['ruta']) && Storage::disk('public')->exists($archivo['ruta']))
                                                                <a href="{{ route('digitalizacion.downloadFile', ['digitalizacion' => $digitalizacion->id, 'fileIndex' => $index]) }}" class="btn btn-primary">
                                                                    <i class="fas fa-download"></i> Descargar
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Nuevos Archivos -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">NUEVOS ARCHIVOS (Opcional)</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="archivos" class="form-label">Seleccionar Nuevos Archivos:</label>
                                    <input type="file" class="form-control" id="archivos" name="archivos[]" multiple accept=".pdf,.jpg,.jpeg,.png,.tiff,.bmp">
                                    <div class="form-text">
                                        Formatos permitidos: PDF, JPG, JPEG, PNG, TIFF, BMP. Máximo 10 archivos.
                                        <br><strong>Nota:</strong> Si selecciona nuevos archivos, reemplazarán completamente los archivos actuales.
                                    </div>
                                    @error('archivos')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    @error('archivos.*')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('digitalizacion.index', $digitalizacion->id) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Digitalización
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection