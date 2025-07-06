@extends('layouts.app')

@section('title', $video->nombre_video)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-play-circle text-primary me-2"></i>
                        {{ $video->nombre_video }}
                    </h4>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
                <div class="card-body">
                    <!-- Video Player -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="video-container mb-4">
                                @if($video->existeArchivo())
                                    <video 
                                        controls 
                                        class="w-100" 
                                        style="max-height: 500px; background-color: #000;"
                                        preload="metadata"
                                    >
                                        <source src="{{ asset('storage/' . $video->archivo_path) }}" type="{{ $video->mime_type }}">
                                        <p>Su navegador no soporta la reproducción de videos.</p>
                                    </video>
                                @else
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        El archivo de video no está disponible.
                                    </div>
                                @endif
                            </div>

                            @if($video->descripcion)
                                <div class="descripcion">
                                    <h5>Descripción</h5>
                                    <p class="text-muted">{{ $video->descripcion }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Video Information -->
                        <div class="col-md-4">
                            <div class="video-info">
                                <h5>Información del Video</h5>
                                
                                <div class="info-item mb-3">
                                    <strong>NUC:</strong>
                                    <span class="text-muted">{{ $video->nuc ?: 'N/A' }}</span>
                                </div>

                                <div class="info-item mb-3">
                                    <strong>Fecha de Subida:</strong>
                                    <span class="text-muted">{{ $video->fecha_subida->format('d/m/Y') }}</span>
                                </div>

                                <div class="info-item mb-3">
                                    <strong>Archivo Original:</strong>
                                    <span class="text-muted">{{ $video->archivo_original }}</span>
                                </div>

                                <div class="info-item mb-3">
                                    <strong>Tamaño:</strong>
                                    <span class="text-muted">{{ $video->tamano_formateado }}</span>
                                </div>

                                @if($video->duracion_formateada && $video->duracion_formateada !== 'N/A')
                                    <div class="info-item mb-3">
                                        <strong>Duración:</strong>
                                        <span class="text-muted">{{ $video->duracion_formateada }}</span>
                                    </div>
                                @endif

                                <div class="info-item mb-3">
                                    <strong>Visualizaciones:</strong>
                                    <span class="text-muted">{{ $video->vistas }}</span>
                                </div>

                                <div class="info-item mb-3">
                                    <strong>Estado:</strong>
                                    <span class="badge bg-{{ $video->estado == 'activo' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($video->estado) }}
                                    </span>
                                </div>

                                <div class="info-item mb-3">
                                    <strong>Subido:</strong>
                                    <span class="text-muted">{{ $video->created_at->format('d/m/Y H:i') }}</span>
                                </div>

                                <!-- Download Button -->
                                <div class="mt-4">
                                    <a href="{{ asset('storage/' . $video->archivo_path) }}" 
                                       download="{{ $video->archivo_original }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>Descargar
                                    </a>
                                </div>
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
    .video-container {
        position: relative;
        background-color: #000;
        border-radius: 8px;
        overflow: hidden;
    }

    .video-info {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .info-item {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 10px;
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    video {
        border-radius: 8px;
    }
</style>
@endpush