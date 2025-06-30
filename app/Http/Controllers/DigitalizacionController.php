<?php

namespace App\Http\Controllers;

use App\Models\Digitalizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class DigitalizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Digitalizacion::query();

        // Filtros de búsqueda
        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function($q) use ($search) {
                $q->where('nuc', 'like', "%{$search}%")
                  ->orWhere('presentado_por', 'like', "%{$search}%")
                  ->orWhere('tipo', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->porTipo($request->get('tipo'));
        }

        if ($request->filled('estado')) {
            $query->porEstado($request->get('estado'));
        }

        $digitalizaciones = $query->orderBy('created_at', 'desc')
                                 ->paginate(15)
                                 ->withQueryString();

        return view('digitalizaciones.index', compact('digitalizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('digitalizaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string|in:carpeta_preliminar,carpeta_procesal,amparo,oficio,evidencia',
            'nuc' => 'required|string|max:255',
            'presentado_por' => 'required|string|max:255',
            'fecha_presentacion' => 'required|date',
            'comentario' => 'nullable|string',
            'ocr' => 'boolean',
            'visor' => 'boolean',
            'archivos' => 'required|array|min:1|max:10',
            'archivos.*' => 'file|mimes:pdf,jpg,jpeg,png,tiff,bmp|max:20480' // Max 20MB por archivo
        ], [
            'tipo.required' => 'El tipo de digitalización es obligatorio.',
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'nuc.required' => 'El NUC es obligatorio.',
            'presentado_por.required' => 'El campo "Presentado por" es obligatorio.',
            'fecha_presentacion.required' => 'La fecha de presentación es obligatoria.',
            'fecha_presentacion.date' => 'La fecha de presentación debe ser una fecha válida.',
            'archivos.required' => 'Debe seleccionar al menos un archivo.',
            'archivos.min' => 'Debe seleccionar al menos un archivo.',
            'archivos.max' => 'No puede seleccionar más de 10 archivos.',
            'archivos.*.file' => 'Cada elemento debe ser un archivo válido.',
            'archivos.*.mimes' => 'Los archivos deben ser de tipo: PDF, JPG, JPEG, PNG, TIFF, BMP.',
            'archivos.*.max' => 'Cada archivo no puede ser mayor a 20 MB.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            // Crear directorio específico para esta digitalización
            $carpetaDigitalizacion = 'digitalizaciones/' . date('Y/m/d') . '/' . Str::uuid();
            
            // Procesar archivos
            $archivosInfo = [];
            
            foreach ($request->file('archivos') as $index => $archivo) {
                // Generar nombre único para el archivo
                $nombreOriginal = $archivo->getClientOriginalName();
                $extension = $archivo->getClientOriginalExtension();
                $nombreArchivo = Str::slug(pathinfo($nombreOriginal, PATHINFO_FILENAME)) . '_' . time() . '_' . $index . '.' . $extension;
                
                // Guardar archivo
                $rutaArchivo = $archivo->storeAs($carpetaDigitalizacion, $nombreArchivo, 'public');
                
                // Información del archivo
                $archivosInfo[] = [
                    'nombre_original' => $nombreOriginal,
                    'nombre_archivo' => $nombreArchivo,
                    'ruta' => $rutaArchivo,
                    'ruta_completa' => Storage::url($rutaArchivo),
                    'tipo_mime' => $archivo->getMimeType(),
                    'tamaño' => $archivo->getSize(),
                    'extension' => $extension,
                    'orden' => $index + 1
                ];
            }

            // Crear registro en base de datos
            $digitalizacion = Digitalizacion::create([
                'tipo' => $request->tipo,
                'nuc' => $request->nuc,
                'presentado_por' => $request->presentado_por,
                'fecha_presentacion' => $request->fecha_presentacion,
                'comentario' => $request->comentario,
                'ocr' => $request->has('ocr'),
                'visor' => $request->has('visor'),
                'archivos' => $archivosInfo,
                'total_archivos' => count($archivosInfo),
                'estado' => 'completado'
            ]);

            return redirect()->route('digitalizacion.index')
                           ->with('success', 'Digitalización guardada exitosamente. Se procesaron ' . count($archivosInfo) . ' archivo(s).');

        } catch (\Exception $e) {
            // En caso de error, limpiar archivos subidos
            if (isset($carpetaDigitalizacion)) {
                Storage::disk('public')->deleteDirectory($carpetaDigitalizacion);
            }

            return redirect()->back()
                           ->withErrors(['error' => 'Error al procesar la digitalización: ' . $e->getMessage()])
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Digitalizacion $digitalizacion)
    {
        return view('digitalizaciones.show', compact('digitalizacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Digitalizacion $digitalizacion)
    {
        return view('digitalizaciones.edit', compact('digitalizacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Digitalizacion $digitalizacion)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string|in:carpeta_preliminar,carpeta_procesal,amparo,oficio,evidencia',
            'nuc' => 'required|string|max:255',
            'presentado_por' => 'required|string|max:255',
            'fecha_presentacion' => 'required|date',
            'comentario' => 'nullable|string',
            'ocr' => 'boolean',
            'visor' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $digitalizacion->update([
            'tipo' => $request->tipo,
            'nuc' => $request->nuc,
            'presentado_por' => $request->presentado_por,
            'fecha_presentacion' => $request->fecha_presentacion,
            'comentario' => $request->comentario,
            'ocr' => $request->has('ocr'),
            'visor' => $request->has('visor'),
        ]);

        return redirect()->route('digitalizacion.index')
                       ->with('success', 'Digitalización actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Digitalizacion $digitalizacion)
    {
        try {
            // Eliminar archivos físicos
            if ($digitalizacion->archivos && is_array($digitalizacion->archivos)) {
                foreach ($digitalizacion->archivos as $archivo) {
                    if (isset($archivo['ruta']) && Storage::disk('public')->exists($archivo['ruta'])) {
                        Storage::disk('public')->delete($archivo['ruta']);
                    }
                }

                // Eliminar directorio si está vacío
                if (!empty($digitalizacion->archivos)) {
                    $directorio = dirname($digitalizacion->archivos[0]['ruta'] ?? '');
                    if ($directorio && Storage::disk('public')->exists($directorio)) {
                        $archivosEnDirectorio = Storage::disk('public')->files($directorio);
                        if (empty($archivosEnDirectorio)) {
                            Storage::disk('public')->deleteDirectory($directorio);
                        }
                    }
                }
            }

            // Eliminar registro de base de datos
            $digitalizacion->delete();

            return redirect()->route('digitalizacion.index')
                           ->with('success', 'Digitalización eliminada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Error al eliminar la digitalización: ' . $e->getMessage()]);
        }
    }

    /**
     * Descargar un archivo específico de una digitalización
     */
   public function downloadFile(Digitalizacion $digitalizacion, $fileIndex)

         {

          try {

           if (!isset($digitalizacion->archivos[$fileIndex])) {

            abort(404, 'Archivo no encontrado');

            }



            $archivo = $digitalizacion->archivos[$fileIndex];

            $rutaArchivo = $archivo['ruta'];



            if (!Storage::disk('public')->exists($rutaArchivo)) {

             abort(404, 'El archivo físico no existe');

            }



             $contenido = Storage::disk('public')->get($rutaArchivo);

            $nombreDescarga = $archivo['nombre_original'];



              return Response::make($contenido, 200, [

               'Content-Type' => $archivo['tipo_mime'],

               'Content-Disposition' => 'attachment; filename="' . $nombreDescarga . '"',

                'Content-Length' => strlen($contenido),

           ]);



         } catch (\Exception $e) {

            abort(500, 'Error al descargar el archivo: ' . $e->getMessage());

        }

    }

    /**
     * Ver un archivo específico en el navegador
     */
    public function viewFile(Digitalizacion $digitalizacion, $fileIndex)
    {
        try {
            if (!isset($digitalizacion->archivos[$fileIndex])) {
                abort(404, 'Archivo no encontrado');
            }

            $archivo = $digitalizacion->archivos[$fileIndex];
            $rutaArchivo = $archivo['ruta'];

            if (!Storage::disk('public')->exists($rutaArchivo)) {
                abort(404, 'El archivo físico no existe');
            }

            $contenido = Storage::disk('public')->get($rutaArchivo);

            return Response::make($contenido, 200, [
                'Content-Type' => $archivo['tipo_mime'],
                'Content-Disposition' => 'inline; filename="' . $archivo['nombre_original'] . '"',
            ]);

        } catch (\Exception $e) {
            abort(500, 'Error al mostrar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Descargar todos los archivos de una digitalización como ZIP
     */
    public function downloadAll(Digitalizacion $digitalizacion)
    {
        try {
            $zip = new \ZipArchive();
            $nombreZip = 'digitalizacion_' . $digitalizacion->nuc . '_' . date('Y-m-d_H-i-s') . '.zip';
            $rutaTemporal = storage_path('app/temp/' . $nombreZip);

            // Crear directorio temporal si no existe
            if (!file_exists(dirname($rutaTemporal))) {
                mkdir(dirname($rutaTemporal), 0755, true);
            }

            if ($zip->open($rutaTemporal, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('No se pudo crear el archivo ZIP');
            }

            foreach ($digitalizacion->archivos as $archivo) {
                if (Storage::disk('public')->exists($archivo['ruta'])) {
                    $contenidoArchivo = Storage::disk('public')->get($archivo['ruta']);
                    $zip->addFromString($archivo['nombre_original'], $contenidoArchivo);
                }
            }

            $zip->close();

            return response()->download($rutaTemporal, $nombreZip)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Error al crear el archivo ZIP: ' . $e->getMessage()]);
        }
    }

    /**
     * Cambiar el estado de una digitalización
     */
    public function cambiarEstado(Request $request, Digitalizacion $digitalizacion)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|string|in:pendiente,en_proceso,completado,cancelado'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Estado no válido'], 400);
        }

        $digitalizacion->update(['estado' => $request->estado]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'nuevo_estado' => $request->estado
            ]);
        }

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Obtener estadísticas de digitalizaciones
     */
    public function estadisticas(): JsonResponse
    {
        $estadisticas = [
            'total' => Digitalizacion::count(),
            'por_estado' => Digitalizacion::selectRaw('estado, COUNT(*) as total')
                                        ->groupBy('estado')
                                        ->pluck('total', 'estado'),
            'por_tipo' => Digitalizacion::selectRaw('tipo, COUNT(*) as total')
                                      ->groupBy('tipo')
                                      ->pluck('total', 'tipo'),
            'este_mes' => Digitalizacion::whereMonth('created_at', now()->month)
                                      ->whereYear('created_at', now()->year)
                                      ->count(),
            'total_archivos' => Digitalizacion::sum('total_archivos')
        ];

        return response()->json($estadisticas);
    }

    /**
     * Búsqueda AJAX para autocompletado
     */
    public function buscar(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $tipo = $request->get('tipo', 'nuc'); // nuc, presentado_por

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $resultados = [];

        switch ($tipo) {
            case 'nuc':
                $resultados = Digitalizacion::where('nuc', 'like', "%{$query}%")
                                          ->distinct()
                                          ->pluck('nuc')
                                          ->take(10)
                                          ->toArray();
                break;
            
            case 'presentado_por':
                $resultados = Digitalizacion::where('presentado_por', 'like', "%{$query}%")
                                          ->distinct()
                                          ->pluck('presentado_por')
                                          ->take(10)
                                          ->toArray();
                break;
        }

        return response()->json($resultados);
    }

    /**
     * Duplicar una digitalización (crear copia)
     */
    public function duplicar(Digitalizacion $digitalizacion)
    {
        try {
            $nuevaDigitalizacion = $digitalizacion->replicate();
            $nuevaDigitalizacion->nuc = $digitalizacion->nuc . '_COPIA_' . date('Ymd_His');
            $nuevaDigitalizacion->estado = 'pendiente';
            $nuevaDigitalizacion->created_at = now();
            $nuevaDigitalizacion->updated_at = now();
            
            // Copiar archivos a nueva ubicación
            $nuevaCarpeta = 'digitalizaciones/' . date('Y/m/d') . '/' . Str::uuid();
            $nuevosArchivos = [];
            
            foreach ($digitalizacion->archivos as $index => $archivo) {
                if (Storage::disk('public')->exists($archivo['ruta'])) {
                    $nuevoNombreArchivo = 'copia_' . $archivo['nombre_archivo'];
                    $nuevaRuta = $nuevaCarpeta . '/' . $nuevoNombreArchivo;
                    
                    Storage::disk('public')->copy($archivo['ruta'], $nuevaRuta);
                    
                    $nuevoArchivo = $archivo;
                    $nuevoArchivo['nombre_archivo'] = $nuevoNombreArchivo;
                    $nuevoArchivo['ruta'] = $nuevaRuta;
                    $nuevoArchivo['ruta_completa'] = Storage::url($nuevaRuta);
                    
                    $nuevosArchivos[] = $nuevoArchivo;
                }
            }
            
            $nuevaDigitalizacion->archivos = $nuevosArchivos;
            $nuevaDigitalizacion->save();

            return redirect()->route('digitalizacion.show', $nuevaDigitalizacion)
                           ->with('success', 'Digitalización duplicada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Error al duplicar la digitalización: ' . $e->getMessage()]);
        }
    }

    public function descargarArchivo($id)
    {
    $digitalizacion = Digitalizacion::findOrFail($id);

    if (!$digitalizacion->archivo) {
        return redirect()->back()->with('error', 'No hay archivo para descargar.');
    }

    $rutaArchivo = storage_path('app/public/digitalizaciones/' . $digitalizacion->archivo);

    if (!file_exists($rutaArchivo)) {
        return redirect()->back()->with('error', 'El archivo no existe en el servidor.');
    }

    return response()->download($rutaArchivo);
}
}