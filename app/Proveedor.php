<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    //
    protected $table='proveedores';
    protected $filiable=['id','telefono','telefono_contacto'];
    public $timestamps=false;

    public function persona(){
        return $this->belongTo('App\Persona');
    }
}
