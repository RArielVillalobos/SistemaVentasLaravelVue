<?php

namespace App\Http\Controllers;

use App\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    //
    public function index(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');

        }
        // $categorias=Categoria::all();

        //lo obtenido a traves de AJAX
        $buscar=$request->buscar;
        $criterio=$request->criterio;

        if($buscar==''){
            $roles= Rol::orderBy('id','desc')->paginate(3);

        }else{
            $roles= Rol::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(3);
        }


        return ['pagination'=>[
            'total'=>$roles->total(),
            'current_page'=>$roles->currentPage(),
            'per_page'=>$roles->perPage(),
            'last_page'=>$roles->lastPage(),
            'from'=>$roles->firstItem(),
            'to'=>$roles->lastItem(),
        ],
            'roles'=>$roles];

    }

}
