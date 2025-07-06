<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Notificacion::with('usuarioNotifica');

        // Filtro de búsqueda por NUC
        if ($request->filled('nuc')) {
            $query->where('nuc', 'like', '%' . $request->nuc . '%');
        }

        $notificaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('notificaciones.index', compact('notificaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = User::all();
        return view('notificaciones.create', compact('usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_telefono' => 'required|string|max:20',
            'fecha_notificacion' => 'required|string',
            'usuario_notifica' => 'required|exists:users,id',
            'seleccione_notificacion' => 'required|string',
            'observaciones' => 'nullable|string',
            'nuc_form' => 'nullable|string|max:50',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240', // 10MB max
        ]);

        $data = [
            'numero_telefono' => $request->numero_telefono,
            'telefono' => $request->numero_telefono, // Para compatibilidad
            'fecha_notificacion' => now(), // Convertir el día seleccionado a fecha real si es necesario
            'fecha_alta' => now(),
            'usuario_notifica_id' => $request->usuario_notifica,
            'persona_notifico' => User::find($request->usuario_notifica)->name ?? '',
            'seleccione_notificacion' => $request->seleccione_notificacion,
            'observaciones' => $request->observaciones,
            'observacion' => $request->observaciones, // Para compatibilidad
            'nuc' => $request->nuc_form,
        ];

        // Manejar la subida del archivo de audio
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('audios', 'public');
            $data['audio_path'] = $audioPath;
        }

        Notificacion::create($data);

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notificacion $notificacion)
    {
        return view('notificaciones.show', compact('notificacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notificacion $notificacion)
    {
        $usuarios = User::all();
        return view('notificaciones.edit', compact('notificacion', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notificacion $notificacion)
    {
        $request->validate([
            'numero_telefono' => 'required|string|max:20',
            'fecha_notificacion' => 'required|string',
            'usuario_notifica' => 'required|exists:users,id',
            'seleccione_notificacion' => 'required|string',
            'observaciones' => 'nullable|string',
            'nuc_form' => 'nullable|string|max:50',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        $data = [
            'numero_telefono' => $request->numero_telefono,
            'telefono' => $request->numero_telefono,
            'usuario_notifica_id' => $request->usuario_notifica,
            'persona_notifico' => User::find($request->usuario_notifica)->name ?? '',
            'seleccione_notificacion' => $request->seleccione_notificacion,
            'observaciones' => $request->observaciones,
            'observacion' => $request->observaciones,
            'nuc' => $request->nuc_form,
        ];

        if ($request->hasFile('audio')) {
            // Eliminar el audio anterior si existe
            if ($notificacion->audio_path) {
                Storage::disk('public')->delete($notificacion->audio_path);
            }
            
            $audioPath = $request->file('audio')->store('audios', 'public');
            $data['audio_path'] = $audioPath;
        }

        $notificacion->update($data);

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notificacion $notificacion)
    {
        if ($notificacion->audio_path) {
            Storage::disk('public')->delete($notificacion->audio_path);
        }

        $notificacion->delete();

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación eliminada exitosamente.');
    }
}