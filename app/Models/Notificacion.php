<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notificaciones';

    protected $fillable = [
        'observacion',
        'fecha_notificacion',
        'fecha_alta',
        'persona_notifico',
        'telefono',
        'audio_path',
        'numero_telefono',
        'usuario_notifica_id',
        'seleccione_notificacion',
        'observaciones',
        'nuc'
    ];

    protected $casts = [
        'fecha_notificacion' => 'datetime',
        'fecha_alta' => 'datetime',
    ];

    protected $dates = [
        'deleted_at'
    ];

    // Relación con el usuario que notifica
    public function usuarioNotifica()
    {
        return $this->belongsTo(User::class, 'usuario_notifica_id');
    }
}