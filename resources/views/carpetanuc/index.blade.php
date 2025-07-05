@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Barra de herramientas superior -->
    <div class="toolbar bg-dark text-white p-2 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="toolbar-section">
                <span class="toolbar-label">BÚSQUEDA</span>
                <div class="toolbar-icons">
                    <button class="btn btn-sm btn-outline-light me-1" title="Buscar">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('videos.create') }}" class="btn btn-sm btn-outline-warning me-1" title="Carpeta">
                       <i class="fas fa-folder text-warning"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-light me-1" title="Configuración">
                        <i class="fas fa-cog"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light me-1" title="Documento">
                        <i class="fas fa-file-alt"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light me-1" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light me-1" title="Gráfico">
                        <i class="fas fa-chart-pie"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light me-1" title="Archivo">
                        <i class="fas fa-file"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light" title="Documento">
                        <i class="fas fa-file-text"></i>
                    </button>
                </div>
            </div>

            <div class="toolbar-section">
                <span class="toolbar-label">GENERAR TRABAJO</span>
                <div class="toolbar-icons">
                    <a href="{{ route('pendientes.create') }}" class="btn btn-sm btn-outline-warning me-1" title="Carpeta">
                       <i class="fas fa-folder text-warning"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-light me-1" title="Impresora">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-warning me-1" title="Carpeta">
                        <i class="fas fa-folder text-warning"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light me-1" title="Candado">
                        <i class="fas fa-lock"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light me-1" title="Árbol">
                        <i class="fas fa-tree"></i>
                    </button>
                </div>
            </div>

            <div class="toolbar-section">
                <span class="toolbar-label">DATOS DE LA CARPETA</span>
            </div>

            <div class="toolbar-section">
                <span class="toolbar-label">DATOS DEL IMPUTADO</span>
                <div class="toolbar-icons">
                    <button class="btn btn-sm btn-outline-light me-1" title="Documento">
                        <i class="fas fa-file-alt"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light me-1" title="Archivo">
                        <i class="fas fa-file"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light" title="Herramienta">
                        <i class="fas fa-wrench"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sección de Búsqueda -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Búsqueda por número de carpeta</h6>
                </div>
                <div class="card-body">
                    <form id="searchForm" action="{{ route('carpetanuc.buscar') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="carpetaNumber" class="form-label">Número de la carpeta</label>
                            <input type="text" class="form-control" id="carpetaNumber" name="carpeta_number" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="separacionProcesos" name="separacion_procesos" value="1">
                                <label class="form-check-label" for="separacionProcesos">
                                    Separación de procesos
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">BUSCAR</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sección de Audiencia de la carpeta -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Audiencia de la carpeta</h6>
                </div>
                <div class="card-body">
                    <div class="folder-tree">
                        <div class="tree-item">
                            <i class="fas fa-folder text-warning me-2"></i>
                            <span class="folder-name">................................................</span>
                        </div>
                        <div class="tree-item ms-3">
                            <button class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn btn-sm btn-danger me-2">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <div class="tree-item ms-3">
                            <button class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn btn-sm btn-danger me-2">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <div class="tree-item ms-3">
                            <button class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn btn-sm btn-danger me-2">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Información de la carpeta -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Información de la carpeta</h6>
                </div>
                <div class="card-body">
                    <div class="folder-info">
                        <div class="info-item d-flex align-items-center mb-2">
                            <i class="fas fa-folder text-warning me-2"></i>
                            <button class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-plus"></i>
                            </button>
                            <span class="info-text">................................................</span>
                        </div>
                        <div class="info-item d-flex align-items-center mb-2">
                            <button class="btn btn-sm btn-outline-secondary me-2 ms-4">
                                <i class="fas fa-plus"></i>
                            </button>
                            <span class="info-text">................................................</span>
                        </div>
                        <div class="info-item d-flex align-items-center mb-2">
                            <button class="btn btn-sm btn-outline-secondary me-2 ms-4">
                                <i class="fas fa-plus"></i>
                            </button>
                            <span class="info-text">................................................</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.toolbar {
    border-radius: 0.375rem;
}

.toolbar-section {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.toolbar-label {
    font-size: 0.75rem;
    font-weight: bold;
    margin-bottom: 0.25rem;
}

.toolbar-icons {
    display: flex;
    gap: 0.25rem;
}

.folder-tree, .folder-info {
    min-height: 200px;
}

.tree-item, .info-item {
    padding: 0.5rem 0;
}

.folder-name, .info-text {
    color: #666;
    font-family: monospace;
}

.card {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-bottom: 1px solid #dee2e6;
    font-weight: 600;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .toolbar-section {
        margin-bottom: 1rem;
    }

    .toolbar-icons {
        flex-wrap: wrap;
    }
}

.nuc-folder {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fa;
}

.folder-header {
    display: flex;
    align-items: center;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.document-section {
    margin-bottom: 1rem;
    border: 1px solid #e3e6f0;
    border-radius: 0.25rem;
    background-color: white;
}

.section-header {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e3e6f0;
    font-weight: 600;
}

.section-content {
    padding: 0.75rem;
}

.document-item {
    margin-bottom: 0.5rem;
}

.document-item p {
    margin-bottom: 0.25rem;
}

.search-summary {
    padding: 1rem;
}

.stat-card {
    text-align: center;
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    background-color: #f8f9fa;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #007bff;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.toggle-section {
    margin-left: auto;
}

.badge {
    font-size: 0.75rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejo del formulario de búsqueda
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const carpetaNumber = document.getElementById('carpetaNumber').value;
        const separacionProcesos = document.getElementById('separacionProcesos').checked;

        if (!carpetaNumber.trim()) {
            alert('Por favor ingrese el número de carpeta');
            return;
        }

        // Realizar la petición AJAX
        fetch('{{ route('carpetanuc.buscar') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                carpeta_number: carpetaNumber,
                separacion_procesos: separacionProcesos
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Resultado:', data);
            if (data.success) {
                displaySearchResults(data.data);
            } else {
                alert('No se encontró información para el NUC: ' + carpetaNumber);
                clearSearchResults();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al buscar la información');
            clearSearchResults();
        });
    });

    // Manejo de botones de la barra de herramientas
    document.querySelectorAll('.toolbar-icons .btn').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const action = icon.className.split(' ').pop();
            console.log('Acción de toolbar:', action);

            switch(action) {
                case 'fa-search':
                    console.log('Ejecutar búsqueda avanzada');
                    break;
                case 'fa-folder':
                    console.log('Crear nueva carpeta');
                    break;
                case 'fa-print':
                    console.log('Imprimir documento');
                    break;
            }
        });
    });

    // Manejo de botones de expandir/colapsar
    document.querySelectorAll('.btn-outline-secondary').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-plus')) {
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
                console.log('Expandir elemento');
            } else {
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
                console.log('Colapsar elemento');
            }
        });
    });

    // Manejo de botones rojos (eliminar)
    document.querySelectorAll('.btn-danger').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Está seguro que desea eliminar este elemento?')) {
                console.log('Eliminar elemento');
            }
        });
    });

    // Función para mostrar los resultados de búsqueda
    function displaySearchResults(data) {
        const audienciaSection = document.querySelector('.col-md-6:nth-child(2) .card-body');
        const infoSection = document.querySelector('.col-12 .card-body');

        // Limpiar contenido anterior
        audienciaSection.innerHTML = '';
        infoSection.innerHTML = '';

        // Crear estructura de carpeta para el NUC
        const folderStructure = `
            <div class="nuc-folder">
                <div class="folder-header">
                    <i class="fas fa-folder text-warning me-2"></i>
                    <strong>NUC: ${data.nuc}</strong>
                    <span class="badge bg-info ms-2">${data.resumen.total_digitalizaciones + data.resumen.total_complementarios} documentos</span>
                </div>
                <div class="folder-content ms-4 mt-2">
                    ${data.recepcion ? createRecepcionSection(data.recepcion) : ''}
                    ${data.digitalizaciones.length > 0 ? createDigitalizacionesSection(data.digitalizaciones) : ''}
                    ${data.complementarios.length > 0 ? createComplementariosSection(data.complementarios) : ''}
                    ${data.carpeta_info ? createCarpetaInfoSection(data.carpeta_info) : ''}
                </div>
            </div>
        `;

        audienciaSection.innerHTML = folderStructure;

        // Mostrar resumen en la sección de información
        const resumenInfo = `
            <div class="search-summary">
                <h6><i class="fas fa-info-circle me-2"></i>Resumen de información encontrada</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-number">${data.resumen.tiene_recepcion ? '1' : '0'}</div>
                            <div class="stat-label">Recepción</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-number">${data.resumen.total_digitalizaciones}</div>
                            <div class="stat-label">Digitalizaciones</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-number">${data.resumen.total_complementarios}</div>
                            <div class="stat-label">Complementarios</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-number">${data.resumen.tiene_carpeta ? '1' : '0'}</div>
                            <div class="stat-label">Carpeta</div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        infoSection.innerHTML = resumenInfo;
    }

    function createRecepcionSection(recepcion) {
        return `
            <div class="document-section">
                <div class="section-header">
                    <i class="fas fa-inbox text-primary me-2"></i>
                    <strong>Recepción</strong>
                    <button class="btn btn-sm btn-outline-secondary ms-2 toggle-section">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="section-content ms-4">
                    <p><strong>Presentado por:</strong> ${recepcion.quien_presenta || 'N/A'}</p>
                    <p><strong>Fecha oficio:</strong> ${recepcion.fecha_oficio || 'N/A'}</p>
                    <p><strong>Tipo audiencia:</strong> ${recepcion.tipo_audiencia || 'N/A'}</p>
                    <p><strong>Número fojas:</strong> ${recepcion.numero_fojas || 'N/A'}</p>
                    <p><strong>Número anexos:</strong> ${recepcion.numero_anexos || 'N/A'}</p>
                </div>
            </div>
        `;
    }

    function createDigitalizacionesSection(digitalizaciones) {
        let html = `
            <div class="document-section">
                <div class="section-header">
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    <strong>Digitalizaciones (${digitalizaciones.length})</strong>
                    <button class="btn btn-sm btn-outline-secondary ms-2 toggle-section">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="section-content ms-4">
        `;

        digitalizaciones.forEach((dig, index) => {
            html += `
                <div class="document-item">
                    <p><strong>Digitalización ${index + 1}:</strong></p>
                    <p class="ms-3"><strong>Tipo:</strong> ${dig.tipo_nombre}</p>
                    <p class="ms-3"><strong>Presentado por:</strong> ${dig.presentado_por || 'N/A'}</p>
                    <p class="ms-3"><strong>Fecha:</strong> ${dig.fecha_presentacion || 'N/A'}</p>
                    <p class="ms-3"><strong>Estado:</strong> <span class="badge bg-${getEstadoBadgeColor(dig.estado)}">${dig.estado_nombre}</span></p>
                    <p class="ms-3"><strong>Total archivos:</strong> ${dig.total_archivos || 0}</p>
                    ${dig.comentario ? `<p class="ms-3"><strong>Comentario:</strong> ${dig.comentario}</p>` : ''}
                </div>
                ${index < digitalizaciones.length - 1 ? '<hr>' : ''}
            `;
        });

        html += `
                </div>
            </div>
        `;

        return html;
    }

    function createComplementariosSection(complementarios) {
        let html = `
            <div class="document-section">
                <div class="section-header">
                    <i class="fas fa-file-alt text-success me-2"></i>
                    <strong>Datos Complementarios (${complementarios.length})</strong>
                    <button class="btn btn-sm btn-outline-secondary ms-2 toggle-section">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="section-content ms-4">
        `;

        complementarios.forEach((comp, index) => {
            html += `
                <div class="document-item">
                    <p><strong>Complementario ${index + 1}:</strong></p>
                    <p class="ms-3"><strong>Tipo documento:</strong> ${comp.tipo_documento || 'N/A'}</p>
                    <p class="ms-3"><strong>Quien presenta:</strong> ${comp.quien_presenta || 'N/A'}</p>
                    <p class="ms-3"><strong>Fecha recepción:</strong> ${comp.fecha_recepcion || 'N/A'}</p>
                    <p class="ms-3"><strong>Número hojas:</strong> ${comp.numero_hojas || 'N/A'}</p>
                    <p class="ms-3"><strong>Estado:</strong> <span class="badge bg-info">${comp.estado || 'N/A'}</span></p>
                    ${comp.descripcion ? `<p class="ms-3"><strong>Descripción:</strong> ${comp.descripcion}</p>` : ''}
                </div>
                ${index < complementarios.length - 1 ? '<hr>' : ''}
            `;
        });

        html += `
                </div>
            </div>
        `;

        return html;
    }

    function createCarpetaInfoSection(carpeta) {
        return `
            <div class="document-section">
                <div class="section-header">
                    <i class="fas fa-folder text-warning me-2"></i>
                    <strong>Información de Carpeta</strong>
                    <button class="btn btn-sm btn-outline-secondary ms-2 toggle-section">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="section-content ms-4">
                    <p><strong>Número:</strong> ${carpeta.numero_carpeta}</p>
                    <p><strong>Tipo:</strong> ${carpeta.tipo_carpeta || 'N/A'}</p>
                    <p><strong>Estado:</strong> <span class="badge bg-${carpeta.estado === 'activa' ? 'success' : 'secondary'}">${carpeta.estado}</span></p>
                    <p><strong>Fiscal asignado:</strong> ${carpeta.fiscal_asignado || 'N/A'}</p>
                    <p><strong>Delito principal:</strong> ${carpeta.delito_principal || 'N/A'}</p>
                    <p><strong>Municipio:</strong> ${carpeta.municipio || 'N/A'}</p>
                    <p><strong>Agencia:</strong> ${carpeta.agencia || 'N/A'}</p>
                </div>
            </div>
        `;
    }

    function getEstadoBadgeColor(estado) {
        switch(estado) {
            case 'completado': return 'success';
            case 'procesando': return 'warning';
            case 'error': return 'danger';
            default: return 'secondary';
        }
    }

    function clearSearchResults() {
        const audienciaSection = document.querySelector('.col-md-6:nth-child(2) .card-body');
        const infoSection = document.querySelector('.col-12 .card-body');

        audienciaSection.innerHTML = `
            <div class="folder-tree">
                <div class="tree-item">
                    <i class="fas fa-folder text-warning me-2"></i>
                    <span class="folder-name">................................................</span>
                </div>
            </div>
        `;

        infoSection.innerHTML = `
            <div class="folder-info">
                <div class="info-item d-flex align-items-center mb-2">
                    <i class="fas fa-folder text-warning me-2"></i>
                    <span class="info-text">................................................</span>
                </div>
            </div>
        `;
    }

    // Event delegation para botones de toggle de secciones
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-section')) {
            const button = e.target.closest('.toggle-section');
            const icon = button.querySelector('i');
            const content = button.closest('.document-section').querySelector('.section-content');

            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            } else {
                content.style.display = 'none';
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
        }
    });
});
</script>
@endsection