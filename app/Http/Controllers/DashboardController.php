<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function __invoke(Request $request)
    {
        $anio=date('Y');

        $ingresos=DB::table('ingresos as i')
            ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
            DB::raw('YEAR(i.fecha_hora) as anio'),
            DB::raw('SUM(i.total) as total'))
            ->whereYear('i.fecha_hora',$anio)
            //agrupamos por mes y anio para poder usar la funcion SUM que esta arriba
            ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))->get();

        return ['ingresos'=>$ingresos,'anio'=>$anio];
    }
}
