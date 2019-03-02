<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Articulo extends Model
{
    //
    protected $filiable=['idcategoria','codigo','nombre','precio_venta','stock','descripcion','condicion'];


    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }
}
