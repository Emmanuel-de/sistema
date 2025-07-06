@extends('layouts.app')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detalles de la Notificación</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">NUC:</label>
                                <p class="form-control-plaintext">{{ $notificacion->nuc ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Número Telefónico:</label>
                                <p class="form-control-plaintext">{{ $notificacion->numero_telefono }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha Notificación:</label>
                                <p class="form-control-plaintext">
                                    {{ $notificacion->fecha_notificacion ? $notificacion->fecha_notificacion->format('d/m/Y H:i') : 'N/A' }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Usuario que Notifica:</label>
                                <p class="form-control-plaintext">{{ $notificacion->usuarioNotifica->name ?? $notificacion->persona_notifico }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo de Notificación:</label>
                                <p class="form-control-plaintext">{{ ucfirst(str_replace('_', ' ', $notificacion->seleccione_notificacion)) }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha de Alta:</label>
                                <p class="form-control-plaintext">
                                    {{ $notificacion->created_at ? $notificacion->created_at->format('d/m/Y H:i') : 'N/A' }}
                                </p>
                            </div>
                            @if($notificacion->audio_path)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Audio:</label>
                                    <div>
                                        <audio controls class="w-100">
                                            <source src="{{ Storage::url($notificacion->audio_path) }}" type="audio/mpeg">
                                            Tu navegador no soporta el elemento de audio.
                                        </audio>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($notificacion->observaciones)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Observaciones:</label>
                                    <div class="p-3 bg-light border rounded">
                                        {{ $notificacion->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('notificaciones.edit', $notificacion) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> EDITAR
                                </a>
                                <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> VOLVER
                                </a>
                                <form method="POST" action="{{ route('notificaciones.destroy', $notificacion) }}" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta notificación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> ELIMINAR
                                    </button>
                                </form>
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
@endsection