<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos'; // Especifica el nombre de la tabla

    protected $fillable = [
        'estado',
        'titulo',
        'descripcion',
        'tipo_documento',
        'archivo',
        'usuario_id',
        'numero',
        'tipo',
        'solicitante',
        'fecha_solicitud',
        'prioridad',
        'liberado_por',
        'fecha_liberacion',
        'observaciones_liberacion',
        'rechazado_por',
        'fecha_rechazo',
        'motivo_rechazo',
        'asignado_a',
        'turnado_por',
        'fecha_turnado',
        'observaciones_turnado'
    ];

    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_liberacion' => 'datetime',
        'fecha_rechazo' => 'datetime',
        'fecha_turnado' => 'datetime'
    ];
}