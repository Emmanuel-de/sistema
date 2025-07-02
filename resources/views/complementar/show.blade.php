@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <h5 class="fw-bold border-bottom pb-2">Detalles de Datos Complementarios</h5>

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

    {{-- BOTONES SUPERIORES --}}
    <div class="d-flex flex-wrap gap-3 my-3">
        <a href="{{ route('complementar.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle-fill"></i> Volver al Listado
        </a>
        {{-- Asegúrate de que $documento exista antes de intentar acceder a su id --}}
        @if (isset($documento) && $documento->id)
            <a href="{{ route('complementar.edit', $documento->id) }}" class="btn btn-outline-warning">
                <i class="bi bi-pencil-fill"></i> Editar Registro
            </a>
            <form action="{{ route('complementar.destroy', $documento->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar este registro?');" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle-fill"></i> Borrar Registro
                </button>
            </form>
        @endif
        <a href="#" class="btn btn-outline-danger"> {{-- Reemplaza # con la ruta real si existe --}}
            <i class="bi bi-file-earmark-pdf-fill"></i> Generar PDF
        </a>
    </div>

    {{-- Detalles del Registro --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <h6 class="fw-bold border-bottom pb-2 mb-3">Información Completa</h6>

            {{-- Asegúrate de que $documento exista antes de intentar mostrar sus propiedades --}}
            @if (isset($documento))
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Folio Único:</strong></p>
                        <p class="fw-bold">{{ $documento->folio_unico ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Tipo de Documento:</strong></p>
                        <p class="fw-bold">{{ $documento->tipo_documento ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>NUC:</strong></p>
                        <p class="fw-bold">{{ $documento->nuc ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Fecha de Recepción:</strong></p>
                        <p class="fw-bold">{{ $documento->fecha_recepcion ? \Carbon\Carbon::parse($documento->fecha_recepcion)->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Quién Presenta:</strong></p>
                        <p class="fw-bold">{{ $documento->quien_presenta ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Número de Hojas:</strong></p>
                        <p class="fw-bold">{{ $documento->numero_hojas ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Número de Anexos:</strong></p>
                        <p class="fw-bold">{{ $documento->numero_anexos ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-12">
                        <p class="mb-1 text-muted"><strong>Descripción:</strong></p>
                        <p class="fw-bold">{{ $documento->descripcion ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Número de Oficio:</strong></p>
                        <p class="fw-bold">{{ $documento->numero_oficio ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Fecha de Oficio:</strong></p>
                        <p class="fw-bold">{{ $documento->fecha_oficio ? \Carbon\Carbon::parse($documento->fecha_oficio)->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Tipo de Audiencia:</strong></p>
                        <p class="fw-bold">{{ $documento->tipo_audiencia ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Número de Amparo:</strong></p>
                        <p class="fw-bold">{{ $documento->numero_amparo ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Precedencia:</strong></p>
                        <p class="fw-bold">{{ $documento->precedencia ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Entidad:</strong></p>
                        <p class="fw-bold">{{ $documento->entidad ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted"><strong>Solicita Informe:</strong></p>
                        <p class="fw-bold">{{ $documento->solicita_informe == 'si' ? 'Sí' : ($documento->solicita_informe == 'no' ? 'No' : 'N/A') }}</p>
                    </div>
                </div>
            @else
                <p class="text-muted text-center">No se pudieron cargar los detalles del documento.</p>
            @endif
        </div>
    </div>

</div>
@endsection

