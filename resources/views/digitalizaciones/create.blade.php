@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>Digitalizar Archivos</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('digitalizar.store') }}" enctype="multipart/form-data" id="digitalizacion-form">
                        @csrf
                        
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">DATOS</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="tipo" class="form-label">Tipo:</label>
                                        <select class="form-select" id="tipo" name="tipo" required>
                                            <option value="">Seleccionar tipo...</option>
                                            <option value="carpeta_preliminar" {{ old('tipo') == 'carpeta_preliminar' ? 'selected' : '' }}>Carpeta Preliminar</option>
                                            <option value="carpeta_procesal" {{ old('tipo') == 'carpeta_procesal' ? 'selected' : '' }}>Carpeta Procesal</option>
                                            <option value="amparo" {{ old('tipo') == 'amparo' ? 'selected' : '' }}>Amparo</option>
                                            <option value="oficio" {{ old('tipo') == 'oficio' ? 'selected' : '' }}>Oficio</option>
                                            <option value="evidencia" {{ old('tipo') == 'evidencia' ? 'selected' : '' }}>Evidencia</option>
                                        </select>
                                        @error('tipo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="ocr" name="ocr" value="1" {{ old('ocr') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ocr">
                                                <strong>OCR</strong> (Reconocimiento Óptico de Caracteres)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="nuc" class="form-label">NUC:</label>
                                    <input type="text" class="form-control" id="nuc" name="nuc" value="{{ old('nuc') }}" required>
                                    @error('nuc')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="presentado_por" class="form-label">Presentado Por:</label>
                                    <input type="text" class="form-control" id="presentado_por" name="presentado_por" value="{{ old('presentado_por') }}" required>
                                    @error('presentado_por')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="fecha_presentacion" class="form-label">Fecha de Presentación:</label>
                                    <input type="date" class="form-control" id="fecha_presentacion" name="fecha_presentacion" value="{{ old('fecha_presentacion') }}" required>
                                    @error('fecha_presentacion')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="comentario" class="form-label">Comentario:</label>
                                    <textarea class="form-control" id="comentario" name="comentario" rows="3" placeholder="Observaciones adicionales...">{{ old('comentario') }}</textarea>
                                    @error('comentario')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">ARCHIVOS A DIGITALIZAR</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="archivos" class="form-label">Seleccionar Archivos:</label>
                                            <input type="file" class="form-control" id="archivos" name="archivos[]" multiple accept=".pdf,.jpg,.jpeg,.png,.tiff,.bmp">
                                            <div class="form-text">
                                                Formatos permitidos: PDF, JPG, JPEG, PNG, TIFF, BMP. Máximo 10 archivos.
                                            </div>
                                            @error('archivos')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('archivos.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="visor" name="visor" value="1" {{ old('visor') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="visor">
                                                    <strong>Visor</strong> - Mostrar vista previa de archivos
                                                </label>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-info" id="btn-digitalizar" disabled>
                                                <i class="fas fa-scanner"></i> Digitalizar
                                            </button>
                                            <button type="button" class="btn btn-warning" id="btn-borrar">
                                                <i class="fas fa-trash"></i> Borrar Imagen
                                            </button>
                                            <button type="button" class="btn btn-success" id="btn-generar-pdf">
                                                <i class="fas fa-file-pdf"></i> Generar PDFs
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="btn-cambiar-scanner">
                                                <i class="fas fa-exchange-alt"></i> Cambiar Scanner
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="border rounded p-3" style="min-height: 300px; background-color: #f8f9fa;">
                                            <div id="preview-container" class="text-center">
                                                <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Los archivos seleccionados aparecerán aquí</p>
                                            </div>
                                        </div>
                                        
                                        <div id="files-list" class="mt-3" style="display: none;">
                                            <h6>Archivos seleccionados:</h6>
                                            <ul id="selected-files" class="list-group list-group-flush">
                                                </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary me-md-2" id="btn-salir">
                                <i class="fas fa-times"></i> Salir
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Digitalización
                                @if ($errors->any())
    <div class="alert alert-danger mt-3">
        <h5>Por favor, corrige los siguientes errores:</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const archivosInput = document.getElementById('archivos');
    const previewContainer = document.getElementById('preview-container');
    const filesList = document.getElementById('files-list');
    const selectedFilesList = document.getElementById('selected-files');
    const btnDigitalizar = document.getElementById('btn-digitalizar');
    const btnBorrar = document.getElementById('btn-borrar');
    const btnGenerarPdf = document.getElementById('btn-generar-pdf');
    const btnSalir = document.getElementById('btn-salir');
    const digitalizacionForm = document.getElementById('digitalizacion-form'); // Obtener el formulario

    let currentFiles = []; // Array para almacenar los archivos actualmente seleccionados/escaneados

    // Manejar selección de archivos
    archivosInput.addEventListener('change', function(e) {
        const newFiles = Array.from(e.target.files);
        // Concatenar nuevos archivos con los existentes (si los hay)
        currentFiles = currentFiles.concat(newFiles); 
        
        // Limitar a 10 archivos si es necesario (ya validado en backend, pero bueno para UX)
        if (currentFiles.length > 10) {
            alert('Solo se permiten un máximo de 10 archivos.');
            currentFiles = currentFiles.slice(0, 10);
        }

        updateFilesDisplay();
    });
    
    // Función para actualizar la visualización de archivos y el input de archivos
    function updateFilesDisplay() {
        selectedFilesList.innerHTML = ''; // Limpiar la lista actual
        previewContainer.innerHTML = ''; // Limpiar la previsualización

        if (currentFiles.length > 0) {
            filesList.style.display = 'block';
            btnDigitalizar.disabled = false; // Habilitar si hay archivos
            btnBorrar.disabled = false;
            btnGenerarPdf.disabled = false;

            currentFiles.forEach((file, index) => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                
                const fileInfo = document.createElement('div');
                fileInfo.innerHTML = `
                    <i class="fas fa-file-alt me-2"></i>
                    <span>${file.name}</span>
                    <small class="text-muted ms-2">(${formatFileSize(file.size)})</small>
                `;
                
                const removeBtn = document.createElement('button');
                removeBtn.className = 'btn btn-sm btn-outline-danger';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.onclick = () => removerArchivo(index);
                
                li.appendChild(fileInfo);
                li.appendChild(removeBtn);
                selectedFilesList.appendChild(li);
            });
            
            // Mostrar primera imagen como preview si es imagen
            const firstFile = currentFiles[0];
            if (firstFile.type.startsWith('image/')) {
                mostrarPreviewImagen(firstFile);
            } else {
                mostrarPreviewGenerico(firstFile);
            }
        } else {
            limpiarPreview(); // Si no hay archivos, limpiar todo
            btnDigitalizar.disabled = true; // Deshabilitar si no hay archivos
            btnBorrar.disabled = true;
            btnGenerarPdf.disabled = true;
        }

        // Importante: Actualizar el input de archivos con los archivos actuales
        const dataTransfer = new DataTransfer();
        currentFiles.forEach(file => dataTransfer.items.add(file));
        archivosInput.files = dataTransfer.files;
    }
    
    // Función para mostrar preview de imagen
    function mostrarPreviewImagen(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `
                <img src="${e.target.result}" class="img-fluid" style="max-height: 280px;" alt="Preview">
            `;
        };
        reader.readAsDataURL(file);
    }
    
    // Función para mostrar preview genérico
    function mostrarPreviewGenerico(file) {
        const extension = file.name.split('.').pop().toLowerCase();
        let iconClass = 'fa-file';
        
        if (extension === 'pdf') iconClass = 'fa-file-pdf';
        else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'].includes(extension)) iconClass = 'fa-file-image';
        
        previewContainer.innerHTML = `
            <i class="fas ${iconClass} fa-4x text-primary mb-3"></i>
            <p class="mb-0">${file.name}</p>
            <small class="text-muted">${formatFileSize(file.size)}</small>
        `;
    }
    
    // Función para limpiar preview (cuando no hay archivos)
    function limpiarPreview() {
        previewContainer.innerHTML = `
            <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
            <p class="text-muted">Los archivos seleccionados aparecerán aquí</p>
        `;
        filesList.style.display = 'none';
        btnDigitalizar.disabled = true;
        btnBorrar.disabled = true;
        btnGenerarPdf.disabled = true;
        currentFiles = []; // Limpiar el array de archivos
        const dataTransfer = new DataTransfer(); // Limpiar el input de archivos
        archivosInput.files = dataTransfer.files;
    }
    
    // Función para formatear tamaño de archivo
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Función para remover archivo
    function removerArchivo(index) {
        currentFiles.splice(index, 1); // Eliminar el archivo del array
        updateFilesDisplay(); // Actualizar la visualización y el input
    }
    
    // Botón Salir
    btnSalir.addEventListener('click', function() {
        if (confirm('¿Está seguro de que desea salir? Se perderán los cambios no guardados.')) {
            window.history.back();
        }
    });
    
    // Botón Borrar Imagen (todos los archivos)
    btnBorrar.addEventListener('click', function() {
        if (confirm('¿Está seguro de que desea borrar todos los archivos seleccionados?')) {
            limpiarPreview(); // Esto también vacía `currentFiles` y el `archivosInput`
        }
    });
    
    // Simulación de funciones de digitalización
    btnDigitalizar.addEventListener('click', function() {
        alert('Función de digitalización - Aquí se integraría con el scanner. Los archivos escaneados se agregarían a la lista.');
        // Ejemplo de cómo agregar un archivo simulado del scanner:
        // const dummyFile = new File(["dummy content"], "scanned_document.pdf", { type: "application/pdf" });
        // currentFiles.push(dummyFile);
        // updateFilesDisplay();
    });
    
    btnGenerarPdf.addEventListener('click', function() {
        // Recopila todos los datos del formulario
    const formData = new FormData(digitalizacionForm);

    // Los archivos ya están adjuntados al FormData porque el input 'archivos'
    // se actualiza en la función `updateFilesDisplay()`.
    // Si tuvieras que adjuntarlos manualmente (ej. si currentFiles no se refleja en el input file):
    // currentFiles.forEach((file, index) => {
    //     formData.append(`archivos[${index}]`, file);
    // });

    fetch("{{ route('digitalizar.generatePdf') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            // No se necesita el encabezado 'Content-Type' para FormData; el navegador lo establece automáticamente
        }
    })
    .then(response => {
        if (!response.ok) {
            // Maneja errores HTTP, por ejemplo, errores de validación
            return response.json().then(errorData => {
                console.error('Error al generar PDF:', errorData);
                alert('Hubo un error al generar el PDF. Por favor, revise la consola para más detalles.');
                throw new Error('Server error');
            });
        }
        return response.blob(); // Obtén la respuesta como un Blob (datos binarios)
    })
    .then(blob => {
        // Crea una URL para el Blob
        const url = window.URL.createObjectURL(blob);
        // Abre el PDF en una nueva pestaña
        window.open(url, '_blank');
        // Libera la URL cuando ya no sea necesaria
        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        console.error('Error en la solicitud de generación de PDF:', error);
        alert('No se pudo generar el PDF. Revise la consola para más detalles.');
    });
    });
    
    document.getElementById('btn-cambiar-scanner').addEventListener('click', function() {
        alert('Función cambiar scanner - Aquí se mostraría la lista de scanners disponibles.');
    });

    // Inicializar el estado de los botones al cargar la página
    updateFilesDisplay();
});
</script>
@endsection