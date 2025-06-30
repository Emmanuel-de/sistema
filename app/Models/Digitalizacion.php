<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Digitalizacion extends Model
{
    use HasFactory;

    protected $table = 'digitalizaciones';

    protected $fillable = [
        'tipo',
        'nuc',
        'presentado_por',
        'fecha_presentacion',
        'comentario',
        'ocr',
        'visor',
        'archivos',
        'total_archivos',
        'estado'
    ];

    protected $casts = [
        'fecha_presentacion' => 'date',
        'ocr' => 'boolean',
        'visor' => 'boolean',
        'archivos' => 'array',
    ];

    // Constantes para los tipos
    const TIPOS = [
        'carpeta_preliminar' => 'Carpeta Preliminar',
        'carpeta_procesal' => 'Carpeta Procesal',
        'amparo' => 'Amparo',
        'oficio' => 'Oficio',
        'evidencia' => 'Evidencia'
    ];

    // Constantes para estados
    const ESTADOS = [
        'pendiente' => 'Pendiente',
        'procesando' => 'Procesando',
        'completado' => 'Completado',
        'error' => 'Error'
    ];

    // Accessor para obtener el nombre del tipo
    public function getTipoNombreAttribute()
    {
        return self::TIPOS[$this->tipo] ?? $this->tipo;
    }

    // Accessor para obtener el nombre del estado
    public function getEstadoNombreAttribute()
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    // Método para obtener la ruta de los archivos
    public function getRutaArchivos()
    {
        return 'digitalizaciones/' . $this->id;
    }

    // Método para obtener URLs de archivos
    public function getUrlsArchivos()
    {
        if (!$this->archivos) {
            return [];
        }

        $urls = [];
        foreach ($this->archivos as $archivo) {
            if (Storage::disk('public')->exists($archivo['ruta'])) {
                $urls[] = [
                    'nombre' => $archivo['nombre'],
                    'url' => Storage::disk('public')->url($archivo['ruta']),
                    'tipo' => $archivo['tipo'] ?? 'unknown',
                    'tamaño' => $archivo['tamaño'] ?? 0
                ];
            }
        }

        return $urls;
    }

    // Método para eliminar archivos físicos
    public function eliminarArchivos()
    {
        if ($this->archivos) {
            foreach ($this->archivos as $archivo) {
                if (Storage::disk('public')->exists($archivo['ruta'])) {
                    Storage::disk('public')->delete($archivo['ruta']);
                }
            }
        }
    }

    // Scopes para filtros
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePorNuc($query, $nuc)
    {
        return $query->where('nuc', $nuc);
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin = null)
    {
        if ($fechaFin) {
            return $query->whereBetween('fecha_presentacion', [$fechaInicio, $fechaFin]);
        }
        return $query->where('fecha_presentacion', $fechaInicio);
    }

    // Método para validar tipos de archivo permitidos
    public static function getExtensionesPermitidas()
    {
        return ['pdf', 'jpg', 'jpeg', 'png', 'tiff', 'bmp'];
    }

    // Evento para limpiar archivos al eliminar registro
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($digitalizacion) {
            $digitalizacion->eliminarArchivos();
        });
    }
}