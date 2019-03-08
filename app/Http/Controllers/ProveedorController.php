<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\DB;
use App\Proveedor;
use App\Persona;

class ProveedorController extends Controller
{
    //
    public function index(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
          
        }}
           
            //lo obtenido a traves de AJAX
            $buscar=$request->buscar;
            $criterio=$request->criterio;
            
            if($buscar==''){
                $proveedores= Proveedor::join('personas','proveedores.id','=','personas.id')
                ->select('personas.id','personas.nombre','personas.tipo_documento',
                'personas.num_documento','personas.direccion','personas.telefono',
                'personas.email','proveedores.contacto','proveedores.telefono_contacto')
                ->orderBy('personas.id','desc')->paginate(3);
               
            }else{
                $proveedores= Proveedor::join('personas','proveedores.id','=','personas.id')
                ->select('personas.id','personas.nombre','personas.tipo_documento',
                'personas.num_documento','personas.direccion','personas.telefono',
                'personas.email','proveedores.contacto','proveedores.telefono_contacto')->
                where('personas'.$criterio,'like','%'.$buscar.'%')->orderBy('personas.id','desc')->paginate(3);
            }
            

          return ['pagination'=>[
              'total'=>$proveedores->total(),
              'current_page'=>$proveedores->currentPage(),
              'per_page'=>$proveedores->perPage(),
              'last_page'=>$proveedores->lastPage(),
              'from'=>$proveedores->firstItem(),
              'to'=>$proveedores->lastItem(),
          ],
          'proveedores'=>$proveedores];
        
    }

    public function store(Request $request)
    {
        //
        if(!$request->ajax()){
            return redirect('/');
          
        }

        try {
            DB::beginTransaction();
            $persona=new Persona();
            $persona->nombre=$request->nombre;
            $persona->tipo_documento=$request->tipo_doc;
            $persona->num_documento=$request->num_documento;
            $persona->direccion=$request->direccion;
            $persona->telefono=$request->telefono;
            $persona->email=$request->email;
            $persona->save();

            $proveedor=new Proveedor();
            $proveedor->id=$persona->id;
            $proveedor->contacto=$request->contacto;
            $proveedor->telefono_contacto=$request->telefono_contacto;
            $proveedor->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            
        }
        

         
    }

    public function update(Request $request)
    {
        //
        if(!$request->ajax()){
            return redirect('/');
          
        }
       

        try {
            DB::beginTransaction();
            
            //Buscar el proveedor a editar
            $proveedor=Proveedor::findOrFail($request->id);
            $persona=Persona::findOrFail($proveedor->id);

            
            $persona->nombre=$request->nombre;
            $persona->tipo_documento=$request->tipo_doc;
            $persona->num_documento=$request->num_documento;
            $persona->direccion=$request->direccion;
            $persona->telefono=$request->telefono;
            $persona->email=$request->email;
            $persona->save();

            
            
            $proveedor->contacto=$request->contacto;
            $proveedor->telefono_contacto=$request->telefono_contacto;
            $proveedor->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            
        }
        


    }


}
