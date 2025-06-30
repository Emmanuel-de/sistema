@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Detalles de Digitalización #{{ $digitalizacion->id }}</h4>
                    <a href="{{ route('digitalizacion.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a la Lista
                    </a>
                </div>
                <div class="card-body">
                    {{-- No necesitamos el mensaje de éxito de sesión aquí, ya que es una vista de solo lectura --}}

                    {{-- Sección de Datos --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">DATOS GENERALES</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Tipo:</strong> {{ $digitalizacion->tipo }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Visor:</strong> {{ $digitalizacion->visor ? 'Sí' : 'No' }}</p>
                                </div>
                            </div>

                            <p><strong>NUC:</strong> {{ $digitalizacion->nuc }}</p>
                            <p><strong>Presentado Por:</strong> {{ $digitalizacion->presentado_por }}</p>
                            <p><strong>Fecha de Presentación:</strong> {{ \Carbon\Carbon::parse($digitalizacion->fecha_presentacion)->format('d/m/Y') }}</p>
                            <p><strong>Comentario:</strong> {{ $digitalizacion->comentario ?? 'N/A' }}</p>
                            <p><strong>Estado:</strong> {{ ucfirst($digitalizacion->estado ?? 'N/A') }}</p>
                        </div>
                    </div>

                    {{-- Archivos Digitalizados --}}
                    @if(isset($digitalizacion->archivos) && count($digitalizacion->archivos) > 0)
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">ARCHIVOS DIGITALIZADOS</h6>
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
                                                    {{-- Aquí puedes poner un enlace para ver o descargar el archivo --}}
                                                    {{-- Asumiendo que 'ruta' o 'url' está guardada en $archivo --}}
                                                    @if(isset($archivo['ruta']))
                                                        <a href="{{ route('digitalizacion.downloadFile', ['digitalizacion' => $digitalizacion->id, 'fileIndex' => $index]) }}" class="btn btn-primary">
                                                            <i class="fas fa-eye"></i> Ver / Descargar 
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Ruta no disponible</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">ARCHIVOS DIGITALIZADOS</h6>
                            </div>
                            <div class="card-body">
                                <p>No hay archivos adjuntos para esta digitalización.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Botón para volver --}}
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('digitalizacion.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a la Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection