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
                    <i class="fa fa-align-justify"></i> Articulos
                    <button type="button" @click="abrirModal('articulo','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="cargarPdf()" class="btn btn-secondary">
                        <i class="icon-doc"></i>&nbsp;Reporte
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" v-on:keyup.enter="listarArticulo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" v-on:click="listarArticulo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Codigo de Barra</th>
                            <th>Nombre</th>
                            <th>Categoria</th>
                            <th>Precio de Venta</th>
                            <th>Stock</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                            <td>
                                <button type="button" @click="abrirModal('articulo','actualizar',articulo)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <template v-if="articulo.condicion" >
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo.id)">
                                        <i class="icon-trash"></i>
                                    </button>    
                                    
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-success btn-sm" @click="activarArticulo(articulo.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <i class="icon-check"></i>
                                </template>
                            </td>
                            <td v-text="articulo.codigo"></td>
                            <td v-text="articulo.nombre"></td>
                            <td v-text="articulo.nombre_categoria"></td>
                            <td v-text="articulo.precio_venta"></td>
                            <td v-text="articulo.stock"></td>
                            <td v-text="articulo.descripcion"></td>
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
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="idcategoria">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>   
                                    </select>    
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Codigo</label>

                                <div class="col-md-9">
                                    <input type="text" v-model="codigo" class="form-control" placeholder="Codigo de Barra">
                                    <barcode :value="codigo" :options="{format:'EAN-12'}">Generando Codigo de Barras</barcode>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Precio de Venta</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="precio_venta" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Stock</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="stock" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="descripcion" class="form-control" placeholder="Descripcion">
                                </div>
                            </div>
                            <div v-show="errorArticulo==1" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarArticulo" :key="error" v-text="error">


                                    </div>
                                </div>    
                            </div>    
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button"  class="btn btn-primary" v-if="tipoAccion==1" @click="registrarArticulo()">Guardar</button>
                        <button type="button"  class="btn btn-primary" v-if="tipoAccion==2" @click="actualizarArticulo()">Actualizar</button>
                        
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
import VueBarcode from 'vue-barcode';
    export default {
        data() {
            return {
                articulo_id:0,
                idcategoria:0,
                nombre_categoria:'',
                codigo:'',
                nombre: '',
                precio_venta:0,
                stock:0,
                descripcion: '',
                arrayArticulo: [],
                modal: 0,
                tituloModal: '',
                tipoAccion : 0,
                errorArticulo: 0,
                errorMostrarArticulo:[],
                pagination:{
                    'total':0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0

                },
                offset:3,
                criterio:'nombre',
                buscar:'',
                arrayCategoria:[]
            }
        },
         components: {
            'barcode': VueBarcode
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

                
            }

        },
        methods: {
            listarArticulo(page,buscar,criterio) {
                let me = this;
                var url='/articulo?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;

                    // handle success
                    me.arrayArticulo = respuesta.articulos.data;
                    me.pagination=respuesta.pagination;
                })
                    .catch(function (error) {
                        // handle error
                        
                        console.log(error);
                    });

            },
            cargarPdf(){
                window.open('http://127.0.0.1:8000/articulo/listarPdf','_blank');
            },
            selectCategoria(){
                 let me = this;
                var url='/categoria/selectCategoria';
                axios.get(url).then(function (response) {
                    
                    var respuesta=response.data;

                    // handle success
                   me.arrayCategoria = respuesta.categorias
                
                })
                    .catch(function (error) {
                        // handle error
                        
                        console.log(error);
                    });

            },
            //el page recibe el numero de pagina que queremos mostrar
            cambiarPagina(page,buscar,criterio){
                let me=this;

                //Actualiza la pagina Actual
                me.pagination.current_page=page;
                //Envia la peticion para visualizar la data de esa pagina
                me.listarArticulo(page,buscar,criterio);
                } 



            ,
            registrarArticulo() {

                //si la validacion devuelve true o 1 , significa que hubo error    
                if(this.validarArticulo()){
                    return false
                }
                let me=this;
                //usamos el verbo post en vez de get
                //primer parametro la ruta del controlador que registra
                //segundo parametro enviaremos los valores q recibira el controlador
                axios.post('/articulo/registrar',
                {
                'idcategoria':this.idcategoria,
                'codigo':this.codigo,
                'nombre':this.nombre,
                'stock':this.stock,
                'precio_venta':this.precio_venta,
                'descripcion':this.descripcion,


                }).then(function(){
                    me.cerrarModal();
                    me.listarArticulo(1,'','nombre');
        

                }).catch(function(error){
                    console.log(error);
                })

            },
            actualizarArticulo(){

                  //si la validacion devuelve true o 1 , significa que hubo error    
                if(this.validarArticulo()){
                    return false
                }
                let me=this;
                //usamos el verbo post en vez de get
                //primer parametro la ruta del controlador que registra
                //segundo parametro enviaremos los valores q recibira el controlador
                axios.put('/articulo/actualizar',
                {
                'id':this.articulo_id,
                'nombre':this.nombre,
                'idcategoria':this.idcategoria,
                'codigo':this.codigo,
                'stock':this.stock,
                'precio_venta':this.precio_venta,
                'descripcion':this.descripcion,
                
                }).then(function(){
                    me.cerrarModal();
                    me.listarArticulo(1,'','nombre');
        

                }).catch(function(error){
                    console.log(error);
                })


            },

            desactivarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de desactivar este Articulo?',
                
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
                    axios.put('/articulo/desactivar',
                    {'id':id
                    }).then(function(){
                        
                        me.listarArticulo(1,'','nombre');
                        swalWithBootstrapButtons.fire(
                        'Desactivado!',
                        'El registro ha sido desactivado.',
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
            activarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de activar este Articulo?',
                
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
                    axios.put('/articulo/activar',
                    {'id':id
                    }).then(function(){
                        
                        me.listarArticulo(1,'','nombre');
                        swalWithBootstrapButtons.fire(
                        'Activado!',
                        'El registro ha sido activado.',
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

            validarArticulo(){
                this.errorArticulo=0;
                this.errorMostrarArticulo=[]
                //si el nombre esta vacio, agrega el msj de error
                if(this.idcategoria==0){
                    this.errorMostrarArticulo.push("Seleccione una categoria");

                }
                if(this.nombre==''){
                    this.errorMostrarArticulo.push("El nombre del articulo no puede estar vacio");

                }
                if(this.precio_venta==''){
                    this.errorMostrarArticulo.push("Ingrese precio de venta");
                }

                if(this.stock==''){
                    this.errorMostrarArticulo.push("Ingrese Stock")
                }
                if(this.errorMostrarArticulo.length){
                    this.errorArticulo=1;
                }
                return this.errorArticulo;     
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.idcategoria=0;
                this.nombre_categoria='';
                this.codigo=0;
                this.nombre='';
                this.precio_venta=0;
                this.stock=0;
                this.descripcion='';
                this.errorArticulo=0;
                this.descripcion='';

            },
            abrirModal(modelo, accion, data = []) {
                

                switch (modelo){
                    case 'articulo':
                    {
                        switch (accion){
                            case'registrar':
                            {
                                this.modal=1;
                                this.tituloModal='Registrar Articulo';
                                this.idcategoria=0;
                                this.nombre_categoria='';
                                this.codigo=0;
                                this.nombre='';
                                this.precio_venta='';
                                this.stock=0;
                                this.descripcion='';
                                this.tipoAccion = 1;
                                break;

                            }
                            case "actualizar":
                            {
                                //mostrando por consola todo el objeto para verificar que estamos bien
                                 //console.log(data);
                                 this.modal=1;
                                 this.tituloModal='Actualizar Articulo';
                                 this.tipoAccion=2;
                                 this.articulo_id=data['id'];
                                 this.idcategoria=data['idcategoria'];
                                 this.codigo=data['codigo'];
                                 this.nombre=data['nombre'];
                                 this.nombre_categoria=data['nombre_categoria']
                                 this.stock=data['stock'];
                                 this.precio_venta=data['precio_venta'];
                                 this.descripcion=data['descripcion'];
                                 break;      
                            }

                        }
                    }

                    this.selectCategoria();
                }

            },

    },
        mounted(){
                    this.listarArticulo(1,this.buscar,this.nombre);
                    
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
</style>