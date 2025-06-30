<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecepcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recepciones = DB::table('recepciones')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('dashboard.recepciones.index', compact('recepciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.recepcion');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'nuc' => 'required|string|max:255',
            'tipo_audiencia' => 'required|string|max:255',
            'quien_presenta' => 'required|string|max:255',
            'numero_oficio' => 'required|string|max:100',
            'fecha_oficio' => 'required|date',
            'numero_fojas' => 'required|integer|min:1',
            'accion_penal' => 'nullable|boolean',
            'tiene_anexos' => 'nullable|boolean',
            'numero_anexos' => 'nullable|integer|min:0',
            'descripcion_anexos' => 'nullable|string|max:1000',
        ], [
            'nuc.required' => 'El campo NUC es obligatorio.',
            'nuc.max' => 'El NUC no puede tener más de 255 caracteres.',
            'tipo_audiencia.required' => 'El tipo de audiencia es obligatorio.',
            'tipo_audiencia.max' => 'El tipo de audiencia no puede tener más de 255 caracteres.',
            'quien_presenta.required' => 'El campo "Quién Presenta" es obligatorio.',
            'quien_presenta.max' => 'El campo "Quién Presenta" no puede tener más de 255 caracteres.',
            'numero_oficio.required' => 'El número de oficio es obligatorio.',
            'numero_oficio.max' => 'El número de oficio no puede tener más de 100 caracteres.',
            'fecha_oficio.required' => 'La fecha de oficio es obligatoria.',
            'fecha_oficio.date' => 'La fecha de oficio debe ser una fecha válida.',
            'numero_fojas.required' => 'El número de fojas es obligatorio.',
            'numero_fojas.integer' => 'El número de fojas debe ser un número entero.',
            'numero_fojas.min' => 'El número de fojas debe ser mayor a 0.',
            'numero_anexos.integer' => 'El número de anexos debe ser un número entero.',
            'numero_anexos.min' => 'El número de anexos no puede ser negativo.',
            'descripcion_anexos.max' => 'La descripción de anexos no puede tener más de 1000 caracteres.',
        ]);

        // Validación condicional para anexos
        if ($request->tiene_anexos) {
            $validator->addRules([
                'numero_anexos' => 'required|integer|min:1',
                'descripcion_anexos' => 'required|string|max:1000',
            ]);
            
            $validator->addCustomMessages([
                'numero_anexos.required' => 'El número de anexos es obligatorio cuando se marcan anexos.',
                'numero_anexos.min' => 'Debe especificar al menos 1 anexo.',
                'descripcion_anexos.required' => 'La descripción de anexos es obligatoria cuando se marcan anexos.',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Preparar los datos para insertar
            $data = [
                'nuc' => $request->nuc,
                'tipo_audiencia' => $request->tipo_audiencia,
                'quien_presenta' => $request->quien_presenta,
                'numero_oficio' => $request->numero_oficio,
                'fecha_oficio' => $request->fecha_oficio,
                'numero_fojas' => $request->numero_fojas,
                'accion_penal' => $request->has('accion_penal') ? 1 : 0,
                'tiene_anexos' => $request->has('tiene_anexos') ? 1 : 0,
                'numero_anexos' => $request->tiene_anexos ? $request->numero_anexos : null,
                'descripcion_anexos' => $request->tiene_anexos ? $request->descripcion_anexos : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // Insertar en la base de datos
            $recepcionId = DB::table('recepciones')->insertGetId($data);

            // Redireccionar con mensaje de éxito
            return redirect()->route('recepcion.show', $recepcionId)
                ->with('success', 'Recepción registrada exitosamente.');

        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error al guardar recepción: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Hubo un error al guardar la recepción. Por favor, inténtelo nuevamente.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $recepcion = DB::table('recepciones')->where('id', $id)->first();
        
        if (!$recepcion) {
            return redirect()->route('recepcion.index')
                ->with('error', 'Recepción no encontrada.');
        }
        
        return view('dashboard.recepciones.show', compact('recepcion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $recepcion = DB::table('recepciones')->where('id', $id)->first();
        
        if (!$recepcion) {
            return redirect()->route('recepcion.index')
                ->with('error', 'Recepción no encontrada.');
        }
        
        return view('dashboard.recepciones.edit', compact('recepcion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación similar al método store
        $validator = Validator::make($request->all(), [
            'nuc' => 'required|string|max:255',
            'tipo_audiencia' => 'required|string|max:255',
            'quien_presenta' => 'required|string|max:255',
            'numero_oficio' => 'required|string|max:100',
            'fecha_oficio' => 'required|date',
            'numero_fojas' => 'required|integer|min:1',
            'accion_penal' => 'nullable|boolean',
            'tiene_anexos' => 'nullable|boolean',
            'numero_anexos' => 'nullable|integer|min:0',
            'descripcion_anexos' => 'nullable|string|max:1000',
        ]);

        if ($request->tiene_anexos) {
            $validator->addRules([
                'numero_anexos' => 'required|integer|min:1',
                'descripcion_anexos' => 'required|string|max:1000',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'nuc' => $request->nuc,
                'tipo_audiencia' => $request->tipo_audiencia,
                'quien_presenta' => $request->quien_presenta,
                'numero_oficio' => $request->numero_oficio,
                'fecha_oficio' => $request->fecha_oficio,
                'numero_fojas' => $request->numero_fojas,
                'accion_penal' => $request->has('accion_penal') ? 1 : 0,
                'tiene_anexos' => $request->has('tiene_anexos') ? 1 : 0,
                'numero_anexos' => $request->tiene_anexos ? $request->numero_anexos : null,
                'descripcion_anexos' => $request->tiene_anexos ? $request->descripcion_anexos : null,
                'updated_at' => Carbon::now(),
            ];

            DB::table('recepciones')->where('id', $id)->update($data);

            return redirect()->route('recepcion.show', $id)
                ->with('success', 'Recepción actualizada exitosamente.');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar recepción: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Hubo un error al actualizar la recepción.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = DB::table('recepciones')->where('id', $id)->delete();
            
            if ($deleted) {
                return redirect()->route('recepcion.index')
                    ->with('success', 'Recepción eliminada exitosamente.');
            } else {
                return redirect()->route('recepcion.index')
                    ->with('error', 'Recepción no encontrada.');
            }
        } catch (\Exception $e) {
            \Log::error('Error al eliminar recepción: ' . $e->getMessage());
            
            return redirect()->route('recepcion.index')
                ->with('error', 'Hubo un error al eliminar la recepción.');
        }
    }

    /**
     * Buscar recepciones por NUC
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('recepcion.index');
        }
        
        $recepciones = DB::table('recepciones')
            ->where('nuc', 'like', '%' . $query . '%')
            ->orWhere('quien_presenta', 'like', '%' . $query . '%')
            ->orWhere('numero_oficio', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('dashboard.recepciones.index', compact('recepciones', 'query'));
    }
}