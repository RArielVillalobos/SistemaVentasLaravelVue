<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    //
    protected $table='detalle_ingresos';

    protected $filiable=[
        'idingreso',
        'idarticulo',
        'cantidad',
        'precio'
    ];
    public $timestamps=false;
    
}
