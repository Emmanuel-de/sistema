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
                    <button class="btn btn-sm btn-outline-light me-1" title="Carpeta">
                        <i class="fas fa-folder"></i>
                    </button>
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

<!-- Video Upload Modal -->
<div id="videoModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5>SUBIR VIDEO</h5>
            <button type="button" class="close-btn" onclick="closeVideoModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="videoUploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nombre_video">Nombre del Video *</label>
                    <input type="text" id="nombre_video" name="nombre_video" class="form-control" required>
                    <div class="error-message" id="nombre_error"></div>
                </div>

                <div class="form-group">
                    <label for="nuc_video">NUC de la Carpeta</label>
                    <input type="text" id="nuc_video" name="nuc" class="form-control" readonly>
                    <div class="error-message" id="nuc_error"></div>
                </div>

                <div class="form-group">
                    <label for="fecha_subida">Fecha de Subida *</label>
                    <input type="date" id="fecha_subida" name="fecha_subida" class="form-control" required>
                    <div class="error-message" id="fecha_error"></div>
                </div>

                <div class="form-group">
                    <label>Seleccionar Archivo de Video *</label>
                    <div class="file-upload-container">
                        <input type="file" id="video_file" name="video_file" accept="video/*" required style="display: none;">
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('video_file').click()">
                            Seleccionar Archivo
                        </button>
                        <div class="file-info mt-2">
                            Formatos permitidos: MP4, AVI, MOV, WMV<br>
                            Tamaño máximo: 100MB
                        </div>
                        <div id="selectedFile" class="selected-file" style="display: none;"></div>
                    </div>
                    <div class="error-message" id="file_error"></div>
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-secondary" onclick="closeVideoModal()">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="submitBtn">Guardar</button>
                </div>
            </form>
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

/* Video Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1050;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #f5f5dc;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 600px;
    position: relative;
    overflow: hidden;
}

.modal-header {
    background-color: #d4a574;
    color: #333;
    padding: 15px 20px;
    text-align: center;
    font-weight: bold;
    font-size: 16px;
    position: relative;
}

.close-btn {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 3px;
    width: 25px;
    height: 25px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
}

.close-btn:hover {
    background-color: #c82333;
}

.modal-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

.file-upload-container {
    border: 2px dashed #ccc;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background-color: #fafafa;
}

.file-info {
    font-size: 12px;
    color: #666;
}

.selected-file {
    margin-top: 10px;
    padding: 8px;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 4px;
    color: #155724;
    font-size: 14px;
}

.button-group {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

/* Video section styles */
.videos-section {
    min-height: 300px;
}

.videos-header {
    font-size: 1.1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.video-item {
    transition: all 0.3s ease;
}

.video-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.no-videos, .no-search {
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
}

.resumen-stats {
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6;
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

    // Función para mostrar resultados de búsqueda
    function displaySearchResults(data) {
        const audienciaSection = document.querySelector('.col-md-6:nth-child(2) .card-body');
        const infoSection = document.querySelector('.col-12 .card-body');

        // Limpiar contenido anterior
        audienciaSection.innerHTML = '';
        infoSection.innerHTML = '';

        // Crear sección de videos para "Audiencia de la carpeta"
        const videosStructure = `
            <div class="videos-section">
                <div class="videos-header mb-3">
                    <i class="fas fa-video text-primary me-2"></i>
                    <strong>Videos asociados al NUC: ${data.nuc}</strong>
                    <span class="badge bg-primary ms-2">${data.resumen.total_videos || 0} videos</span>
                </div>
                ${data.videos && data.videos.length > 0 ? createVideosSection(data.videos) : `
                    <div class="no-videos text-center p-4">
                        <i class="fas fa-video text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted">No hay videos asociados a este NUC</p>
                        <button class="btn btn-success btn-sm" onclick="openVideoModal('${data.nuc}')">
                            <i class="fas fa-plus me-2"></i>Subir Video
                        </button>
                    </div>
                `}
                ${data.nuc && data.videos && data.videos.length > 0 ? `
                <div class="text-center mt-3">
                    <button class="btn btn-success btn-sm" onclick="openVideoModal('${data.nuc}')">
                        <i class="fas fa-plus me-2"></i>Subir Nuevo Video
                    </button>
                </div>
                ` : ''}
            </div>
        `;

        audienciaSection.innerHTML = videosStructure;

        // Mostrar información detallada en la sección de información
        const detailedInfo = `
            <div class="nuc-folder">
                <div class="folder-header">
                    <i class="fas fa-folder text-warning me-2"></i>
                    <strong>Información detallada - NUC: ${data.nuc}</strong>
                    <span class="badge bg-info ms-2">${data.resumen.total_digitalizaciones + data.resumen.total_complementarios} documentos</span>
                </div>
                <div class="folder-content ms-4 mt-2">
                    ${data.recepcion ? createRecepcionSection(data.recepcion) : ''}
                    ${data.digitalizaciones.length > 0 ? createDigitalizacionesSection(data.digitalizaciones) : ''}
                    ${data.complementarios.length > 0 ? createComplementariosSection(data.complementarios) : ''}
                    ${data.carpeta_info ? createCarpetaInfoSection(data.carpeta_info) : ''}
                </div>
                <div class="resumen-stats mt-3 p-3 bg-light rounded">
                    <h6><i class="fas fa-chart-bar me-2"></i>Resumen estadístico</h6>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="stat-card">
                                <div class="stat-number">${data.resumen.tiene_recepcion ? '1' : '0'}</div>
                                <div class="stat-label">Recepción</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="stat-card">
                                <div class="stat-number">${data.resumen.total_digitalizaciones}</div>
                                <div class="stat-label">Digitalizaciones</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="stat-card">
                                <div class="stat-number">${data.resumen.total_complementarios}</div>
                                <div class="stat-label">Complementarios</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="stat-card">
                                <div class="stat-number">${data.resumen.total_videos || 0}</div>
                                <div class="stat-label">Videos</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="stat-card">
                                <div class="stat-number">${data.resumen.tiene_carpeta ? '1' : '0'}</div>
                                <div class="stat-label">Carpeta</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        infoSection.innerHTML = detailedInfo;
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

    function createVideosSection(videos) {
        let html = `
            <div class="videos-list">
        `;

        videos.forEach((video, index) => {
            html += `
                <div class="video-item p-3 mb-3 border rounded bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-1">
                                <i class="fas fa-play-circle text-primary me-2"></i>
                                ${video.nombre_video}
                            </h6>
                            <p class="mb-1 text-muted small">
                                <i class="fas fa-calendar me-1"></i>
                                Subido: ${video.fecha_subida}
                            </p>
                            <p class="mb-1 text-muted small">
                                <i class="fas fa-file me-1"></i>
                                ${video.archivo_original} (${video.tamano_formateado})
                            </p>
                            ${video.duracion_formateada ? `
                            <p class="mb-1 text-muted small">
                                <i class="fas fa-clock me-1"></i>
                                Duración: ${video.duracion_formateada}
                            </p>
                            ` : ''}
                            <p class="mb-0 text-muted small">
                                <i class="fas fa-eye me-1"></i>
                                ${video.vistas} visualizaciones
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group-vertical" role="group">
                                <a href="/videos/${video.id}" class="btn btn-sm btn-primary mb-1" target="_blank">
                                    <i class="fas fa-play me-1"></i>Reproducir
                                </a>
                                <button class="btn btn-sm btn-outline-info" onclick="showVideoDetails(${video.id})">
                                    <i class="fas fa-info-circle me-1"></i>Detalles
                                </button>
                            </div>
                            <span class="badge bg-${getVideoBadgeColor(video.estado)} d-block mt-2">${video.estado}</span>
                        </div>
                    </div>
                    ${video.descripcion ? `
                    <div class="mt-2">
                        <small class="text-muted">
                            <strong>Descripción:</strong> ${video.descripcion}
                        </small>
                    </div>
                    ` : ''}
                </div>
            `;
        });

        html += `
            </div>
        `;

        return html;
    }

    function getVideoBadgeColor(estado) {
        switch(estado) {
            case 'activo': return 'success';
            case 'procesando': return 'warning';
            case 'inactivo': return 'secondary';
            case 'error': return 'danger';
            default: return 'secondary';
        }
    }

    function showVideoDetails(videoId) {
        // You can implement a modal or redirect to show video details
        console.log('Mostrar detalles del video:', videoId);
        alert('Funcionalidad de detalles del video - ID: ' + videoId);
    }

    function getEstadoBadgeColor(estado) {
        switch(estado) {
            case 'completado': return 'success';
            case 'procesando': return 'warning';
            case 'error': return 'danger';
            default: return 'secondary';
        }
    }

    // Función para limpiar resultados
    function clearSearchResults() {
        const audienciaSection = document.querySelector('.col-md-6:nth-child(2) .card-body');
        const infoSection = document.querySelector('.col-12 .card-body');

        audienciaSection.innerHTML = `
            <div class="no-search text-center p-4">
                <i class="fas fa-video text-muted mb-3" style="font-size: 3rem;"></i>
                <p class="text-muted">Ingrese un NUC para ver los videos asociados</p>
            </div>
        `;

        infoSection.innerHTML = `
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

// Video Modal Functions
function openVideoModal(nuc) {
    document.getElementById('videoModal').style.display = 'flex';
    document.getElementById('nuc_video').value = nuc || '';
    document.getElementById('fecha_subida').valueAsDate = new Date();
}

function closeVideoModal() {
    document.getElementById('videoModal').style.display = 'none';
    document.getElementById('videoUploadForm').reset();
    document.getElementById('selectedFile').style.display = 'none';
    // Clear error messages
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
}

// Video file selection handling
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('video_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const selectedFileDiv = document.getElementById('selectedFile');
        const fileError = document.getElementById('file_error');

        if (file) {
            // Validate file type
            const allowedTypes = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];
            if (!allowedTypes.includes(file.type)) {
                fileError.textContent = 'Tipo de archivo no permitido. Use MP4, AVI, MOV o WMV.';
                selectedFileDiv.style.display = 'none';
                return;
            }

            // Validate size (100MB = 100 * 1024 * 1024 bytes)
            if (file.size > 100 * 1024 * 1024) {
                fileError.textContent = 'El archivo es demasiado grande. Máximo 100MB.';
                selectedFileDiv.style.display = 'none';
                return;
            }

            fileError.textContent = '';
            selectedFileDiv.innerHTML = `
                <strong>Archivo seleccionado:</strong><br>
                ${file.name} (${(file.size / (1024*1024)).toFixed(2)} MB)
            `;
            selectedFileDiv.style.display = 'block';
        } else {
            selectedFileDiv.style.display = 'none';
        }
    });

    // Handle form submission
    document.getElementById('videoUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate fields
        let isValid = true;

        const nombre = document.getElementById('nombre_video').value.trim();
        if (!nombre) {
            document.getElementById('nombre_error').textContent = 'El nombre del video es requerido.';
            isValid = false;
        } else {
            document.getElementById('nombre_error').textContent = '';
        }

        const fecha = document.getElementById('fecha_subida').value;
        if (!fecha) {
            document.getElementById('fecha_error').textContent = 'La fecha de subida es requerida.';
            isValid = false;
        } else {
            document.getElementById('fecha_error').textContent = '';
        }

        const archivo = document.getElementById('video_file').files[0];
        if (!archivo) {
            document.getElementById('file_error').textContent = 'Debe seleccionar un archivo de video.';
            isValid = false;
        } else {
            document.getElementById('file_error').textContent = '';
        }

        if (!isValid) return;

        // Submit form
        const submitBtn = document.getElementById('submitBtn');
        const formData = new FormData(this);

        submitBtn.disabled = true;
        submitBtn.textContent = 'Subiendo...';

        fetch('{{ route('videos.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Video subido exitosamente!');
                closeVideoModal();
            } else {
                alert('Error al subir video: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al subir el video');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Guardar';
        });
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('videoModal').style.display === 'flex') {
            closeVideoModal();
        }
    });
});
</script>
@endsection