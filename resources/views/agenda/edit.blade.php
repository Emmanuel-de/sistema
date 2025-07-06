@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-edit me-2"></i>
                        Editar Cita
                    </h4>
                    <a href="{{ route('agenda.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('agenda.update', $cita->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" 
                                   value="{{ $cita->fecha->format('Y-m-d') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                                       value="{{ $cita->hora_inicio ? \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') : '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="hora_fin" class="form-label">Hora Fin</label>
                                <input type="time" class="form-control" id="hora_fin" name="hora_fin" 
                                       value="{{ $cita->hora_fin ? \Carbon\Carbon::parse($cita->hora_fin)->format('H:i') : '' }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" 
                                   value="{{ $cita->titulo }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="cliente" name="cliente" 
                                   value="{{ $cita->cliente }}">
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" 
                                   value="{{ $cita->telefono }}">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ $cita->email }}">
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ $cita->descripcion }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="color" class="form-label">Color</label>
                                <select class="form-control" id="color" name="color">
                                    <option value="#007bff" {{ $cita->color == '#007bff' ? 'selected' : '' }}>Azul</option>
                                    <option value="#28a745" {{ $cita->color == '#28a745' ? 'selected' : '' }}>Verde</option>
                                    <option value="#dc3545" {{ $cita->color == '#dc3545' ? 'selected' : '' }}>Rojo</option>
                                    <option value="#ffc107" {{ $cita->color == '#ffc107' ? 'selected' : '' }}>Amarillo</option>
                                    <option value="#6f42c1" {{ $cita->color == '#6f42c1' ? 'selected' : '' }}>Morado</option>
                                    <option value="#fd7e14" {{ $cita->color == '#fd7e14' ? 'selected' : '' }}>Naranja</option>
                                    <option value="#20c997" {{ $cita->color == '#20c997' ? 'selected' : '' }}>Turquesa</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-control" id="estado" name="estado">
                                    <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="confirmada" {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                    <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    <option value="completada" {{ $cita->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Actualizar Cita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection