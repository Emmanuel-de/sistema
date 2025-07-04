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

        // Buscar carpeta por número exacto o parcial
        $carpeta = Carpetanuc::where('numero_carpeta', $numeroCarpeta)
                            ->orWhere('numero_carpeta', 'like', "%{$numeroCarpeta}%")
                            ->first();

        if ($carpeta) {
            return response()->json([
                'success' => true,
                'data' => [
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
                    'imputados' => $carpeta->imputados_lista,
                    'victimas' => $carpeta->victimas_lista,
                    'audiencias' => $carpeta->audiencias_lista,
                    'documentos' => $carpeta->documentos_lista,
                    'total_folios' => $carpeta->total_folios,
                    'encontrada' => true,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Carpeta no encontrada',
            'data' => [
                'numero_carpeta' => $numeroCarpeta,
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

