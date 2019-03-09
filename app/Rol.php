<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    //
    protected $table='roles';
    protected $filiable=['nombre','descripcion','condicion'];
    public $timestamps=false;


}
