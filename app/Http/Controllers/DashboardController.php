<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Datos para carpetas por etapa
        $carpetasPorEtapa = [
            'investigacion_inicial' => 11,
            'investigacion_complementaria' => 8,
            'intermedia' => 0,
            'juicio' => 0,
            'concluida' => 0,
            'no_definida' => 0
        ];

        // Datos para carpetas por año
        $carpetasPorAno = [
            '2015' => 2,
            '2016' => 15,
            '2017' => 14
        ];

        // Datos para audiencias por juez
        $audienciasPorJuez = [
            'SANTIAGO' => 27,
            'CARLOS FAVIÁN' => 2
        ];

        return view('dashboard.index', compact(
            'carpetasPorEtapa',
            'carpetasPorAno',
            'audienciasPorJuez'
        ));
    }

    public function getCarpetasData()
    {
        // Si tienes una tabla de carpetas
        /*
        $carpetas = DB::table('carpetas')
            ->select(DB::raw('YEAR(created_at) as año'), DB::raw('COUNT(*) as total'))
            ->groupBy('año')
            ->orderBy('año')
            ->get();
        
        return response()->json($carpetas);
        */
        
        return response()->json([
            ['año' => 2015, 'total' => 2],
            ['año' => 2016, 'total' => 15],
            ['año' => 2017, 'total' => 14]
        ]);
    }

    public function getAudienciasData()
    {
        // Si tienes una tabla de audiencias
        /*
        $audiencias = DB::table('audiencias')
            ->join('jueces', 'audiencias.juez_id', '=', 'jueces.id')
            ->select('jueces.nombre', DB::raw('COUNT(*) as total'))
            ->whereYear('audiencias.fecha', 2017)
            ->groupBy('jueces.id', 'jueces.nombre')
            ->get();
        
        return response()->json($audiencias);
        */
        
        return response()->json([
            ['nombre' => 'SANTIAGO', 'total' => 27],
            ['nombre' => 'CARLOS FAVIÁN', 'total' => 2]
        ]);
    }

    public function getEtapasData()
    {
        // Si tienes una tabla de carpetas con etapas
        /*
        $etapas = DB::table('carpetas')
            ->select('etapa', DB::raw('COUNT(*) as total'))
            ->groupBy('etapa')
            ->get();
        
        return response()->json($etapas);
        */
        
        return response()->json([
            ['etapa' => 'investigacion_inicial', 'total' => 11],
            ['etapa' => 'investigacion_complementaria', 'total' => 8],
            ['etapa' => 'intermedia', 'total' => 0],
            ['etapa' => 'juicio', 'total' => 0],
            ['etapa' => 'concluida', 'total' => 0],
            ['etapa' => 'no_definida', 'total' => 0]
        ]);
    }
}
