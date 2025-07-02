<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complementar; // Importa el modelo
use Illuminate\Support\Facades\Log;
use Exception;

class ComplementarController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra una lista de los documentos complementarios.
     */
    public function index()
    {
        try {
            $documentos = Complementar::orderBy('created_at', 'desc')->paginate(15);
            return view('complementar.index', compact('documentos'));
        } catch (Exception $e) {
            Log::error('Error al obtener documentos complementarios: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar los documentos.');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Muestra el formulario para crear un nuevo documento.
     */
    public function create()
    {
      $documentos = \DB::table('datos_complementarios')
        ->select(
            'folio_unico',
            'tipo_documento',
            'nuc',
            'fecha_recepcion',
            'quien_presenta',
            'numero_hojas',
            'numero_anexos',
            'descripcion'
        )
        ->where('estado', 'activo')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('complementar.complementar', compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     * Almacena un nuevo documento en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de datos - ¡Nombres de campos corregidos y valores en minúsculas!
        $validatedData = $request->validate([
            'folio_unico' => 'required|string|max:50|unique:datos_complementarios,folio_unico',
            'tipo_documento' => 'required|string|in:oficio,memorandum,circular,informe', // Valores en minúsculas
            'nuc' => 'nullable|string|max:100',
            'fecha_recepcion' => 'nullable|date',
            'quien_presenta' => 'nullable|string|max:255',
            'numero_hojas' => 'nullable|integer|min:1|max:9999', // Nombre corregido
            'numero_anexos' => 'nullable|integer|min:0|max:999', // Nombre corregido
            'descripcion' => 'nullable|string|max:1000',
            'numero_oficio' => 'nullable|string|max:100',
            'fecha_oficio' => 'nullable|date',
            'tipo_audiencia' => 'nullable|string|in:inicial,intermedia,juicio,sentencia', // Valores en minúsculas
            'numero_amparo' => 'nullable|string|max:100', // Nombre corregido
            'precedencia' => 'nullable|string|max:255', // Nombre corregido
            'entidad' => 'nullable|string|max:255',
            'solicita_informe' => 'nullable|string|in:si,no' // Valores en minúsculas
        ], [
            // Mensajes personalizados
            'folio_unico.required' => 'El folio único es obligatorio.',
            'folio_unico.unique' => 'Este folio único ya existe en el sistema.',
            'folio_unico.max' => 'El folio único no puede exceder 50 caracteres.',
            'tipo_documento.required' => 'Debe seleccionar un tipo de documento.',
            'tipo_documento.in' => 'El tipo de documento seleccionado no es válido.',
            'fecha_recepcion.date' => 'La fecha de recepción debe ser una fecha válida.',
            'fecha_oficio.date' => 'La fecha de oficio debe ser una fecha válida.',
            'numero_hojas.integer' => 'El número de hojas debe ser un número entero.',
            'numero_hojas.min' => 'El número de hojas debe ser al menos 1.',
            'numero_hojas.max' => 'El número de hojas no puede exceder 9999.',
            'numero_anexos.integer' => 'El número de anexos debe ser un número entero.',
            'numero_anexos.min' => 'El número de anexos no puede ser negativo.',
            'numero_anexos.max' => 'El número de anexos no puede exceder 999.',
            'descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
            'tipo_audiencia.in' => 'El tipo de audiencia seleccionado no es válido.',
            'solicita_informe.in' => 'El valor para solicita informe debe ser Sí o No.'
        ]);

        try {
            // Uso de Eloquent ORM para insertar
            $documento = Complementar::create(array_merge($validatedData, [
                'usuario_creacion' => auth()->id() ?? 1, // Si tienes autenticación
                'estado' => 'activo'
            ]));

            // Registrar actividad en log
            Log::info('Documento complementario creado', [
                'id' => $documento->id,
                'folio_unico' => $documento->folio_unico,
                'usuario' => auth()->id() ?? 'sistema'
            ]);

            return redirect()->route('complementar.show', $documento->id)
                ->with('success', 'Documento complementario guardado exitosamente.');

        } catch (Exception $e) {
            Log::error('Error al guardar documento complementario: ' . $e->getMessage(), [
                'datos' => $validatedData,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar el documento. Por favor, inténtelo nuevamente. ' . $e->getMessage()); // Agregado mensaje de la excepción para depuración
        }
    }

    /**
     * Display the specified resource.
     * Muestra un documento específico.
     */
    public function show($id)
    {
        try {
            $documento = Complementar::where('id', $id)->first();

            if (!$documento) {
                return redirect()->route('complementar.index')
                    ->with('error', 'Documento no encontrado.');
            }

            return view('complementar.show', compact('documento'));
        } catch (Exception $e) {
            Log::error('Error al mostrar documento complementario: ' . $e->getMessage());
            return redirect()->route('complementar.index')
                ->with('error', 'Error al cargar el documento.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * Muestra el formulario para editar un documento.
     */
    public function edit($id)
    {
        try {
            $documento = Complementar::where('id', $id)->first();

            if (!$documento) {
                return redirect()->route('complementar.index')
                    ->with('error', 'Documento no encontrado.');
            }

            return view('complementar.edit', compact('documento'));
        } catch (Exception $e) {
            Log::error('Error al cargar formulario de edición: ' . $e->getMessage());
            return redirect()->route('complementar.index')
                ->with('error', 'Error al cargar el formulario de edición.');
        }
    }

    /**
     * Update the specified resource in storage.
     * Actualiza un documento en la base de datos.
     */
    public function update(Request $request, $id)
    {
        // Validación - ¡Nombres de campos corregidos y valores en minúsculas!
        $validatedData = $request->validate([
            'folio_unico' => 'required|string|max:50|unique:datos_complementarios,folio_unico,' . $id,
            'tipo_documento' => 'required|string|in:oficio,memorandum,circular,informe',
            'nuc' => 'nullable|string|max:100',
            'fecha_recepcion' => 'nullable|date',
            'quien_presenta' => 'nullable|string|max:255',
            'numero_hojas' => 'nullable|integer|min:1|max:9999',
            'numero_anexos' => 'nullable|integer|min:0|max:999',
            'descripcion' => 'nullable|string|max:1000',
            'numero_oficio' => 'nullable|string|max:100',
            'fecha_oficio' => 'nullable|date',
            'tipo_audiencia' => 'nullable|string|in:inicial,intermedia,juicio,sentencia',
            'numero_amparo' => 'nullable|string|max:100',
            'precedencia' => 'nullable|string|max:255',
            'entidad' => 'nullable|string|max:255',
            'solicita_informe' => 'nullable|string|in:si,no'
        ], [
            'folio_unico.required' => 'El folio único es obligatorio.',
            'folio_unico.unique' => 'Este folio único ya existe en el sistema.',
            'tipo_documento.required' => 'Debe seleccionar un tipo de documento.',
        ]);

        try {
            $documento = Complementar::where('id', $id)->first();
            if (!$documento) {
                return redirect()->route('complementar.index')
                    ->with('error', 'Documento no encontrado.');
            }

            // Uso de Eloquent ORM para actualizar
            $documento->update(array_merge($validatedData, [
                'usuario_modificacion' => auth()->id() ?? 1
            ]));

            Log::info('Documento complementario actualizado', [
                'id' => $id,
                'folio_unico' => $validatedData['folio_unico'],
                'usuario' => auth()->id() ?? 'sistema'
            ]);

            return redirect()->route('complementar.show', $id)
                ->with('success', 'Documento actualizado exitosamente.');

        } catch (Exception $e) {
            Log::error('Error al actualizar documento complementario: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el documento. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * Marca un documento como inactivo (soft delete).
     */
    public function destroy($id)
    {
        try {
            $documento = Complementar::where('id', $id)->first();
            
            if (!$documento) {
                return redirect()->route('complementar.index')
                    ->with('error', 'Documento no encontrado.');
            }

            // Soft delete (marcar como inactivo en lugar de eliminar)
            $documento->update([
                'estado' => 'inactivo',
                'fecha_eliminacion' => now(),
                'usuario_eliminacion' => auth()->id() ?? 1,
            ]);

            Log::info('Documento complementario eliminado', [
                'id' => $id,
                'folio_unico' => $documento->folio_unico,
                'usuario' => auth()->id() ?? 'sistema'
            ]);

            return redirect()->route('complementar.index')
                ->with('success', 'Documento eliminado exitosamente.');

        } catch (Exception $e) {
            Log::error('Error al eliminar documento complementario: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al eliminar el documento.');
        }
    }

    /**
     * Buscar documentos por diferentes criterios.
     */
    public function search(Request $request)
    {
        try {
            $query = Complementar::where('estado', 'activo');

            if ($request->filled('folio_unico')) {
                $query->where('folio_unico', 'like', '%' . $request->folio_unico . '%');
            }
            if ($request->filled('tipo_documento')) {
                $query->where('tipo_documento', $request->tipo_documento);
            }
            if ($request->filled('nuc')) {
                $query->where('nuc', 'like', '%' . $request->nuc . '%');
            }
            if ($request->filled('fecha_desde')) {
                $query->where('fecha_recepcion', '>=', $request->fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->where('fecha_recepcion', '<=', $request->fecha_hasta);
            }

            $documentos = $query->orderBy('created_at', 'desc')->paginate(15);

            return view('complementar.index', compact('documentos'));

        } catch (Exception $e) {
            Log::error('Error en búsqueda de documentos: ' . $e->getMessage());
            return redirect()->route('complementar.index')
                ->with('error', 'Error al realizar la búsqueda.');
        }
    }

    /**
     * Exportar documentos a Excel/CSV.
     */
    public function export(Request $request)
    {
        try {
            $documentos = Complementar::where('estado', 'activo')->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="documentos_complementarios_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function() use ($documentos) {
                $file = fopen('php://output', 'w');
                
                fputcsv($file, [
                    'ID', 'Folio Único', 'Tipo Documento', 'NUC', 'Fecha Recepción',
                    'Quién Presenta', 'Número Hojas', 'Número Anexos', 'Descripción',
                    'Número Oficio', 'Fecha Oficio', 'Tipo Audiencia', 'Número Amparo',
                    'Precedencia', 'Entidad', 'Solicita Informe', 'Fecha Creación'
                ]);

                foreach ($documentos as $documento) {
                    fputcsv($file, [
                        $documento->id,
                        $documento->folio_unico,
                        $documento->tipo_documento,
                        $documento->nuc,
                        $documento->fecha_recepcion,
                        $documento->quien_presenta,
                        $documento->numero_hojas,
                        $documento->numero_anexos,
                        $documento->descripcion,
                        $documento->numero_oficio,
                        $documento->fecha_oficio,
                        $documento->tipo_audiencia,
                        $documento->numero_amparo,
                        $documento->precedencia,
                        $documento->entidad,
                        $documento->solicita_informe,
                        $documento->created_at
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (Exception $e) {
            Log::error('Error al exportar documentos: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al exportar los documentos.');
        }
    }
}