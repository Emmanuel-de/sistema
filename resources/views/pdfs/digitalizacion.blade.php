<!DOCTYPE html>
<html>
<head>
    <title>Digitalización de Archivos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .section {
            margin-bottom: 20px;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 5px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .data-row {
            margin-bottom: 8px;
        }
        .data-row strong {
            display: inline-block;
            width: 150px; /* Ajusta según sea necesario */
        }
        .images-container {
            margin-top: 20px;
            text-align: center;
        }
        .images-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 5px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Poder Judicial Tamaulipas</h1>
        <h2>Digitalización de Archivos</h2>
        <p><strong>Fecha de Generación:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">DATOS DE DIGITALIZACIÓN</div>
        <div class="data-row">
            <strong>Tipo:</strong> {{ $tipo ?? 'N/A' }}
        </div>
        <div class="data-row">
            <strong>NUC:</strong> {{ $nuc ?? 'N/A' }}
        </div>
        <div class="data-row">
            <strong>Presentado Por:</strong> {{ $presentado_por ?? 'N/A' }}
        </div>
        <div class="data-row">
            <strong>Fecha de Presentación:</strong> {{ \Carbon\Carbon::parse($fecha_presentacion)->format('d/m/Y') ?? 'N/A' }}
        </div>
        <div class="data-row">
            <strong>OCR Habilitado:</strong> {{ ($ocr ?? false) ? 'Sí' : 'No' }}
        </div>
        <div class="data-row">
            <strong>Comentario:</strong> {{ $comentario ?? 'Sin comentario' }}
        </div>
    </div>

    @if (!empty($uploaded_images))
        <div class="section">
            <div class="section-title">ARCHIVOS DIGITALIZADOS</div>
            <div class="images-container">
                @foreach($uploaded_images as $imageUrl)
                    {{-- Para Dompdf, a menudo es mejor usar rutas absolutas o codificar imágenes en base64 --}}
                    {{-- `public_path()` resuelve la ruta absoluta al archivo en el disco --}}
                    <img src="{{ public_path(str_replace('/storage', 'storage', $imageUrl)) }}" alt="Imagen Digitalizada">
                    {{-- Ejemplo para base64 (requiere más memoria): --}}
                    {{-- <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path(str_replace('/storage', 'storage', $imageUrl)))) }}" alt="Imagen Digitalizada"> --}}
                @endforeach
            </div>
        </div>
    @else
        <div class="section">
            <div class="section-title">ARCHIVOS DIGITALIZADOS</div>
            <p>No se adjuntaron imágenes a este documento digitalizado.</p>
        </div>
    @endif
</body>
</html>