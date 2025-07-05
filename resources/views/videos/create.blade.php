<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Video</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .modal-container {
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

        .form-group input[type="text"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="date"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .file-upload-container {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            background-color: #fafafa;
            transition: all 0.3s ease;
        }

        .file-upload-container:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        .file-upload-container.dragover {
            border-color: #007bff;
            background-color: #e3f2fd;
        }

        .file-input {
            display: none;
        }

        .file-upload-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }

        .file-upload-btn:hover {
            background-color: #0056b3;
        }

        .file-info {
            margin-top: 15px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 4px;
            font-size: 13px;
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

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #28a745;
            color: white;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .btn-primary:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
            display: none;
        }

        .progress-fill {
            height: 100%;
            background-color: #28a745;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="modal-container">
        <div class="modal-header">
            SUBIR VIDEO
            <button type="button" class="close-btn" onclick="closeModal()">×</button>
        </div>

        <div class="modal-body">
            <form id="videoUploadForm" action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="nombre_video">Nombre del Video *</label>
                    <input type="text" id="nombre_video" name="nombre_video" required>
                    <div class="error-message" id="nombre_error"></div>
                </div>

                <div class="form-group">
                    <label for="nuc">NUC de la Carpeta</label>
                    <input type="text" id="nuc" name="nuc" value="{{ $nuc ?? '' }}" placeholder="Número de carpeta (NUC)">
                    <div class="error-message" id="nuc_error"></div>
                </div>

                <div class="form-group">
                    <label for="fecha_subida">Fecha de Subida *</label>
                    <input type="date" id="fecha_subida" name="fecha_subida" required>
                    <div class="error-message" id="fecha_error"></div>
                </div>

                <div class="form-group">
                    <label>Seleccionar Archivo de Video *</label>
                    <div class="file-upload-container" id="fileUploadContainer">
                        <input type="file" id="video_file" name="video_file" class="file-input" accept="video/*" required>
                        <button type="button" class="file-upload-btn" onclick="document.getElementById('video_file').click()">
                            Seleccionar Archivo
                        </button>
                        <div class="file-info">
                            Formatos permitidos: MP4, AVI, MOV, WMV<br>
                            Tamaño máximo: 100MB
                        </div>
                        <div id="selectedFile" class="selected-file" style="display: none;"></div>
                        <div class="progress-bar" id="progressBar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                    </div>
                    <div class="error-message" id="file_error"></div>
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Configurar fecha actual por defecto
        document.getElementById('fecha_subida').valueAsDate = new Date();

        // Manejar selección de archivo
        document.getElementById('video_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const selectedFileDiv = document.getElementById('selectedFile');
            const fileError = document.getElementById('file_error');

            if (file) {
                // Validar tipo de archivo
                const allowedTypes = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];
                if (!allowedTypes.includes(file.type)) {
                    fileError.textContent = 'Tipo de archivo no permitido. Use MP4, AVI, MOV o WMV.';
                    selectedFileDiv.style.display = 'none';
                    return;
                }

                // Validar tamaño (100MB = 100 * 1024 * 1024 bytes)
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

        // Manejar drag and drop
        const fileUploadContainer = document.getElementById('fileUploadContainer');

        fileUploadContainer.addEventListener('dragover', function(e) {
            e.preventDefault();
            fileUploadContainer.classList.add('dragover');
        });

        fileUploadContainer.addEventListener('dragleave', function(e) {
            e.preventDefault();
            fileUploadContainer.classList.remove('dragover');
        });

        fileUploadContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            fileUploadContainer.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('video_file').files = files;
                document.getElementById('video_file').dispatchEvent(new Event('change'));
            }
        });

        // Manejar envío del formulario
        document.getElementById('videoUploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validar campos
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

            // Simular subida con barra de progreso
            const submitBtn = document.getElementById('submitBtn');
            const progressBar = document.getElementById('progressBar');
            const progressFill = document.getElementById('progressFill');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Subiendo...';
            progressBar.style.display = 'block';

            // Simular progreso
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 20;
                if (progress > 100) progress = 100;

                progressFill.style.width = progress + '%';

                if (progress >= 100) {
                    clearInterval(interval);
                    // Aquí normalmente enviarías el formulario real
                    setTimeout(() => {
                        alert('Video subido exitosamente!');
                        closeModal();
                    }, 500);
                }
            }, 200);
        });

        // Función para cerrar modal
        function closeModal() {
            if (confirm('¿Está seguro de que desea cancelar? Los cambios no guardados se perderán.')) {
                window.history.back();
            }
        }

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>