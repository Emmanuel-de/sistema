@forelse($documentos ?? [] as $documento)
<tr>
    <td>
        <input type="checkbox" class="form-check-input documento-checkbox" 
               value="{{ $documento->id }}">
    </td>
    <td>
        <span class="fw-bold text-primary">
            {{ $documento->numero ?? 'S/N' }}
        </span>
    </td>
    <td>
        <span class="badge bg-info">
            {{ $documento->tipo ?? $documento->tipo_documento ?? 'Sin tipo' }}
        </span>
    </td>
    <td>
        <div class="text-truncate" style="max-width: 200px;" 
             title="{{ $documento->descripcion ?? 'Sin descripción' }}">
            {{ $documento->descripcion ?? 'Sin descripción' }}
        </div>
    </td>
    <td>
        <i class="fas fa-user me-1"></i>
        {{ $documento->solicitante ?? 'N/A' }}
    </td>
    <td>
        <i class="fas fa-calendar me-1"></i>
        @if($documento->fecha_solicitud)
            {{ is_string($documento->fecha_solicitud) ? date('d/m/Y', strtotime($documento->fecha_solicitud)) : $documento->fecha_solicitud->format('d/m/Y') }}
        @else
            N/A
        @endif
    </td>
    <td>
        <span class="badge bg-warning">
            <i class="fas fa-clock me-1"></i>
            {{ ucfirst($documento->estado) }}
        </span>
    </td>
    <td>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-primary btn-ver" 
                    data-id="{{ $documento->id }}" title="Ver detalles">
                <i class="fas fa-eye"></i>
            </button>
            <button type="button" class="btn btn-outline-success btn-procesar" 
                    data-id="{{ $documento->id }}" title="Procesar">
                <i class="fas fa-check"></i>
            </button>
            <button type="button" class="btn btn-outline-danger btn-rechazar-individual" 
                    data-id="{{ $documento->id }}" title="Rechazar">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-4">
        <div class="text-muted">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <h5>No hay documentos pendientes</h5>
            <p>Todos los documentos han sido procesados.</p>
        </div>
    </td>
</tr>
@endforelse