<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agenda_citas';

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'titulo',
        'cliente',
        'telefono',
        'email',
        'descripcion',
        'color',
        'estado',
        'user_id'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getHoraAttribute()
    {
        return $this->hora_inicio ? Carbon::parse($this->hora_inicio)->format('H:i') : '';
    }

    public function scopeEnFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    public function scopeDelMes($query, $year, $month)
    {
        return $query->whereYear('fecha', $year)->whereMonth('fecha', $month);
    }
}