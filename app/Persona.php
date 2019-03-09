<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    //

    protected $filliable=['nombre','tipo_documento','num_documento','direccion', 'telefono','email'];
    
    public function proveedor(){
        return $this->hasOne('App\Proveedor');
    }

    public function user(){
        return $this->hasOne(User::class);
    }
}
