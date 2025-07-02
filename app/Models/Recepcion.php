<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
    use HasFactory;

    protected $table = 'recepciones'; // Asegúrate de que el nombre de la tabla sea correcto
    protected $guarded = []; // Permite asignación masiva para todos los campos (o lista los fillable)
}
