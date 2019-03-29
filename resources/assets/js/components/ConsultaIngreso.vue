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
                                        <button type="button" @click="verIngreso(ingreso.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                        </button>


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
                    <!-- Fin Listado-->
                </template>

                <!-- Ver Ingreso-->
                <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor</label>
                                    <p v-text="proveedor"></p>

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
                                        <td>
                                            {{detalle.precio*detalle.cantidad}}

                                        </td>
                                    </tr>

                                    <tr style="background-color:#CEECF5;">
                                        <td colspan="3" align="right"><strong>Total Parcial:</strong></td>
                                        <td>$ {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>


                                    </tr>
                                    <tr style="background-color:#CEECF5;">
                                        <td colspan="3" align="right"><strong>Total Impuesto:</strong></td>
                                        <td>$ {{totalImpuesto=((total*impuesto)).toFixed(2)}}</td>


                                    </tr>
                                    <tr style="background-color:#CEECF5;">
                                        <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                        <td>$ {{total}}</td>


                                    </tr>

                                    </tbody>
                                    <tbody v-else>
                                    <tr >
                                        <td colspan="4">
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


    </main>
</template>
<script>
    import vSelect from 'vue-select';

    export default {
        data() {
            return {
                ingreso_id:0,
                idproveedor:0,
                proveedor:'',
                nombre: '',
                tipo_comprobante:'BOLETA',
                serie_comprobante:'',
                num_comprobante:'',
                impuesto:0.18,
                total:0.0,
                totalImpuesto:0.0,
                totalParcial:0.0,
                arrayIngreso:[],
                arrayDetalle:[],
                arrayProveedor:[],
                listado:1,
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
                buscar:'',
                buscarModal:'',
                criterioModal:'nombre',
                arrayArticulo:[],
                idarticulo:0,
                codigo:'',
                articulo:'',
                precio:0,
                cantidad:0,

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
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad);
                }
                return resultado;
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

            //el page recibe el numero de pagina que queremos mostrar
            cambiarPagina(page,buscar,criterio){
                let me=this;

                //Actualiza la pagina Actual
                me.pagination.current_page=page;
                //Envia la peticion para visualizar la data de esa pagina
                me.listarIngreso(page,buscar,criterio);
            }
            ,
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

            verIngreso(ingresoid){
                let me=this;
                me.listado=2;


                //obtener datos del ingreso
                var arrayIngresoT=[];


                axios.get('/ingreso/obtenerCabecera?id='+ingresoid).then(function(response){
                    var respuesta=response.data;
                    arrayIngresoT=respuesta.ingreso;
                    me.proveedor=arrayIngresoT[0]['nombre'];
                    me.tipo_comprobante=arrayIngresoT[0]['tipo_comprobante'];
                    me.serie_comprobante=arrayIngresoT[0]['serie_comprobante'];
                    me.num_comprobante=arrayIngresoT[0]['num_comprobante'];
                    me.impuesto=arrayIngresoT[0]['impuesto'];
                    me.total=arrayIngresoT[0]['total'];






                }).catch(function(error){
                    console.log(error)

                });

                //obtener los datos de los detalles

                axios.get('/ingreso/obtenerDetalles?id='+ingresoid).then(function(response){
                    var respuesta=response.data;
                    me.arrayDetalle=respuesta.detalles;





                }).catch(function(error){
                    console.log(error)

                });



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

    @media(min-width: 600px){
        .btnagregar{
            margin-top: 2rem;
        }
    }
</style>