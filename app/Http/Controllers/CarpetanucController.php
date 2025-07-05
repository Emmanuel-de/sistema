<?php

namespace App\Http\Controllers;

use App\Models\Carpetanuc;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CarpetanucController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Obtener carpetas recientes o todas si es necesario
        $carpetas = Carpetanuc::activas()
                              ->orderBy('fecha_ultima_actualizacion', 'desc')
                              ->limit(10)
                              ->get();

        return view('carpetanuc.index', compact('carpetas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('carpetanuc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validación y almacenamiento
        $request->validate([
            'numero_carpeta' => 'required|string|max:255|unique:carpetanucs,numero_carpeta',
            'tipo_carpeta' => 'string|max:255',
            'separacion_procesos' => 'boolean',
            'fiscal_asignado' => 'nullable|string|max:255',
            'secretario_asignado' => 'nullable|string|max:255',
            'delito_principal' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'agencia' => 'nullable|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        // Crear la carpeta
        $carpeta = Carpetanuc::create([
            'numero_carpeta' => $request->numero_carpeta,
            'tipo_carpeta' => $request->tipo_carpeta ?? 'preliminar',
            'separacion_procesos' => $request->separacion_procesos ?? false,
            'fiscal_asignado' => $request->fiscal_asignado,
            'secretario_asignado' => $request->secretario_asignado,
            'delito_principal' => $request->delito_principal,
            'municipio' => $request->municipio,
            'agencia' => $request->agencia,
            'fecha_inicio' => $request->fecha_inicio ?? now(),
            'observaciones' => $request->observaciones,
            'estado' => 'activa',
        ]);

        $carpeta->marcarComoActualizada()->save();

        return redirect()->route('carpetanuc.index')->with('success', 'Carpeta creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Lógica para mostrar una carpeta específica
        return view('carpetanuc.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Lógica para mostrar el formulario de edición
        return view('carpetanuc.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // Validación y actualización
        $request->validate([
            'numero_carpeta' => 'required|string|max:255',
            // Agregar más validaciones según necesites
        ]);

        // Lógica para actualizar la carpeta

        return redirect()->route('carpetanuc.index')->with('success', 'Carpeta actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        // Lógica para eliminar la carpeta

        return redirect()->route('carpetanuc.index')->with('success', 'Carpeta eliminada exitosamente');
    }

    /**
     * Buscar carpeta por número
     */
    public function buscar(Request $request): JsonResponse
    {
        $request->validate([
            'carpeta_number' => 'required|string',
            'separacion_procesos' => 'boolean'
        ]);

        $numeroCarpeta = $request->input('carpeta_number');
        $separacionProcesos = $request->input('separacion_procesos', false);

        // Buscar en todas las tablas relacionadas por NUC
        $recepcion = \App\Models\Recepcion::where('nuc', $numeroCarpeta)->first();
        $digitalizaciones = \App\Models\Digitalizacion::where('nuc', $numeroCarpeta)->get();
        $complementarios = \App\Models\Complementar::where('nuc', $numeroCarpeta)->get();
        $videos = \App\Models\Video::where('nuc', $numeroCarpeta)->activos()->get();

        // También buscar en carpetanucs por número de carpeta
        $carpeta = Carpetanuc::where('numero_carpeta', $numeroCarpeta)
                            ->orWhere('numero_carpeta', 'like', "%{$numeroCarpeta}%")
                            ->first();

        // Verificar si se encontró información en alguna tabla
        $encontrado = $recepcion || $digitalizaciones->count() > 0 || $complementarios->count() > 0 || $carpeta || $videos->count() > 0;

        if ($encontrado) {
            $resultado = [
                'success' => true,
                'data' => [
                    'nuc' => $numeroCarpeta,
                    'separacion_procesos' => $separacionProcesos,
                    'encontrada' => true,
                    'carpeta_info' => null,
                    'recepcion' => null,
                    'digitalizaciones' => [],
                    'complementarios' => [],
                    'videos' => [],
                    'resumen' => [
                        'total_digitalizaciones' => $digitalizaciones->count(),
                        'total_complementarios' => $complementarios->count(),
                        'total_videos' => $videos->count(),
                        'tiene_recepcion' => $recepcion ? true : false,
                        'tiene_carpeta' => $carpeta ? true : false
                    ]
                ]
            ];

            // Agregar información de la carpeta si existe
            if ($carpeta) {
                $resultado['data']['carpeta_info'] = [
                    'id' => $carpeta->id,
                    'numero_carpeta' => $carpeta->numero_carpeta,
                    'tipo_carpeta' => $carpeta->tipo_carpeta,
                    'estado' => $carpeta->estado,
                    'fiscal_asignado' => $carpeta->fiscal_asignado,
                    'delito_principal' => $carpeta->delito_principal,
                    'fecha_inicio' => $carpeta->fecha_inicio,
                    'municipio' => $carpeta->municipio,
                    'agencia' => $carpeta->agencia,
                    'separacion_procesos' => $carpeta->separacion_procesos,
                ];
            }

            // Agregar información de recepción si existe
            if ($recepcion) {
                $resultado['data']['recepcion'] = [
                    'id' => $recepcion->id,
                    'nuc' => $recepcion->nuc,
                    'fecha_oficio' => $recepcion->fecha_oficio,
                    'quien_presenta' => $recepcion->quien_presenta,
                    'numero_fojas' => $recepcion->numero_fojas,
                    'numero_anexos' => $recepcion->numero_anexos,
                    'descripcion_anexos' => $recepcion->descripcion_anexos,
                    'tipo_audiencia' => $recepcion->tipo_audiencia,
                    'numero_oficio' => $recepcion->numero_oficio,
                ];
            }

            // Agregar digitalizaciones
            if ($digitalizaciones->count() > 0) {
                $resultado['data']['digitalizaciones'] = $digitalizaciones->map(function ($dig) {
                    return [
                        'id' => $dig->id,
                        'tipo' => $dig->tipo,
                        'tipo_nombre' => $dig->tipo_nombre,
                        'nuc' => $dig->nuc,
                        'presentado_por' => $dig->presentado_por,
                        'fecha_presentacion' => $dig->fecha_presentacion,
                        'comentario' => $dig->comentario,
                        'estado' => $dig->estado,
                        'estado_nombre' => $dig->estado_nombre,
                        'total_archivos' => $dig->total_archivos,
                        'ocr' => $dig->ocr,
                        'visor' => $dig->visor,
                    ];
                })->toArray();
            }

            // Agregar complementarios
            if ($complementarios->count() > 0) {
                $resultado['data']['complementarios'] = $complementarios->map(function ($comp) {
                    return [
                        'id' => $comp->id,
                        'nuc' => $comp->nuc,
                        'tipo_documento' => $comp->tipo_documento,
                        'fecha_recepcion' => $comp->fecha_recepcion,
                        'quien_presenta' => $comp->quien_presenta,
                        'numero_hojas' => $comp->numero_hojas,
                        'numero_anexos' => $comp->numero_anexos,
                        'descripcion' => $comp->descripcion,
                        'numero_oficio' => $comp->numero_oficio,
                        'fecha_oficio' => $comp->fecha_oficio,
                        'tipo_audiencia' => $comp->tipo_audiencia,
                        'estado' => $comp->estado,
                    ];
                })->toArray();
            }

            // Agregar videos
            if ($videos->count() > 0) {
                $resultado['data']['videos'] = $videos->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'nombre_video' => $video->nombre_video,
                        'nuc' => $video->nuc,
                        'fecha_subida' => $video->fecha_subida,
                        'archivo_path' => $video->archivo_path,
                        'archivo_original' => $video->archivo_original,
                        'extension' => $video->extension,
                        'tamano' => $video->tamano,
                        'tamano_formateado' => $video->tamano_formateado,
                        'duracion_formateada' => $video->duracion_formateada,
                        'estado' => $video->estado,
                        'vistas' => $video->vistas,
                        'url' => $video->url,
                        'descripcion' => $video->descripcion,
                    ];
                })->toArray();
            }

            return response()->json($resultado);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontró información para el NUC proporcionado',
            'data' => [
                'nuc' => $numeroCarpeta,
                'separacion_procesos' => $separacionProcesos,
                'encontrada' => false,
            ]
        ]);
    }

    /**
     * Obtener datos de audiencia
     */
    public function audiencia(string $id): JsonResponse
    {
        // Lógica para obtener datos de audiencia
        return response()->json([
            'success' => true,
            'audiencias' => [
                // Datos de audiencias
            ]
        ]);
    }

    /**
     * Generar trabajo
     */
    public function generarTrabajo(Request $request): JsonResponse
    {
        // Lógica para generar trabajo
        return response()->json([
            'success' => true,
            'mensaje' => 'Trabajo generado exitosamente'
        ]);
    }

    /**
     * Obtener datos del imputado
     */
    public function datosImputado(string $id): JsonResponse
    {
        // Lógica para obtener datos del imputado
        return response()->json([
            'success' => true,
            'imputado' => [
                // Datos del imputado
            ]
        ]);
    }
}