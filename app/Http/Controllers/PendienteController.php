<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Documento; // Ajusta según tu modelo
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PendienteController extends Controller
{
   
    /**
     * Mostrar lista de documentos pendientes
     */
    public function index(Request $request): View
    {
        $query = Documento::where('estado', 'pendiente')
                          ->orWhere('estado', 'liberado_auxiliar');

        // Aplicar filtros de búsqueda
        if ($request->filled('buscar')) {
            $termino = $request->input('buscar');
            $query->where(function($q) use ($termino) {
                $q->where('numero', 'LIKE', "%{$termino}%")
                  ->orWhere('descripcion', 'LIKE', "%{$termino}%")
                  ->orWhere('tipo', 'LIKE', "%{$termino}%")
                  ->orWhere('solicitante', 'LIKE', "%{$termino}%");
            });
        }

        // Filtros adicionales
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_solicitud', '>=', $request->input('fecha_desde'));
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_solicitud', '<=', $request->input('fecha_hasta'));
        }

        // Ordenamiento
        $ordenar_por = $request->input('ordenar_por', 'fecha_solicitud');
        $direccion = $request->input('direccion', 'desc');
        $query->orderBy($ordenar_por, $direccion);

        // Paginación
        $documentos = $query->paginate(15)->withQueryString();

        // Estadísticas adicionales
        $estadisticas = [
            'total_pendientes' => Documento::where('estado', 'pendiente')->count(),
            'liberados_auxiliar' => Documento::where('estado', 'liberado_auxiliar')->count(),
            'urgentes' => Documento::where('estado', 'pendiente')
                                  ->where('prioridad', 'urgente')->count(),
        ];

        return view('pendientes.index', compact('documentos', 'estadisticas'));
    }

    /**
     * Buscar documentos pendientes (AJAX)
     */
    public function buscar(Request $request): JsonResponse
    {
        $termino = $request->input('termino');
        
        $documentos = Documento::where('estado', 'pendiente')
                              ->where(function($query) use ($termino) {
                                  $query->where('numero', 'LIKE', "%{$termino}%")
                                        ->orWhere('descripcion', 'LIKE', "%{$termino}%")
                                        ->orWhere('tipo', 'LIKE', "%{$termino}%")
                                        ->orWhere('solicitante', 'LIKE', "%{$termino}%");
                              })
                              ->orderBy('fecha_solicitud', 'desc')
                              ->get();

        return response()->json([
            'success' => true,
            'documentos' => $documentos,
            'total' => $documentos->count()
        ]);
    }

    /**
     * Mostrar detalles de un documento específico
     */
    public function show($id): View
    {
        $documento = Documento::findOrFail($id);
        
        // Verificar que el documento esté en estado pendiente
        if (!in_array($documento->estado, ['pendiente', 'liberado_auxiliar'])) {
            abort(404, 'Documento no encontrado en pendientes');
        }

        return view('pendientes.show', compact('documento'));
    }

    /**
     * Liberar documentos seleccionados
     */
    public function liberar(Request $request): JsonResponse
    {
        $request->validate([
            'documentos' => 'required|array',
            'documentos.*' => 'exists:documentos,id',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $documentos_ids = $request->input('documentos');
            $observaciones = $request->input('observaciones');

            $documentos_actualizados = Documento::whereIn('id', $documentos_ids)
                                               ->where('estado', 'pendiente')
                                               ->update([
                                                   'estado' => 'liberado',
                                                   'liberado_por' => Auth::id(),
                                                   'fecha_liberacion' => now(),
                                                   'observaciones_liberacion' => $observaciones,
                                                   'updated_at' => now()
                                               ]);

            // Log de la acción
            Log::info('Documentos liberados', [
                'usuario' => Auth::id(),
                'documentos' => $documentos_ids,
                'cantidad' => $documentos_actualizados
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Se liberaron {$documentos_actualizados} documento(s) correctamente",
                'documentos_actualizados' => $documentos_actualizados
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al liberar documentos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al liberar los documentos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar documentos seleccionados
     */
    public function rechazar(Request $request): JsonResponse
    {
        $request->validate([
            'documentos' => 'required|array',
            'documentos.*' => 'exists:documentos,id',
            'motivo_rechazo' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $documentos_ids = $request->input('documentos');
            $motivo_rechazo = $request->input('motivo_rechazo');

            $documentos_actualizados = Documento::whereIn('id', $documentos_ids)
                                               ->whereIn('estado', ['pendiente', 'liberado_auxiliar'])
                                               ->update([
                                                   'estado' => 'rechazado',
                                                   'rechazado_por' => Auth::id(),
                                                   'fecha_rechazo' => now(),
                                                   'motivo_rechazo' => $motivo_rechazo,
                                                   'updated_at' => now()
                                               ]);

            // Log de la acción
            Log::info('Documentos rechazados', [
                'usuario' => Auth::id(),
                'documentos' => $documentos_ids,
                'motivo' => $motivo_rechazo,
                'cantidad' => $documentos_actualizados
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Se rechazaron {$documentos_actualizados} documento(s) correctamente",
                'documentos_actualizados' => $documentos_actualizados
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al rechazar documentos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar los documentos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Turnar documentos a otro usuario/departamento
     */
    public function turnar(Request $request): JsonResponse
    {
        $request->validate([
            'documentos' => 'required|array',
            'documentos.*' => 'exists:documentos,id',
            'turnar_a' => 'required|exists:users,id',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $documentos_ids = $request->input('documentos');
            $turnar_a = $request->input('turnar_a');
            $observaciones = $request->input('observaciones');

            $documentos_actualizados = Documento::whereIn('id', $documentos_ids)
                                               ->whereIn('estado', ['pendiente', 'liberado_auxiliar'])
                                               ->update([
                                                   'asignado_a' => $turnar_a,
                                                   'turnado_por' => Auth::id(),
                                                   'fecha_turnado' => now(),
                                                   'observaciones_turnado' => $observaciones,
                                                   'updated_at' => now()
                                               ]);

            // Log de la acción
            Log::info('Documentos turnados', [
                'usuario' => Auth::id(),
                'documentos' => $documentos_ids,
                'turnado_a' => $turnar_a,
                'cantidad' => $documentos_actualizados
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Se turnaron {$documentos_actualizados} documento(s) correctamente",
                'documentos_actualizados' => $documentos_actualizados
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al turnar documentos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al turnar los documentos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar reporte de documentos pendientes
     */
    public function generar(Request $request): JsonResponse
    {
        try {
            $documentos_ids = $request->input('documentos', []);
            
            if (empty($documentos_ids)) {
                // Generar reporte de todos los pendientes
                $documentos = Documento::whereIn('estado', ['pendiente', 'liberado_auxiliar'])
                                      ->orderBy('fecha_solicitud', 'desc')
                                      ->get();
            } else {
                // Generar reporte de documentos seleccionados
                $documentos = Documento::whereIn('id', $documentos_ids)
                                      ->whereIn('estado', ['pendiente', 'liberado_auxiliar'])
                                      ->get();
            }

            // Aquí puedes implementar la lógica para generar PDF, Excel, etc.
            // Por ejemplo, usando DomPDF o PhpSpreadsheet

            return response()->json([
                'success' => true,
                'message' => 'Reporte generado correctamente',
                'total_documentos' => $documentos->count(),
                'url_descarga' => route('pendientes.descargar-reporte', ['tipo' => 'pdf'])
            ]);

        } catch (\Exception $e) {
            Log::error('Error al generar reporte: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de documentos pendientes
     */
    public function estadisticas(): JsonResponse
    {
        try {
            $estadisticas = [
                'total_pendientes' => Documento::where('estado', 'pendiente')->count(),
                'liberados_auxiliar' => Documento::where('estado', 'liberado_auxiliar')->count(),
                'urgentes' => Documento::where('estado', 'pendiente')
                                      ->where('prioridad', 'urgente')->count(),
                'por_tipo' => Documento::whereIn('estado', ['pendiente', 'liberado_auxiliar'])
                                      ->select('tipo', DB::raw('count(*) as total'))
                                      ->groupBy('tipo')
                                      ->get(),
                'por_mes' => Documento::whereIn('estado', ['pendiente', 'liberado_auxiliar'])
                                     ->select(
                                         DB::raw('YEAR(fecha_solicitud) as año'),
                                         DB::raw('MONTH(fecha_solicitud) as mes'),
                                         DB::raw('count(*) as total')
                                     )
                                     ->groupBy('año', 'mes')
                                     ->orderBy('año', 'desc')
                                     ->orderBy('mes', 'desc')
                                     ->limit(12)
                                     ->get()
            ];

            return response()->json([
                'success' => true,
                'estadisticas' => $estadisticas
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las estadísticas'
            ], 500);
        }
    }

    /**
     * Actualizar estado masivo de documentos
     */
    public function actualizarEstadoMasivo(Request $request): JsonResponse
    {
        $request->validate([
            'documentos' => 'required|array',
            'documentos.*' => 'exists:documentos,id',
            'nuevo_estado' => 'required|in:pendiente,liberado,rechazado,procesado',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $documentos_ids = $request->input('documentos');
            $nuevo_estado = $request->input('nuevo_estado');
            $observaciones = $request->input('observaciones');

            $datos_actualizacion = [
                'estado' => $nuevo_estado,
                'updated_at' => now()
            ];

            // Agregar campos específicos según el estado
            switch ($nuevo_estado) {
                case 'liberado':
                    $datos_actualizacion['liberado_por'] = Auth::id();
                    $datos_actualizacion['fecha_liberacion'] = now();
                    if ($observaciones) {
                        $datos_actualizacion['observaciones_liberacion'] = $observaciones;
                    }
                    break;
                
                case 'rechazado':
                    $datos_actualizacion['rechazado_por'] = Auth::id();
                    $datos_actualizacion['fecha_rechazo'] = now();
                    if ($observaciones) {
                        $datos_actualizacion['motivo_rechazo'] = $observaciones;
                    }
                    break;
            }

            $documentos_actualizados = Documento::whereIn('id', $documentos_ids)
                                               ->update($datos_actualizacion);

            // Log de la acción
            Log::info('Estado masivo actualizado', [
                'usuario' => Auth::id(),
                'documentos' => $documentos_ids,
                'nuevo_estado' => $nuevo_estado,
                'cantidad' => $documentos_actualizados
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Se actualizaron {$documentos_actualizados} documento(s) a estado: {$nuevo_estado}",
                'documentos_actualizados' => $documentos_actualizados
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar estado masivo: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los documentos: ' . $e->getMessage()
            ], 500);
        }
    }
}