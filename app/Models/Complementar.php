<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complementar extends Model
{
    use HasFactory;

    // Define el nombre de la tabla si no sigue la convención de nombres de Laravel (plural del nombre del modelo)
    // Si tu tabla se llama 'datos_complementarios', esto es correcto.
    protected $table = 'datos_complementarios'; 

    // Define los campos que pueden ser asignados masivamente (mass assignable)
    // Es crucial que 'numero_hojas' y 'descripcion' estén aquí, según tu tabla 'datos_complementarios'.
    protected $fillable = [
        'folio_unico',
        'tipo_documento',
        'nuc',
        'fecha_recepcion',
        'quien_presenta',
        'numero_hojas',          // Confirmado: este es el nombre de la columna en tu tabla 'datos_complementarios'
        'numero_anexos',
        'descripcion',           // ¡CORREGIDO! Ahora coincide con el nombre de la columna 'descripcion' en tu tabla 'datos_complementarios'
        'numero_oficio',
        'fecha_oficio',
        'tipo_audiencia',
        'numero_amparo',
        'precedencia',
        'entidad',
        'solicita_informe',
        'usuario_creacion',
        'estado',
        'usuario_modificacion',  // Si usas estos campos para el update
        'fecha_eliminacion',     // Si usas soft deletes
        'usuario_eliminacion',   // Si usas soft deletes
    ];

    // Si tu tabla no usa 'created_at' y 'updated_at', puedes desactivar los timestamps
    // public $timestamps = false; 

    // Si tienes relaciones, las definirías aquí
    // Por ejemplo, si un Complementar pertenece a una Recepcion:
    // public function recepcion()
    // {
    //     return $this->belongsTo(Recepcion::class, 'nuc', 'nuc'); // Asumiendo 'nuc' es la clave foránea
    // }
}
