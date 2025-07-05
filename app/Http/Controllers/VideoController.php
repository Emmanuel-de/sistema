<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Video;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::activos()->orderBy('created_at', 'desc')->get();
        return view('videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('videos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre_video' => 'required|string|max:255',
            'fecha_subida' => 'required|date',
            'video_file' => 'required|file|mimes:mp4,avi,mov,wmv|max:102400', // 100MB max
        ], [
            'nombre_video.required' => 'El nombre del video es obligatorio.',
            'fecha_subida.required' => 'La fecha de subida es obligatoria.',
            'video_file.required' => 'Debe seleccionar un archivo de video.',
            'video_file.mimes' => 'El archivo debe ser de tipo: mp4, avi, mov, wmv.',
            'video_file.max' => 'El archivo no debe superar los 100MB.',
        ]);

        try {
            // Obtener el archivo
            $videoFile = $request->file('video_file');
            
            // Generar un nombre único para el archivo
            $fileName = Str::uuid() . '.' . $videoFile->getClientOriginalExtension();
            
            // Guardar el archivo en storage/app/public/videos
            $filePath = $videoFile->storeAs('videos', $fileName, 'public');
            
            // Crear el registro en la base de datos
            $video = Video::create([
                'nombre_video' => $request->nombre_video,
                'fecha_subida' => $request->fecha_subida,
                'archivo_path' => $filePath,
                'archivo_original' => $videoFile->getClientOriginalName(),
                'extension' => $videoFile->getClientOriginalExtension(),
                'tamano' => $videoFile->getSize(),
                'mime_type' => $videoFile->getMimeType(),
                'estado' => 'activo',
            ]);
            
            \Log::info('Video subido exitosamente', [
                'id' => $video->id,
                'nombre' => $request->nombre_video,
                'fecha' => $request->fecha_subida,
                'archivo' => $fileName,
                'ruta' => $filePath
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Video subido exitosamente',
                'data' => [
                    'id' => $video->id,
                    'nombre' => $video->nombre_video,
                    'fecha' => $video->fecha_subida->format('Y-m-d'),
                    'archivo' => $fileName,
                    'url' => $video->url
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);
        $video->incrementarVistas();
        return view('videos.show', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('videos.edit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        
        $request->validate([
            'nombre_video' => 'required|string|max:255',
            'fecha_subida' => 'required|date',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo,procesando',
        ]);
        
        $video->update($request->only(['nombre_video', 'fecha_subida', 'descripcion', 'estado']));
        
        return redirect()->route('videos.index')->with('success', 'Video actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete(); // El modelo se encarga de eliminar el archivo físico
        
        return redirect()->route('videos.index')->with('success', 'Video eliminado exitosamente');
    }
}
