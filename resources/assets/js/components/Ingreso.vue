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
                    <i class="fa fa-align-justify"></i>Ingreso
                    <button type="button" @click="abrirModal('ingreso','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>

                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="comprobante">Tipo Comprobante</option>
                                    <option value="numero_comprobante">Numero Comprobante</option>
                                    <option value="fecha_hora">Fecha-Hora</option>

                                </select>
                                <input type="text" v-model="buscar" v-on:keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" v-on:click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Proveedor</th>
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
                            <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                <td>
                                    <button type="button" @click="abrirModal('ingreso','actualizar',ingreso)" class="btn btn-success btn-sm">
                                        <i class="icon-eye"></i>
                                    </button>&nbsp;
                                    <template v-if="ingreso.estado=='registrado'" >
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                            <i class="icon-trash"></i>
                                        </button>

                                    </template>


                                </td>
                                <td v-text="ingreso.usuario"></td>
                                <td v-text="ingreso.nombre"></td>
                                <td v-text="ingreso.tipo_comprobante"></td>
                                <td v-text="ingreso.serie_comprobante"></td>
                                <td v-text="ingreso.num_comprobante"></td>
                                <td v-text="ingreso.fecha_hora"></td>
                                <td v-text="ingreso.total"></td>
                                <td v-text="ingreso.impuesto"></td>
                                <td v-text="ingreso.estado"></td>


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
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona">
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo Doc</label>
                                <div class="col-md-9">
                                   <select v-model="tipo_documento" class="form-control">
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="PASS">PASS</option>
                                    </select>  
                                </div>
                                   
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Numero Doc</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="numero_documento" class="form-control" placeholder="Número Documento">
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" class="form-control" placeholder="Telefono">
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Email</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="email" class="form-control" placeholder="Email">
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Rol(*)</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="idrol">
                                        <option value="0">Seleccione Rol</option>
                                        <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre">

                                        </option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Usuario(*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="usuario" class="form-control" placeholder="Usuario">

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Password(*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="password" class="form-control" placeholder="Password">

                                </div>
                            </div>


                            
                            <div v-show="errorPersona==1" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarPersona" :key="error" v-text="error">


                                    </div>
                                </div>    
                            </div>    
                        </form>
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
    export default {
        data() {
            return {
                ingreso_id:0,
                idproveedor:0,
                nombre: '',
                tipo_comprobante:'Boleta',
                serie_comprobante:'',
                num_comprobante:'',
                impuesto:0.18,
                total:0.0,
                arrayIngreso:[],
                arrayDetalle:[],
                modal: 0,
                tituloModal: '',
                tipoAccion : 0,
                errorIngreso: 0,
                errorMostrarIngreso:[],
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
                buscar:''
            }
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
            listarIngreso(page,buscar,criterio) {
                let me = this;
                var url='/ingreso?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;


                    
                    // handle success
                    me.arrayIngreso = respuesta.ingresos.data;



                    me.pagination=respuesta.pagination;
                })
                    .catch(function (error) {
                        // handle error
                        
                        console.log(error);
                    });

            },
            selectRol(){
                let me = this;
                var url='/rol/selectRol';
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    // handle success
                    me.arrayRol = respuesta.roles

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
                me.listarIngreso(page,buscar,criterio);
                } 



            ,
            registrarPersona() {

                //si la validacion devuelve true o 1 , significa que hubo error    
                if(this.validarPersona()){
                    return false
                }
                let me=this;
                //usamos el verbo post en vez de get
                //primer parametro la ruta del controlador que registra
                //segundo parametro enviaremos los valores q recibira el controlador
                axios.post('/user/registrar',
                {'nombre':this.nombre,
                 'tipo_doc':this.tipo_documento,
                 'num_documento':this.numero_documento,
                'direccion':this.direccion,
                'telefono':this.telefono,
                'email':this.email,
                'usuario':this.usuario,
                'password':this.password,
                'idrol':this.idrol

                }).then(function(){
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
        

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

            
            validarPersona(){
                this.errorPersona=0;
                this.errorMostrarPersona=[];
                //si el nombre esta vacio, agrega el msj de error
                if(this.nombre==''){
                    this.errorMostrarPersona.push("el nombre  no puede estar vacio");

                }
                if(this.usuario==''){
                    this.errorMostrarPersona.push("el nombre de usuario  no puede estar vacio");

                }
                if(this.password==''){
                    this.errorMostrarPersona.push("el password  no puede estar vacio");

                }
                if(this.idrol==0){
                    this.errorMostrarPersona.push("debes seleccionar un rol para el usuario");

                }
                
                if(this.errorMostrarPersona.length){
                    this.errorPersona=1;
                }
                return this.errorPersona;     
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.tipo_documento='DNI';
                this.numero_documento='';
                this.direccion='';
                this.telefono='';
                this.email='';
                this.telefono_contacto='';
                this.usuario='';
                this.password='';
                this.idrol=0;

                this.errorPersona=0;
                

            },
            abrirModal(modelo, accion, data = []) {
                this.selectRol();

                switch (modelo){
                    case 'persona':
                    {
                        switch (accion){
                            case'registrar':
                            {
                                this.modal=1;
                                this.tituloModal='Registrar Usuario';
                                this.nombre='';
                                this.tipo_documento='DNI';
                                this.numero_documento='';
                                this.direccion='';
                                this.telefono='';
                                this.email='';
                                this.usuario='';
                                this.password='';
                                this.idrol=0;
                                this.tipoAccion = 1;
                                break;

                            }
                            case "actualizar":
                            {
                                //mostrando por consola todo el objeto para verificar que estamos bien
                                 //console.log(data);
                                 this.modal=1;
                                 this.tituloModal='Actualizar Usuario';
                                 this.tipoAccion=2;
                                 this.persona_id=data.id;
                                 this.nombre=data.nombre;
                                 this.tipo_documento=data.tipo_documento;
                                 this.numero_documento=data.num_documento;
                                 this.direccion=data.direccion;
                                 this.telefono=data.telefono;
                                 this.email=data.email;
                                 this.usuario=data.usuario;
                                 this.password=data.password;
                                 this.idrol=data.idrol;
                                 break;      
                            }

                        }
                    }
                }

            },
            desactivarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de desactivar este usuario?',

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
                        axios.put('/user/desactivar',
                            {'id':id
                            }).then(function(){

                            me.listarPersona(1,'','nombre');
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
            activarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de activar este usuario?',

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
                        axios.put('/user/activar',
                            {'id':id
                            }).then(function(){

                            me.listarPersona(1,'','nombre');
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

            

    },
        mounted(){
                    this.listarIngreso(1,this.buscar,this.nombre);
                    
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