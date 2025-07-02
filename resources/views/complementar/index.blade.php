@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <h5 class="fw-bold border-bottom pb-2">Listado de Datos Complementarios</h5>

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

    {{-- Botón para crear nuevo registro --}}
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('complementar.create') }}" class="btn btn-success rounded-pill px-4">
            <i class="bi bi-plus-circle-fill me-2"></i> Nuevo Registro
        </a>
    </div>

    {{-- Tabla de Registros --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Folio Único</th>
                            <th>Tipo Documento</th>
                            <th>NUC</th>
                            <th>Fecha Recepción</th>
                            <th>Quién Presenta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Itera sobre los datos complementarios --}}
                        @forelse ($complementos as $complemento)
                            <tr>
                                <td>{{ $complemento->folio_unico }}</td>
                                <td>{{ $complemento->tipo_documento }}</td>
                                <td>{{ $complemento->nuc }}</td>
                                <td>{{ \Carbon\Carbon::parse($complemento->fecha_recepcion)->format('d/m/Y') }}</td>
                                <td>{{ $complemento->quien_presenta }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Botón Ver --}}
                                        <a href="{{ route('complementar.show', $complemento->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Ver Detalles">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        {{-- Botón Editar --}}
                                        <a href="{{ route('complementar.edit', $complemento->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar Registro">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        {{-- Botón Eliminar (con formulario para método DELETE) --}}
                                        <form action="{{ route('complementar.destroy', $complemento->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar este registro?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Eliminar Registro">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted py-4">No hay registros de datos complementarios aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $complementos->links() }}
            </div>
        </div>
    </div>

</div>

{{-- Script para inicializar tooltips de Bootstrap --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection
