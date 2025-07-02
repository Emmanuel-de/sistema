<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complementar; // Importa el modelo Complementar
use App\Models\Recepcion;    // ¡IMPORTANTE! Importa el modelo Recepcion para acceder a la tabla 'recepciones'
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator; // Necesario si usas Validator::make

class ComplementarController extends Controller
{
    /**
     * Muestra una lista de los datos complementarios.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            // Recupera todos los registros de Complementar, ordenados y paginados
            // Se asume que 'estado' es una columna y quieres solo los 'activos'
            $complementos = Complementar::where('estado', 'activo')->orderBy('created_at', 'desc')->paginate(15);
            return view('complementar.index', compact('complementos'));
        } catch (Exception $e) {
            Log::error('Error al obtener documentos complementarios en index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar los documentos. Por favor, inténtelo de nuevo más tarde.');
        }
    }

    /**
     * Muestra el formulario para crear un nuevo registro de datos complementarios.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Este método ahora solo carga la vista del formulario vacío,
        // asumiendo que 'complementar.create' es el nombre de tu vista para crear.
        // Si tu vista se llama 'complementar.complementar', ajusta el nombre aquí.
        return view('complementar.complementar');
    }

    /**
     * Almacena un nuevo registro de datos complementarios en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Reglas de validación
        $rules = [
            'folio_unico' => 'required|string|max:50|unique:datos_complementarios,folio_unico', // Asumiendo que la tabla es 'complementars'
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
            'entidad' => 'nullable|string|max:255', // Considera usar 'in' si son valores fijos
            'solicita_informe' => 'nullable|string|in:si,no',
        ];

        // Mensajes personalizados para la validación
        $messages = [
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
            'solicita_informe.in' => 'El valor para solicita informe debe ser Sí o No.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            // Crear el registro en la base de datos
            $documento = Complementar::create(array_merge($validator->validated(), [
                'usuario_creacion' => auth()->id() ?? 1, // Asigna el ID del usuario autenticado o 1 si no hay
                'estado' => 'activo' // Establece el estado inicial como 'activo'
            ]));

            // Registrar la actividad en el log
            Log::info('Documento complementario creado exitosamente.', [
                'id' => $documento->id,
                'folio_unico' => $documento->folio_unico,
                'usuario' => auth()->id() ?? 'sistema'
            ]);

            return redirect()->route('complementar.show', $documento->id)
                ->with('success', 'Documento complementario guardado exitosamente.');

        } catch (Exception $e) {
            // Registrar el error detallado en el log
            Log::error('Error al guardar documento complementario: ' . $e->getMessage(), [
                'datos' => $request->all(), // Registra todos los datos del request
                'trace' => $e->getTraceAsString() // Registra el stack trace completo
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar el documento. Por favor, inténtelo nuevamente. Detalles: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un registro específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            // Busca el documento por ID y asegura que esté activo
            $documento = Complementar::where('id', $id)->where('estado', 'activo')->first();

            if (!$documento) {
                return redirect()->route('complementar.index')
                    ->with('error', 'Documento no encontrado o no está activo.');
            }

            return view('complementar.show', compact('documento'));
        } catch (Exception $e) {
            Log::error('Error al mostrar documento complementario (ID: ' . $id . '): ' . $e->getMessage());
            return redirect()->route('complementar.index')
                ->with('error', 'Error al cargar el documento.');
        }
    }

    /**
     * Muestra el formulario para editar un registro existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            // Busca el documento por ID y asegura que esté activo
            $documento = Complementar::where('id', $id)->where('estado', 'activo')->first();

            if (!$documento) {
                return redirect()->route('complementar.index')
                    ->with('error', 'Documento no encontrado o no está activo.');
            }

            return view('complementar.edit', compact('documento'));
        } catch (Exception $e) {
            Log::error('Error al cargar formulario de edición (ID: ' . $id . '): ' . $e->getMessage());
            return redirect()->route('complementar.index')
                ->with('error', 'Error al cargar el formulario de edición.');
        }
    }

    /**
     * Actualiza un registro existente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Reglas de validación para la actualización (ignora el folio_unico del registro actual)
        $rules = [
            'folio_unico' => 'required|string|max:50|unique:datos_complementarios,folio_unico,' . $id, // Asumiendo que la tabla es 'complementars'
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
            'solicita_informe' => 'nullable|string|in:si,no',
        ];

        // Mensajes personalizados para la validación
        $messages = [
            'folio_unico.required' => 'El folio único es obligatorio.',
            'folio_unico.unique' => 'Este folio único ya existe en el sistema.',
            'folio_unico.max' => 'El folio único no puede exceder 50 caracteres.',
            'tipo_documento.required' => 'Debe seleccionar un tipo de documento.',
            'tipo_documento.in' => 'El tipo de documento seleccionado no es válido.',
            // ... (otros mensajes de validación si los necesitas)
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            // Busca el documento por ID y asegura que esté activo
            $documento = Complementar::where('id', $id)->where('estado', 'activo')->first();
            if (!$documento) {
                return redirect()->route('complementar.index')
                    ->with('error', 'Documento no encontrado o no está activo.');
            }

            // Actualizar el registro
            $documento->update(array_merge($validator->validated(), [
                'usuario_modificacion' => auth()->id() ?? 1 // Asigna el ID del usuario autenticado o 1
            ]));

            // Registrar la actividad en el log
            Log::info('Documento complementario actualizado exitosamente.', [
                'id' => $id,
                'folio_unico' => $documento->folio_unico,
                'usuario' => auth()->id() ?? 'sistema'
            ]);

            return redirect()->route('complementar.show', $id)
                ->with('success', 'Documento actualizado exitosamente.');

        } catch (Exception $e) {
            // Registrar el error detallado en el log
            Log::error('Error al actualizar documento complementario (ID: ' . $id . '): ' . $e->getMessage(), [
                'datos' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el documento. Por favor, inténtelo nuevamente. Detalles: ' . $e->getMessage());
        }
    }

    /**
     * Marca un documento como inactivo (soft delete).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Busca el documento por ID y asegura que esté activo
            $documento = Complementar::where('id', $id)->where('estado', 'activo')->first();

            if (!$documento) {
                return redirect()->route('complementar.index')
                    ->with('error', 'Documento no encontrado o ya está inactivo.');
            }

            // Realiza el soft delete actualizando el campo 'estado'
            $documento->update([
                'estado' => 'inactivo',
                'fecha_eliminacion' => now(), // Registra la fecha de "eliminación"
                'usuario_eliminacion' => auth()->id() ?? 1, // Registra el usuario que "eliminó"
            ]);

            // Registrar la actividad en el log
            Log::info('Documento complementario marcado como inactivo (soft delete) exitosamente.', [
                'id' => $id,
                'folio_unico' => $documento->folio_unico,
                'usuario' => auth()->id() ?? 'sistema'
            ]);

            return redirect()->route('complementar.index')
                ->with('success', 'Documento eliminado (marcado como inactivo) exitosamente.');

        } catch (Exception $e) {
            // Registrar el error detallado en el log
            Log::error('Error al marcar documento complementario como inactivo (ID: ' . $id . '): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Error al eliminar el documento. Por favor, inténtelo nuevamente. Detalles: ' . $e->getMessage());
        }
    }

    /**
     * Busca documentos por diferentes criterios.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        try {
            // Inicia la consulta solo con documentos activos
            $query = Complementar::where('estado', 'activo');

            // Aplica filtros si los campos están llenos en la solicitud
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

            // Ordena y pagina los resultados
            $complementos = $query->orderBy('created_at', 'desc')->paginate(15);

            // Pasa los resultados a la vista index
            return view('complementar.index', compact('complementos'));

        } catch (Exception $e) {
            Log::error('Error en búsqueda de documentos: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('complementar.index')
                ->with('error', 'Error al realizar la búsqueda. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Exporta documentos a Excel/CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function export(Request $request)
    {
        try {
            // Obtiene todos los documentos activos para exportar
            $documentos = Complementar::where('estado', 'activo')->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="documentos_complementarios_' . date('Y-m-d') . '.csv"',
            ];

            // Define el callback para generar el CSV
            $callback = function() use ($documentos) {
                $file = fopen('php://output', 'w');
                // Añade el encabezado del CSV
                fputcsv($file, [
                    'ID', 'Folio Único', 'Tipo Documento', 'NUC', 'Fecha Recepción',
                    'Quién Presenta', 'Número Hojas', 'Número Anexos', 'Descripción',
                    'Número Oficio', 'Fecha Oficio', 'Tipo Audiencia', 'Número Amparo',
                    'Precedencia', 'Entidad', 'Solicita Informe', 'Fecha Creación',
                    'Fecha Modificación', 'Usuario Creación', 'Usuario Modificación',
                    'Estado', 'Fecha Eliminación', 'Usuario Eliminación'
                ]);

                // Itera sobre los documentos y añade cada fila al CSV
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
                        $documento->created_at,
                        $documento->updated_at,
                        $documento->usuario_creacion,
                        $documento->usuario_modificacion,
                        $documento->estado,
                        $documento->fecha_eliminacion,
                        $documento->usuario_eliminacion
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (Exception $e) {
            Log::error('Error al exportar documentos: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error al exportar los documentos. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Busca datos de recepción por NUC para autocompletar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecepcionData(Request $request)
    {
        $nuc = $request->input('nuc');
        if (!$nuc) {
            return response()->json(['error' => 'NUC no proporcionado'], 400);
        }

        // Buscar en la tabla 'recepciones' por el NUC
        // ¡IMPORTANTE! Asegúrate de que el modelo 'Recepcion' esté correctamente configurado
        // para tu tabla 'recepciones' y que las columnas existan.
        $recepcion = Recepcion::where('nuc', $nuc)->first();

        if ($recepcion) {
            // Devuelve solo los campos que quieres autocompletar
            // Los nombres de las claves aquí deben coincidir con los IDs/Nombres de los campos en tu formulario
            // 'complementar.blade.php'.
            // Los valores $recepcion->XXXXX deben coincidir con los nombres de las columnas en tu tabla 'recepciones'.
            return response()->json([
                'fecha_oficio' => $recepcion->fecha_oficio,
                'quien_presenta' => $recepcion->quien_presenta,
                'numero_hojas' => $recepcion->numero_fojas, // Mapea 'numero_fojas' de la BD a 'numero_hojas' del formulario
                'numero_anexos' => $recepcion->numero_anexos,
                'descripcion' => $recepcion->descripcion_anexos, // ¡CORREGIDO! Mapea 'descripcion_anexos' de la BD a 'descripcion' del formulario
                'tipo_audiencia' => $recepcion->tipo_audiencia,
                'numero_oficio' => $recepcion->numero_oficio,
                // Si necesitas autocompletar más campos de 'recepciones' en el formulario 'complementar',
                // asegúrate de que existan en la tabla 'recepciones' y añádelos aquí.
            ]);
        } else {
            // Si no se encuentra la recepción, devuelve un mensaje 404
            return response()->json(['message' => 'No se encontraron datos para el NUC proporcionado.'], 404);
        }
    }
}
