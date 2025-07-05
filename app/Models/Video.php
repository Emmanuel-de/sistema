<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_video',
        'fecha_subida',
        'archivo_path',
        'archivo_original',
        'extension',
        'tamano',
        'mime_type',
        'estado',
        'descripcion',
        'duracion',
        'thumbnail_path',
        'vistas',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_subida' => 'date',
        'metadata' => 'array',
        'vistas' => 'integer',
        'duracion' => 'integer',
        'tamano' => 'integer',
    ];

    /**
     * Boot method para eventos del modelo
     */
    protected static function boot()
    {
        parent::boot();
        
        // Eliminar archivo físico cuando se elimine el registro
        static::deleting(function ($video) {
            if ($video->archivo_path && Storage::disk('public')->exists($video->archivo_path)) {
                Storage::disk('public')->delete($video->archivo_path);
            }
            
            // Eliminar thumbnail si existe
            if ($video->thumbnail_path && Storage::disk('public')->exists($video->thumbnail_path)) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }
        });
    }

    /**
     * Obtener la URL completa del video
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->archivo_path);
    }

    /**
     * Obtener la URL de la miniatura
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }
        return null;
    }

    /**
     * Obtener el tamaño formateado
     */
    public function getTamanoFormateadoAttribute()
    {
        $bytes = $this->tamano;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Obtener la duración formateada
     */
    public function getDuracionFormateadaAttribute()
    {
        if (!$this->duracion) return 'N/A';
        
        $hours = floor($this->duracion / 3600);
        $minutes = floor(($this->duracion % 3600) / 60);
        $seconds = $this->duracion % 60;
        
        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Incrementar contador de vistas
     */
    public function incrementarVistas()
    {
        $this->increment('vistas');
    }

    /**
     * Scope para videos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para videos por fecha
     */
    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('fecha_subida', $fecha);
    }

    /**
     * Scope para búsqueda por nombre
     */
    public function scopeBuscarPorNombre($query, $nombre)
    {
        return $query->where('nombre_video', 'LIKE', '%' . $nombre . '%');
    }

    /**
     * Scope para videos recientes
     */
    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }

    /**
     * Scope para videos populares
     */
    public function scopePopulares($query, $minVistas = 10)
    {
        return $query->where('vistas', '>=', $minVistas)->orderBy('vistas', 'desc');
    }

    /**
     * Mutator para convertir el nombre a título
     */
    public function setNombreVideoAttribute($value)
    {
        $this->attributes['nombre_video'] = ucwords(strtolower($value));
    }

    /**
     * Verificar si el video existe físicamente
     */
    public function existeArchivo()
    {
        return Storage::disk('public')->exists($this->archivo_path);
    }

    /**
     * Obtener información del archivo
     */
    public function getInfoArchivo()
    {
        if (!$this->existeArchivo()) {
            return null;
        }

        $path = Storage::disk('public')->path($this->archivo_path);
        
        return [
            'ruta_completa' => $path,
            'tamano_real' => filesize($path),
            'ultima_modificacion' => filemtime($path),
            'es_legible' => is_readable($path),
        ];
    }

    /**
     * Validar integridad del archivo
     */
    public function validarIntegridad()
    {
        $info = $this->getInfoArchivo();
        
        if (!$info) {
            return false;
        }
        
        // Verificar que el tamaño coincida
        return $info['tamano_real'] === $this->tamano;
    }
}
