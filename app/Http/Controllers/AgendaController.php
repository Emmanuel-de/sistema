<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index(Request $request): View
    {
        $mes = $request->input('mes', now()->format('Y-m'));
        $fechaActual = Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
        
        // Obtener todas las citas del mes
        $citasDelMes = Agenda::delMes($fechaActual->year, $fechaActual->month)
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy(function($cita) {
                return $cita->fecha->format('Y-m-d');
            });

        // Generar calendario
        $semanas = $this->generarCalendario($fechaActual, $citasDelMes);

        return view('agenda.index', compact('fechaActual', 'semanas', 'citasDelMes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'titulo' => 'required|string|max:255',
            'cliente' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'descripcion' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada'
        ]);

        $cita = Agenda::create([
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'titulo' => $request->titulo,
            'cliente' => $request->cliente,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'descripcion' => $request->descripcion,
            'color' => $request->color ?? '#007bff',
            'estado' => $request->estado,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('agenda.index')->with('success', 'Cita creada exitosamente');
    }

    public function show($id): JsonResponse
    {
        $cita = Agenda::findOrFail($id);
        
        return response()->json([
            'id' => $cita->id,
            'fecha' => $cita->fecha->format('d/m/Y'),
            'hora_inicio' => $cita->hora_inicio ? Carbon::parse($cita->hora_inicio)->format('H:i') : '',
            'hora_fin' => $cita->hora_fin ? Carbon::parse($cita->hora_fin)->format('H:i') : '',
            'titulo' => $cita->titulo,
            'cliente' => $cita->cliente,
            'telefono' => $cita->telefono,
            'email' => $cita->email,
            'descripcion' => $cita->descripcion,
            'color' => $cita->color,
            'estado' => $cita->estado
        ]);
    }

    public function edit($id): View
    {
        $cita = Agenda::findOrFail($id);
        return view('agenda.edit', compact('cita'));
    }

    public function update(Request $request, $id)
    {
        $cita = Agenda::findOrFail($id);
        
        $request->validate([
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'titulo' => 'required|string|max:255',
            'cliente' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'descripcion' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada'
        ]);

        $cita->update($request->all());

        return redirect()->route('agenda.index')->with('success', 'Cita actualizada exitosamente');
    }

    public function destroy($id): JsonResponse
    {
        $cita = Agenda::findOrFail($id);
        $cita->delete();

        return response()->json(['success' => true, 'message' => 'Cita eliminada exitosamente']);
    }

    private function generarCalendario($fechaActual, $citasDelMes)
    {
        $primerDia = $fechaActual->copy()->startOfMonth();
        $ultimoDia = $fechaActual->copy()->endOfMonth();
        
        // Encontrar el primer domingo de la vista del calendario
        $inicioCalendario = $primerDia->copy()->startOfWeek(Carbon::SUNDAY);
        
        // Encontrar el último sábado de la vista del calendario
        $finCalendario = $ultimoDia->copy()->endOfWeek(Carbon::SATURDAY);
        
        $semanas = [];
        $fechaActualIteracion = $inicioCalendario->copy();
        
        while ($fechaActualIteracion <= $finCalendario) {
            $semana = [];
            
            for ($i = 0; $i < 7; $i++) {
                $fechaString = $fechaActualIteracion->format('Y-m-d');
                $citasDelDia = $citasDelMes->get($fechaString, collect());
                
                $semana[] = [
                    'numero' => $fechaActualIteracion->day,
                    'fecha' => $fechaString,
                    'esMesActual' => $fechaActualIteracion->month === $fechaActual->month,
                    'esHoy' => $fechaActualIteracion->isToday(),
                    'citas' => $citasDelDia
                ];
                
                $fechaActualIteracion->addDay();
            }
            
            $semanas[] = $semana;
        }
        
        return $semanas;
    }
}