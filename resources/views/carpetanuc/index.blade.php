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
                    <button class="btn btn-sm btn-outline-warning me-1" title="Carpeta">
                        <i class="fas fa-folder text-warning"></i>
                    </button>
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
                alert('Carpeta encontrada exitosamente');
                // Aquí puedes actualizar la interfaz con los datos encontrados
            } else {
                alert('No se encontró la carpeta');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al buscar la carpeta');
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
});
</script>
@endsection