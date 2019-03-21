<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Ventas
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>

                </div>
                <!-- Listado-->
                <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="comprobante">Tipo Comprobante</option>
                                        <option value="numero_comprobante">Numero Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>

                                    </select>
                                    <input type="text" v-model="buscar" v-on:keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" v-on:click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Usuario</th>
                                    <th>Cliente</th>
                                    <th>Tipo Comprobante</th>
                                    <th>Serie Comprobante</th>
                                    <th>Numero Comprobante</th>
                                    <th>Fecha-Hora</th>
                                    <th>Total</th>
                                    <th>Impuesto</th>
                                    <th>Estado</th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="venta in arrayVenta" :key="venta.id">
                                    <td>
                                        <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                        </button>&nbsp;
                                        <template v-if="venta.estado=='Registrado'" >
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                <i class="icon-trash"></i>
                                            </button>

                                        </template>


                                    </td>
                                    <td v-text="venta.usuario"></td>
                                    <td v-text="venta.nombre"></td>
                                    <td v-text="venta.tipo_comprobante"></td>
                                    <td v-text="venta.serie_comprobante"></td>
                                    <td v-text="venta.num_comprobante"></td>
                                    <td v-text="venta.fecha_hora"></td>
                                    <td v-text="venta.total"></td>
                                    <td v-text="venta.impuesto"></td>
                                    <td v-text="venta.estado"></td>


                                </tr>


                                </tbody>
                            </table>
                        </div>

                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.curret_page>1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page-1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>

                                <li class="page-item" v-if="pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page+1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <!-- Fin Listado-->
                </template>
                <!-- Detalle-->
                <template v-else-if="listado==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <v-select :on-search="selectCliente" label="nombre" :options="arrayCliente" placeholder="Buscando Cliente" :onChange="getDatosCliente">

                                    </v-select>    

                                </div>

                            </div>
                            <div class="col-md-3">
                                <label>Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="impuesto">

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="tipo_comprobante">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <input type="text" class="form-control" v-model="serie_comprobante" placeholder="Serie de Comprobante">

                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero  Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="Numero de Comprobante">

                                </div>

                            </div>
                            <div class="col-md-12">
                                <div v-show="errorVenta==1" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarVenta" :key="error" v-text="error">


                                    </div>
                                </div>    
                            </div>
                            </div>    


                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Articulo <span style="color:red" v-show="idarticulo==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese Articulo">
                                        <button @click="abrirModal()" class="btn btn-primary btn-sm">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">

                                    </div>

                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span style="color:red" v-show="idarticulo==0">(*Seleccione)</span></label>
                                    <input type="number" step="any"  class="form-control" v-model="precio">

                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color:red" v-show="idarticulo==0">(*Seleccione)</span></label>
                                    <input type="number" value="0"  class="form-control" v-model="cantidad">

                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento</label>
                                    <input type="number" value="0"  class="form-control" v-model="descuento">

                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-success form-control btnagregar" @click="agregarDetalle()">
                                        <i class="icon-plus"></i>
                                    </button>

                                </div>

                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Articulo</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>

                                    </thead>
                                    <tbody v-if="arrayDetalle.length>=1">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id"> 
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm" >
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo">
                                                
                                            </td>
                                            <td>
                                                <input type="number" v-model="detalle.precio"  class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span>
                                                <input v-model="detalle.cantidad" type="number"   class="form-control">

                                            </td>
                                            <td>
                                                <span style="color:red" v-show="detalle.descuento>detalle.precio*detalle.cantidad">Descuento Superior</span>
                                                <input v-model="detalle.descuento" type="number"  class="form-control">

                                            </td>
                                            <td>
                                                {{(detalle.precio*detalle.cantidad)-detalle.descuento}}

                                            </td>
                                        </tr>
                                       
                                        <tr style="background-color:#CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>


                                        </tr>
                                        <tr style="background-color:#CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{totalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>


                                        </tr>
                                        <tr style="background-color:#CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total=calcularTotal}}</td>


                                        </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr >
                                            <td colspan="6">
                                                 No hay Articulos Agregados
                                            </td>    


                                            
                                        </tr>        
                                    </tbody>    

                                </table>

                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                            </div>

                        </div>
                    </div>
                </template>
                <!-- Fin  Detalle-->
                <!-- Ver Ingreso-->
                <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p> 

                                </div>

                            </div>
                            <div class="col-md-3">
                                <label>Impuesto</label>
                                <p v-text="impuesto"></p>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>

                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero  Comprobante</label>
                                    <p v-text="num_comprobante"></p>

                                </div>

                            
                           
                            </div>    


                        </div>
                        
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        
                                        <th>Articulo</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>

                                    </thead>
                                    <tbody v-if="arrayDetalle.length>=1">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id"> 
                                            
                                            <td v-text="detalle.articulo">
                                                
                                            </td>
                                            <td v-text="detalle.precio">
                                                
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td v-text="detalle.descuento">
                                            </td>
                                            <td>
                                                
                                                {{detalle.precio*detalle.cantidad-detalle.descuento}}

                                            </td>
                                        </tr>
                                       
                                        <tr style="background-color:#CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>


                                        </tr>
                                        <tr style="background-color:#CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{totalImpuesto=((total*impuesto)).toFixed(2)}}</td>


                                        </tr>
                                        <tr style="background-color:#CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total}}</td>


                                        </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr >
                                            <td colspan="5">
                                                 No hay Articulos Agregados
                                            </td>    


                                            
                                        </tr>        
                                    </tbody>    

                                </table>

                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle" class="btn btn-secondary">Cerrar</button>
                                
                            </div>

                        </div>
                    </div>
                </template>
                
                <!-- Fin Ver Ingreso-->
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioModal">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Código</option>
                                    </select>
                                    <input type="text" v-model="buscarModal" v-on:keyup.enter="listarArticuloModal(buscarModal,criterioModal)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" v-on:click="listarArticuloModal(buscarModal,criterioModal)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Codigo de Barra</th>
                                        <th>Nombre</th>
                                        <th>Categoria</th>
                                        <th>Precio de Venta</th>
                                        <th>Stock</th>
                                        
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                        <td>
                                            <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                            
                                        </td>
                                        <td v-text="articulo.codigo"></td>
                                        <td v-text="articulo.nombre"></td>
                                        <td v-text="articulo.nombre_categoria"></td>
                                        <td v-text="articulo.precio_venta"></td>
                                        <td v-text="articulo.stock"></td>
                                        
                                        <td>
                                            <div v-if="articulo.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>

                                        </td>
                                    </tr>


                                </tbody>
                    </table>

                        </div>    

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button"  class="btn btn-primary" v-if="tipoAccion==1" @click="registrarPersona()">Guardar</button>
                        <button type="button"  class="btn btn-primary" v-if="tipoAccion==2" @click="actualizarPersona()">Actualizar</button>
                        
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        
    </main>
</template>
<script>
    import vSelect from 'vue-select';
    
    export default {
        data() {
            return {
                venta_id:0,
                idcliente:0,
                cliente:'',
                tipo_comprobante:'BOLETA',
                serie_comprobante:'',
                num_comprobante:'',
                impuesto:0.18,
                total:0.0,
                totalImpuesto:0.0,
                totalParcial:0.0,
                arrayVenta:[],
                arrayDetalle:[],
                arrayCliente:[],
                listado:1,
                modal: 0,
                tituloModal: '',
                tipoAccion : 0,
                errorVenta: 0,
                errorMostrarVenta:[],
                pagination:{
                    'total':0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0

                },
                offset:3,
                criterio:'num_comprobante',
                buscar:'',
                buscarModal:'',
                criterioModal:'nombre',
                arrayArticulo:[],
                idarticulo:0,
                codigo:'',
                articulo:'',
                precio:0,
                cantidad:0,
                descuento:0,
                stock:0,

            }
        },
        components:{
            vSelect
        },
        //propiedad computada
        computed:{
            isActived:function(){
                return pagination.current_page;
            },
            pagesNumber:function (){
                if(!this.pagination.to){
                   return [] ;
                }
                var from=this.pagination.current_page - this.offset;
                if(from<1){
                    from=1;
                }
                var to=from + (this.offset*2);
                if(to>=this.pagination.last_page){
                    to=this.pagination.last_page;

                }

                var pagesArray=[];
                while (from<=to) {
                    pagesArray.push(from);
                    from++;
                    
                }
                return pagesArray;

                
            },
            calcularTotal:function(){
                var resultado=0;
                for(var i=0; i<this.arrayDetalle.length; i++){
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad-this.arrayDetalle[i].descuento);
                }
                return resultado;
            }

        },
        methods: {
            listarVenta(page,buscar,criterio) {
                let me = this;
                var url='/venta?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;


                    
                    // handle success
                    me.arrayVenta = respuesta.ventas.data;



                    me.pagination=respuesta.pagination;
                })
                    .catch(function (error) {
                        // handle error
                        
                        console.log(error);
                    });

            },
            
            selectCliente(search,loading){
                let me=this;
                loading(true)
                var url='/venta/selectCliente?filtro='+search;
                axios.get(url).then(function(response){
                    let respuesta=response.data;
                    q:search
                     me.arrayCliente=respuesta.clientes;
                     loading(false)



                }).catch(function(error){
                    console.log(error)

                });
            },
            getDatosCliente(vall){
                let me=this;
                me.loading=true;
                me.idcliente=vall.id;

            },

            buscarArticulo(){
                let me=this;
                var url='/articulo/buscarArticuloVenta?filtro='+me.codigo;

                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayArticulo=respuesta.articulos;
                    //si hemos encontrado un articulo
                    if(me.arrayArticulo.length>0){
                        me.articulo=me.arrayArticulo[0]['nombre'];
                        me.idarticulo=me.arrayArticulo[0]['id'];
                        me.precio=me.arrayArticulo[0]['precio_venta'];
                        me.stock=me.arrayArticulo[0]['stock'];




                    }else{
                        me.articulo='No existe articulo';
                        me.idarticulo=0;


                    }



                }).catch(function(error){
                    console.log(error);

                });

            },

            //el page recibe el numero de pagina que queremos mostrar
            cambiarPagina(page,buscar,criterio){
                let me=this;

                //Actualiza la pagina Actual
                me.pagination.current_page=page;
                //Envia la peticion para visualizar la data de esa pagina
                me.listarVenta(page,buscar,criterio);
                } 



            ,
            encuentra(idarticulo){
                var encontrado=false;
                for(var i=0; i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idarticulo==idarticulo){
                        encontrado=true;
                    }
                }

                return encontrado;


            },
            agregarDetalle(){
                let me=this;
                if(me.idarticulo==0|me.cantidad==0|me.precio==0){
                    return false;
                }else{
                    if(me.encuentra(me.idarticulo)){
                        swal({
                            type:'error',
                            title:'Error',
                            text:'Este articulo ya se encuentra agregado'

                        })

                    }else{
                        if(me.cantidad>me.stock){
                            swal({
                            type:'error',
                            title:'Error',
                            text:'No hay stock disponible'

                        })

                        }else{
                            me.arrayDetalle.push({
                            idarticulo:me.idarticulo,
                            articulo:me.articulo,
                            cantidad:me.cantidad,
                            precio:me.precio,
                            descuento:me.descuento,
                            stock:me.stock

                            });
                            me.codigo='';
                            me.idarticulo=0;
                            me.articulo='';
                            me.cantidad=0;
                            me.precio=0;
                            me.descuento='';
                            me.stock='';

                        }


                    }
                        

                }
                
                

            },
            agregarDetalleModal(data=[]){
                let me=this;
                 if(me.encuentra(data['id'])){
                        swal({
                            type:'error',
                            title:'Error',
                            text:'Este articulo ya se encuentra agregado'

                        })

                    }else{
                        me.arrayDetalle.push({
                            idarticulo:data['id'],
                            articulo:data['nombre'],
                            cantidad:1,
                            precio:data['precio_venta'],
                            descuento:0,
                            stock:data['stock']

                    });
                    




                }
            },
            listarArticuloModal(buscar,criterio) {
                let me = this;
                var url='/articulo/listarArticuloModalVenta?buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;

                    // handle success
                    me.arrayArticulo = respuesta.articulosModal.data;
                    
                })
                    .catch(function (error) {
                        // handle error
                        
                        console.log(error);
                    });

            },

            eliminarDetalle(index){
                let me=this;
                //eliminar el elemento del array que coincida con el indice, el 1 es cuantos elementos eliminara
                me.arrayDetalle.splice(index,1);
            },
            registrarVenta() {

                //si la validacion devuelve true o 1 , significa que hubo error    
                if(this.validarVenta()){
                    return false
                }
                let me=this;
                //usamos el verbo post en vez de get
                //primer parametro la ruta del controlador que registra
                //segundo parametro enviaremos los valores q recibira el controlador
                axios.post('/venta/registrar',
                {'idcliente':this.idcliente,
                 'tipo_comprobante':this.tipo_comprobante,
                 'serie_comprobante':this.serie_comprobante,
                 'num_comprobante':this.num_comprobante,
                 'impuesto':this.impuesto,
                 'total':this.total,
                 'data':this.arrayDetalle,
                
                }).then(function(){
                    //que muestre el listado
                    me.listado=1;
                    
                    me.listarVenta(1,'','num_comprobante');
                    me.idproveedor=0;
                    me.tipo_comprobante='BOLETA';
                    me.serie_comprobante='';
                    me.num_comprobante='';
                    me.impuesto=0.18;
                    me.total=0;
                    me.idarticulo=0;
                    me.articulo='';
                    me.cantidad=0;
                    me.precio=0;
                    me.stock=0;
                    me.codig='';
                    me.descuento=0;
                    me.arrayDetalle=[];

        

                }).catch(function(error){
                    console.log(error);
                })

            },
            actualizarPersona(){

                  //si la validacion devuelve true o 1 , significa que hubo error    
                if(this.validarPersona()){
                    return false
                }
                let me=this;
                //usamos el verbo post en vez de get
                //primer parametro la ruta del controlador que registra
                //segundo parametro enviaremos los valores q recibira el controlador
                axios.put('/user/actualizar',
                {'nombre':this.nombre,
                'tipo_doc':this.tipo_documento,
                'num_documento':this.numero_documento,
                'direccion':this.direccion,
                'telefono':this.telefono,
                'email':this.email,
                'usuario':this.usuario,
                'password':this.password,
                'idrol':this.idrol,
                'id':this.persona_id
                }).then(function(){
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
        

                }).catch(function(error){
                    console.log(error);
                })


            },

            
            validarVenta(){
                let me=this;
                me.errorVenta=0;
                me.errorMostrarVenta=[];
                var art;
                //recorro los valores con la funcion map, cada valor es la variable x
                me.arrayDetalle.map(function(x){
                    console.log(x);
                    if(x.cantidad>x.stock){
                        art=x.articulo + ' con stock insuficiente';
                        me.errorMostrarVenta.push(art);

                    }

                });
                //si el nombre esta vacio, agrega el msj de error
                if(me.idcliente==0){
                    me.errorMostrarVenta.push("Seleccione un Cliente");

                }
                if(me.tipo_comprobante==0){
                    me.errorMostrarVenta.push("Seleccione el comprobante");

                }
                if(me.num_comprobante==''){
                    me.errorMostrarVenta.push("ingrese numero de comprobante");

                }
                if(me.impuesto==0){
                    me.errorMostrarVenta.push("ingrese el impuesto de compra");

                }
                if(me.arrayDetalle.length<=0){
                    me.errorMostrarVenta.push("Agrege articulos a la compra")
                }
                
                if(me.errorMostrarVenta.length){
                    me.errorVenta=1;
                } 
                return me.errorVenta;     
            },

            mostrarDetalle(){
                let me=this;
                me.listado=0;
                me.idproveedor=0;
                me.tipo_comprobante='BOLETA';
                me.serie_comprobante='';
                me.num_comprobante='';
                me.impuesto=0.18;
                me.total=0;
                me.idarticulo=0;
                me.articulo='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];

            },
            ocultarDetalle(){
                this.listado=1;

            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            
                

            },
            verVenta(ventaid){
                let me=this;
                me.listado=2;


                //obtener datos del ingreso
                var arrayVentaT=[];
                

                axios.get('/venta/obtenerCabecera?id='+ventaid).then(function(response){
                    var respuesta=response.data;
                    arrayVentaT=respuesta.venta;
                    me.cliente=arrayVentaT[0]['nombre'];
                    me.tipo_comprobante=arrayVentaT[0]['tipo_comprobante'];
                    me.serie_comprobante=arrayVentaT[0]['serie_comprobante'];
                    me.num_comprobante=arrayVentaT[0]['num_comprobante'];
                    me.impuesto=arrayVentaT[0]['impuesto'];
                    me.total=arrayVentaT[0]['total'];



                
                    


                }).catch(function(error){
                    console.log(error)

                });

                //obtener los datos de los detalles
                
                axios.get('/venta/obtenerDetalles?id='+ventaid).then(function(response){
                    var respuesta=response.data;
                    me.arrayDetalle=respuesta.detalles;
                    


                    
                    


                }).catch(function(error){
                    console.log(error)

                });
                


            },
            abrirModal() {
                this.arrayArticulo=[]; 
                this.modal=1;
                this.tituloModal='Seleccione uno o varios articulos';
                                

            },
            desactivarIngreso(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta Seguro de anular el ingreso?',

                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let me=this;
                        //usamos el verbo post en vez de get
                        //primer parametro la ruta del controlador que registra
                        //segundo parametro enviaremos los valores q recibira el controlador
                        axios.put('/ingreso/desactivar',
                            {'id':id
                            }).then(function(){

                            me.listarIngreso(1,'','num_comprobante');
                            swalWithBootstrapButtons.fire(
                                'Anulado!',
                                'El registro ha sido anulado con exito.',
                                'success'
                            )


                        }).catch(function(error){
                            console.log(error);
                        })


                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {

                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                })
            },
            

            

    },
        mounted(){
                    this.listarVenta(1,this.buscar,this.nombre);
                    
                }

    }
            
        
    
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;

    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: black !important;
    }

    .div-error{
        display:flex;
        justify-content: center;

    }
    .text-error{
        color:red !important;
        font-weight: bold;

    }

    @media(min-width: 600px){
        .btnagregar{
            margin-top: 2rem;
        }
    }
</style>