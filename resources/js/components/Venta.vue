<template>
   <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="/">BACKEND - SISTEMA DE COMPRAS - VENTAS</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">

                    <template v-if="listado==1">
                    <div class="card-header">

                       <h2>Listado de Ventas</h2><br/>

                        <button class="btn btn-primary btn-lg" type="button" @click="mostrarDetalle()">
                            <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Nueva Venta
                        </button>
                    </div>

                    <!--Listado-->

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="num_venta">Número de Venta</option>
                                      <option value="fecha_venta">Fecha de Venta</option>
                                    </select>
                                    <input type="text"  @keyup.enter="listarVenta(1,buscar,criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                                    <button type="submit"  @click="listarVenta(1,buscar,criterio);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr class="bg-primary">

                                    <th>Ver Detalle</th>
                                    <th>Fecha Venta</th>
                                    <th>Número Venta</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Cambiar Estado</th>
                                    <th>Descargar Reporte</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="venta in arrayVenta" :key="venta.id">

                                        <td>
                                             <!--venta.id-->
                                            <button type="button" @click="verVenta(venta.id)" class="btn btn-warning btn-sm">
                                            <i class="fa fa-eye fa-2x"></i> Ver detalle
                                            </button> &nbsp;

                                        </td>
                                        <td v-text="venta.fecha_venta"></td>
                                        <td v-text="venta.num_venta"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.total"></td>
                                        <td>

                                             <button type="button" v-if="venta.estado=='Registrado'" class="btn btn-success btn-sm">
                                                <i class="fa fa-check fa-2x"></i> Registrado
                                            </button>

                                            <button type="button" v-else class="btn btn-danger btn-sm">
                                                 <i class="fa fa-times fa-2x"></i> Anulado
                                            </button>

                                        </td>

                                         <td>
                                            <template v-if="venta.estado=='Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                    <i class="fa fa-times fa-2x"></i> Anular Venta
                                                </button>
                                            </template>

                                            <template v-else>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                     <i class="fa fa-check fa-2x"></i> Cambiado
                                                </button>
                                            </template>
                                        </td>

                                        <td>

                                             <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                            <i class="fa fa-file fa-2x"></i> Descargar PDF
                                            </button> &nbsp;
                                        </td>


                                </tr>

                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-f="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Anterior</a>
                                </li>

                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>


                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Siguiente</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <!--FIN Listado-->

                    <!-- Detalle-->

                    <template v-else-if="listado==0">

                    <span><strong>(*) Campo obligatorio</strong></span><br/>

                    <h3 class="text-center">LLenar el formulario</h3>

                    <div class="card-body">

                        <div class="form-group row border">

                             <div class="col-md-8">
                                <div class="form-group">
                                    <label class="text-uppercase"><strong>Número Venta(*)</strong></label>
                                    <input type="text" class="form-control" v-model="num_venta" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="text-uppercase"><strong>Cliente(*)</strong></label>
                                    <v-select
                                        :on-search="selectCliente"
                                        label="nombre"
                                        :options="arrayCliente"
                                        placeholder="Buscar Clientes..."
                                        :onChange="getDatosCliente"
                                    >

                                    </v-select>
                                </div>
                            </div>

                        </div>

                        <div class="form-group row">

                            <div class="col-md-12">
                                    <div v-show="errorVenta" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>


                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Producto <span class="text-error" v-show="idproducto==0">(*Ingrese código del producto)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarProducto()" placeholder="Ingrese código">
                                        <button @click="abrirModal()" class="btn btn-primary">

                                           <i class="fa fa-plus"></i>&nbsp;Agregar Productos

                                        </button>
                                        <input type="text" readonly class="form-control" v-model="producto">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span class="text-error" v-show="precio==0">(*Ingrese un valor)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span class="text-error" v-show="cantidad==0">(*Ingrese un valor)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento </label>
                                    <input type="number" value="0" class="form-control" v-model="descuento">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-primary form-control btnagregar"><i class="fa fa-plus fa-2x"></i> Agregar detalle</button>
                                </div>
                            </div>
                        </div>

                        <br/><br/>

                        <div class="form-group row border">

                            <h3>Lista de Ventas</h3>

                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr class="bg-success">
                                            <th>Eliminar</th>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-times fa-2x"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.producto">
                                            </td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.cantidad" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.descuento" type="number" class="form-control">
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: grey;">
                                            <td colspan="5" align="right"><strong>Total:</strong></td>
                                            <td><strong> MXN {{total=calcularTotal}}</strong></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No se han agregado productos
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" @click="ocultarDetalle()"><i class="fa fa-times fa-2x"></i> Cerrar</button>
                                <button type="button" class="btn btn-success" @click="registrarVenta()"><i class="fa fa-save fa-2x"></i> Registrar Venta</button>
                            </div>
                        </div>
                    </div>
                    </template>

                    <!-- Fin Detalle-->

                    <!-- Ver Compra -->

                    <template v-else-if="listado==2">

                    <h2 class="text-center">Detalle de Compra</h2><br/>

                       <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-uppercase"><strong>Proveedor</strong></label>
                                        <p v-text="proveedor"></p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-uppercase"><strong>Número Compra</strong></label>
                                        <p v-text="num_compra"></p>
                                    </div>
                                </div>

                        </div>


                    <div class="form-group row border">
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr class="bg-success">
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                        <td v-text="detalle.producto">
                                        </td>
                                        <td v-text="detalle.precio">
                                        </td>
                                        <td v-text="detalle.cantidad">
                                        </td>
                                        <td>
                                            {{detalle.precio*detalle.cantidad}}
                                        </td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="3" align="right"><strong>Sub-Total:</strong></td>
                                        <td><strong>USD$ {{subTotal=(total-subTotalImpuesto).toFixed(2)}}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="3" align="right"><strong>Impuesto:</strong></td>
                                        <td><strong>USD$ {{subTotalImpuesto=((total*impuesto)).toFixed(2)}}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="3" align="right"><strong>Total:</strong></td>
                                        <td><strong>USD$ {{total}}</strong></td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="4">
                                            No se han agregado productos
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" @click="ocultarDetalle()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>

                        </div>
                    </div>
                 </div>


                   </template>

                  <!-- fin ver compra-->


                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" :class="{'mostrar':modal}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" @click="cerrarModal()" class="close" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body">

                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioP">
                                      <option value="nombre">Producto</option>
                                      <option value="codigo">Código</option>

                                    </select>
                                    <input type="text"  @keyup.enter="listarProducto(buscarP,criterioP);" v-model="buscarP" class="form-control" placeholder="Buscar texto">
                                    <button type="submit"  @click="listarProducto(buscarP,criterioP);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr class="bg-primary">

                                            <th>Categoria</th>
                                            <th>Producto</th>
                                            <th>Codigo</th>
                                            <th>Precio Venta</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="producto in arrayProducto" :key="producto.id">

                                            <td v-text="producto.nombre_categoria"></td>
                                            <td v-text="producto.nombre"></td>
                                            <td v-text="producto.codigo"></td>
                                            <td v-text="producto.precio_venta"></td>
                                            <td v-text="producto.stock"></td>

                                            <td>
                                                <button type="button" class="btn btn-success btn-md" v-if="producto.condicion">
                                                    <i class="fa fa-check fa-2x"></i> Activo
                                                </button>

                                                <button type="button" class="btn btn-danger btn-md" v-else>
                                                    <i class="fa fa-check fa-2x"></i> Desactivado
                                                </button>

                                            </td>

                                            <td>

                                                <button type="button" @click="agregarDetalleModal(producto)" class="btn btn-primary">
                                                <i class="fa fa-plus fa-2x"></i> Agregar
                                                </button>

                                            </td>
                                        </tr>

                                    </tbody>
                                </table>


                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
                            <button type="button" @click="registrarCompra()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>
                            <button type="button" @click="actualizarUsuario()" v-if="tipoAccion==2" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Actualizar</button>

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
        data(){

            return {

                venta_id:0,
                idcliente:0,
                cliente:'',
                num_venta : '',
                total:0.0,
                //subTotal: 0.0,
                arrayVenta:[],
                arrayCliente:[],
                arrayDetalle:[],
                listado:1,
                modal:0,
                tituloModal:'',
                tipoAccion:0,
                errorVenta:0,
                errorMostrarMsjVenta:[],
                pagination:{

                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,

                },
                offset:3,
                criterio:'num_venta',
                buscar:'',
                criterioP:'nombre',
                buscarP: '',
                arrayProducto: [],
                idproducto: 0,
                codigo: '',
                producto: '',
                precio: 0,
                cantidad: 0,
                descuento:0
            }

        },

        components: {
            vSelect
        },

        computed:{

            isActived: function(){

              return this.pagination.current_page;

            },

             //calcula los elementos de la paginacion
            pagesNumber: function(){

                if(!this.pagination.to){

                    return[];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){

                   from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){

                   to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){

                   pagesArray.push(from);
                   from++;
                }
                return pagesArray;


            }

        },

        calcularTotal: function(){
            var resultado=0.0;
            for(var i=0;i<this.arrayDetalle.length;i++){
                resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad)
            }
            return resultado;

        },

        methods:{

           listarVenta(page,buscar,criterio){

               let me=this;

               var url= '/venta?page=' + page + '&buscar='+ buscar + '&criterio='+criterio;

               axios.get(url).then(function (response) {
                    // handle success
                    //console.log(response);
                    var respuesta = response.data;
                    me.arrayVenta=respuesta.ventas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
           },

           selectProveedor(search,loading){
                let me=this;
                loading(true)

                var url= '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayProveedor=respuesta.proveedores;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;
            },

            buscarProducto(){
                let me=this;
                var url= '/producto/buscarProducto?filtro=' + me.codigo;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos;

                    if (me.arrayProducto.length>0){
                        me.producto=me.arrayProducto[0]['nombre'];
                        me.idproducto=me.arrayProducto[0]['id'];
                    }
                    else{
                        me.producto='No existe el producto';
                        me.idproducto=0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

           cambiarPagina(page,buscar,criterio){

              let me = this;

              //Actualiza  la pagina actual

               me.pagination.current_page=page;

               me.listarVenta(page,buscar,criterio);

           },

           encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idproducto==id){
                        sw=true;
                    }
                }
                return sw;
            },

            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index, 1);

            },

           agregarDetalle(){
                let me=this;
                if(me.idproducto==0 || me.cantidad==0 || me.precio==0){
                }

                else{
                    if(me.encuentra(me.idproducto)){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese producto ya fue agregado',
                            })
                    }
                    else{
                       me.arrayDetalle.push({
                            idproducto: me.idproducto,
                            producto: me.producto,
                            cantidad: me.cantidad,
                            precio: me.precio
                        });
                        me.codigo="";
                        me.idproducto=0;
                        me.producto="";
                        me.cantidad=0;
                        me.precio=0;
                    }

                }


           },

           agregarDetalleModal(data=[]){
               let me=this;

                if(me.encuentra(data['id'])){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese producto ya fue agregado',
                            })
                    }
                    else{
                       me.arrayDetalle.push({
                            idproducto: data['id'],
                            producto: data['nombre'],
                            cantidad: 1,
                            precio: 1
                        });
                    }

           },

           listarProducto (buscar,criterio){
                let me=this;
                var url= '/producto/listarProducto?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

           registrarCompra(){
               if(this.validarCompra()){

                   return;
               }

              let me = this;

                axios.post('/compra/registrar',{
                    'idproveedor': this.idproveedor,
                    'num_compra' : this.num_compra,
                    'total' : this.total,
                    'data': this.arrayDetalle

                }).then(function (response) {
                    me.listado=1;
                    me.listarCompra(1,'','num_compra');
                    me.idproveedor=0;
                    me.num_compra='';
                    me.total=0.0;
                    me.idproducto=0;
                    me.producto='';
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle=[];

                }).catch(function (error) {
                    console.log(error);
                });


           },

            validarCompra(){

                this.errorCompra=0;
                this.errorMostrarMsjCompra =[];

                if (this.idproveedor==0) this.errorMostrarMsjCompra.push("(*)Debes seleccionar un proveedor.");
                if (!this.num_compra) this.errorMostrarMsjCompra.push("(*)Debes de ingresar numero de compra");
                if (this.arrayDetalle.length<=0) this.errorMostrarMsjCompra.push("(*)Ingrese.");

                if (this.errorMostrarMsjCompra.length) this.errorCompra = 1;

                return this.errorCompra;
            },

            mostrarDetalle(){

                let me = this;

                    me.listado=0;
                    me.idproveedor=0;
                    me.num_compra='';
                    me.total=0.0;
                    me.idproducto=0;
                    me.producto='';
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle=[];
            },

            ocultarDetalle(){

                this.listado=1;
            },

            verCompra(){

                let me=this;
                me.listado=2;

            },

           cerrarModal(){

                this.modal=0;
                this.tituloModal="";

           },

           abrirModal(){

               this.arrayProducto=[];
               this.modal = 1;
               this.tituloModal = "Selecciona uno o varios productos";

           },

           desactivarCompra(id){
               const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Estas seguro de desactivar la compra?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
                cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {

                    let me=this;

                axios.put('/compra/desactivar',{

                    'id':id

                }).then(function (response) {
                     //console.log(response);
                     me.listarCompra(1,'','nombre');

                     swalWithBootstrapButtons.fire(
                    'Desactivado',
                    'El registro del usuario ha sido desactivado',
                    'success'
                    )

                }).catch(function (error) {
                    console.log(error);
                });


                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {

                }
                })
            },

        },

        mounted() {
            //console.log('Component mounted.')
            this.listarVenta(1,this.buscar,this.criterio);
        }
    }
</script>

<style>

     .modal-content{

      width:100% !important;
      position:absolute !important;
  }

  .mostrar{

      display:list-item !important;
      opacity:1 !important;
      position:absolute !important;
      background-color:#3c29297a !important;
  }

   .div-error{

      display:flex;
      justify-content:center;
  }

  .text-error{

      color:red !important;
      font-weight:bold;
      font-size:20px;
  }

  @media (min-width: 600px) {

      .btnagregar {
          margin-top: 2rem;
      }
  }

</style>
