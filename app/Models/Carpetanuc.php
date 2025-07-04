<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carpetanuc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'carpetanucs';

    protected $fillable = [
        'numero_carpeta',
        'tipo_carpeta',
        'separacion_procesos',
        'estado',
        'fiscal_asignado',
        'secretario_asignado',
        'delito_principal',
        'delitos_secundarios',
        'fecha_inicio',
        'fecha_cierre',
        'municipio',
        'agencia',
        'observaciones',
        'imputados',
        'victimas',
        'audiencias',
        'documentos',
        'total_folios',
        'ubicacion_fisica',
        'digitalizacion_completa',
        'fecha_ultima_actualizacion',
        'usuario_ultima_actualizacion'
    ];

    protected $casts = [
        'separacion_procesos' => 'boolean',
        'imputados' => 'array',
        'victimas' => 'array',
        'audiencias' => 'array',
        'documentos' => 'array',
        'fecha_inicio' => 'date',
        'fecha_cierre' => 'date',
        'fecha_ultima_actualizacion' => 'datetime',
        'digitalizacion_completa' => 'boolean',
        'total_folios' => 'integer'
    ];

    protected $dates = [
        'fecha_inicio',
        'fecha_cierre',
        'fecha_ultima_actualizacion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Scopes para filtros comunes
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    public function scopeCerradas($query)
    {
        return $query->where('estado', 'cerrada');
    }

    public function scopeConSeparacionProcesos($query)
    {
        return $query->where('separacion_procesos', true);
    }

    public function scopePorFiscal($query, $fiscal)
    {
        return $query->where('fiscal_asignado', $fiscal);
    }

    public function scopePorMunicipio($query, $municipio)
    {
        return $query->where('municipio', $municipio);
    }

    public function scopePorAgencia($query, $agencia)
    {
        return $query->where('agencia', $agencia);
    }

    public function scopeBuscarPorNumero($query, $numero)
    {
        return $query->where('numero_carpeta', 'like', "%{$numero}%");
    }

    // Métodos auxiliares
    public function getImputadosListaAttribute()
    {
        if (empty($this->imputados)) {
            return collect();
        }
        
        return collect($this->imputados);
    }

    public function getVictimasListaAttribute()
    {
        if (empty($this->victimas)) {
            return collect();
        }
        
        return collect($this->victimas);
    }

    public function getAudienciasListaAttribute()
    {
        if (empty($this->audiencias)) {
            return collect();
        }
        
        return collect($this->audiencias);
    }

    public function getDocumentosListaAttribute()
    {
        if (empty($this->documentos)) {
            return collect();
        }
        
        return collect($this->documentos);
    }

    public function agregarImputado($imputado)
    {
        $imputados = $this->imputados ?? [];
        $imputados[] = $imputado;
        $this->imputados = $imputados;
        return $this;
    }

    public function agregarVictima($victima)
    {
        $victimas = $this->victimas ?? [];
        $victimas[] = $victima;
        $this->victimas = $victimas;
        return $this;
    }

    public function agregarAudiencia($audiencia)
    {
        $audiencias = $this->audiencias ?? [];
        $audiencias[] = $audiencia;
        $this->audiencias = $audiencias;
        return $this;
    }

    public function agregarDocumento($documento)
    {
        $documentos = $this->documentos ?? [];
        $documentos[] = $documento;
        $this->documentos = $documentos;
        return $this;
    }

    public function marcarComoActualizada()
    {
        $this->fecha_ultima_actualizacion = now();
        $this->usuario_ultima_actualizacion = auth()->user()->name ?? 'Sistema';
        return $this;
    }

    public function cerrarCarpeta()
    {
        $this->estado = 'cerrada';
        $this->fecha_cierre = now();
        $this->marcarComoActualizada();
        return $this;
    }

    public function abrirCarpeta()
    {
        $this->estado = 'activa';
        $this->fecha_cierre = null;
        $this->marcarComoActualizada();
        return $this;
    }

    public function estaActiva()
    {
        return $this->estado === 'activa';
    }

    public function estaCerrada()
    {
        return $this->estado === 'cerrada';
    }

    public function tieneImputados()
    {
        return !empty($this->imputados);
    }

    public function tieneVictimas()
    {
        return !empty($this->victimas);
    }

    public function tieneAudiencias()
    {
        return !empty($this->audiencias);
    }

    public function tieneDocumentos()
    {
        return !empty($this->documentos);
    }

}