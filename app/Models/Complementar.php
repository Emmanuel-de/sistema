<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complementar extends Model
{
    use HasFactory;

    // Nombre de la tabla explícitamente definido
    protected $table = 'datos_complementarios';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'folio_unico',
        'tipo_documento',
        'nuc',
        'fecha_recepcion',
        'quien_presenta',
        'numero_hojas',    // Corregido
        'numero_anexos',   // Corregido
        'descripcion',
        'numero_oficio',
        'fecha_oficio',
        'tipo_audiencia',
        'numero_amparo',   // Corregido
        'precedencia',     // Corregido
        'entidad',
        'solicita_informe',
        'estado',             // Incluido para consistencia si se maneja aquí
        'usuario_creacion',
        'usuario_modificacion',
        'usuario_eliminacion',
        'fecha_eliminacion',
    ];

    // Si no usas los campos created_at y updated_at, puedes añadir:
    // public $timestamps = true; // Asegúrate de que esto es true si están en la migración (y lo están)
}
