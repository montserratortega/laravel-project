<template>
   <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="/">BACKEND - SISTEMA DE COMPRAS - VENTAS</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">

                       <h2>Listado de Compras</h2><br/>

                        <button class="btn btn-primary btn-lg" type="button" @click="abrirModal('compra','registrar')">
                            <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Agregar Compra
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="num_compra">Número de Compra</option>
                                      <option value="fecha_compra">Fecha de Compra</option>
                                    </select>
                                    <input type="text"  @keyup.enter="listarCompra(1,buscar,criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                                    <button type="submit"  @click="listarCompra(1,buscar,criterio);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr class="bg-primary">

                                    <th>Ver Detalle</th>
                                    <th>Fecha Compra</th>
                                    <th>Número Compra</th>
                                    <th>Proveedor</th>
                                    <th>Comprador</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Cambiar Estado</th>
                                    <th>Descargar Reporte</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="compra in arrayCompra" :key="compra.id">

                                        <td>
                                             <!--compra.id-->
                                            <button type="button" @click="verCompra(compra.id)" class="btn btn-warning btn-sm">
                                            <i class="fa fa-eye fa-2x"></i> Ver detalle
                                            </button> &nbsp;

                                        </td>
                                        <td v-text="compra.fecha_compra"></td>
                                        <td v-text="compra.num_compra"></td>
                                        <td v-text="compra.nombre"></td>
                                        <td v-text="compra.usuario"></td>
                                        <td v-text="compra.total"></td>
                                        <td>

                                             <button type="button" v-if="compra.estado=='Registrado'" class="btn btn-success btn-sm">
                                                <i class="fa fa-check fa-2x"></i> Registrado
                                            </button>

                                            <button type="button" v-else class="btn btn-danger btn-sm">
                                                 <i class="fa fa-times fa-2x"></i> Anulado
                                            </button>

                                        </td>

                                         <td>
                                            <template v-if="compra.estado=='Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarCompra(compra.id)">
                                                    <i class="fa fa-times fa-2x"></i> Anular Compra
                                                </button>
                                            </template>

                                            <template v-else>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                     <i class="fa fa-check fa-2x"></i> Cambiado
                                                </button>
                                            </template>
                                        </td>

                                        <td>

                                             <button type="button" @click="pdfCompra(compra.id)" class="btn btn-info btn-sm">
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

                            <div v-show="errorUsuario" class="form-group row div-error">

                                <div class="text-center text-error">

                                    <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error"></div>

                                </div>

                            </div>


                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del usuario">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Rol</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idrol">
                                            <option value="0">Seleccione un rol</option>
                                            <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre">
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Usuario</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="usuario" class="form-control" placeholder="Usuario">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="Password de Acceso">
                                    </div>
                                </div>


                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
                            <button type="button" @click="registrarUsuario()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>
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

    export default {
        data(){

            return {

                compra_id:0,
                idproveedor:0,
                proveedor:'',
                nombre : '',
                num_compra : '',
                total:0.0,
                //subTotal: 0.0,
                arrayCompra:[],
                arrayProveedor:[],
                arrayDetalle:[],
                listado:1,
                modal:0,
                tituloModal:'',
                tipoAccion:0,
                errorCompra:0,
                errorMostrarMsjCompra:[],
                pagination:{

                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,

                },
                offset:3,
                criterio:'num_compra',
                buscar:''
            }

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

        methods:{

           listarCompra(page,buscar,criterio){

               let me=this;

               var url= '/compra?page=' + page + '&buscar='+ buscar + '&criterio='+criterio;

               axios.get(url).then(function (response) {
                    // handle success
                    //console.log(response);
                    var respuesta = response.data;
                    me.arrayCompra=respuesta.compras.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
           },

           selectRol(){

               let me=this;
               var url= '/rol/selectRol';
               axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRol=respuesta.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });

           },

           cambiarPagina(page,buscar,criterio){

              let me = this;

              //Actualiza  la pagina actual

               me.pagination.current_page=page;

               me.listarCompra(page,buscar,criterio);

           },

           registrarUsuario(){

               if(this.validarUsuario()){

                   return;
               }

               let me=this;

               var body = {
                    'nombre': this.nombre,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'usuario' : this.usuario,
                    'password' : this.password,
                    'idrol' : this.idrol

               }

               console.log('Body: ', body)

               axios.post('/user/registrar', body).then(function (response) {
                    // handle success
                    //console.log(response);
                    me.cerrarModal();
                    me.listarUsuario(1,'','nombre');

                }).catch(function (error) {
                    // handle error
                    console.log(error);
                });

           },

            actualizarUsuario(){

                if(this.validarUsuario()){

                   return;
               }

               let me=this;

               var body = {
                    'nombre': this.nombre,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'usuario' : this.usuario,
                    'password' : this.password,
                    'idrol' : this.idrol,
                    'id' : this.usuario_id
               }

               console.log('Body: ', body)

               axios.put('/user/actualizar', body).then(function (response) {
                    // handle success
                    console.log(response);
                    me.cerrarModal();
                    me.listarUsuario(1,'','nombre');

                }).catch(function (error) {
                    // handle error
                    console.log(error);
                });

            },

            validarUsuario(){

                this.errorUsuario=0;
                this.errorMostrarMsjUsuario =[];

                if (!this.nombre) this.errorMostrarMsjUsuario.push("(*)El nombre no puede estar vacío.");
                if (!this.usuario) this.errorMostrarMsjUsuario.push("(*)El nombre del usuario no puede estar vacío.");
                if (!this.password) this.errorMostrarMsjUsuario.push("(*)El password no puede estar vacío.");
                if (this.idrol==0) this.errorMostrarMsjUsuario.push("(*)Debes seleccionar un rol para el usuario.");

                if (this.errorMostrarMsjUsuario.length) this.errorUsuario = 1;

                return this.errorUsuario;
            },

           cerrarModal(){

                this.modal=0;
                this.tituloModal="";
                this.nombre="";
                this.direccion="";
                this.telefono="";
                this.email="";
                this.usuario="";
                this.password="";
                this.idrol=0;
                this.errorUsuario=0;

           },

           abrirModal(modelo,accion,data=[]){

                this.selectRol();

                 switch(modelo){

                    case "usuario":

                    {

                        switch(accion){

                            case "registrar":

                                {

                                    this.modal = 1;
                                    this.tituloModal = "Agregar Usuario";
                                    this.nombre= "";
                                    this.direccion="";
                                    this.telefono="";
                                    this.email="";
                                    this.usuario = "";
                                    this.password="";
                                    this.idrol=0;
                                    this.tipoAccion = 1;
                                    break;

                                }

                                case "actualizar":

                                {
                                    //console.log(data);
                                    this.modal=1;
                                    this.tituloModal="Editar Usuario";
                                    this.tipoAccion=2;
                                    this.usuario_id=data["id"];
                                    this.nombre = data["nombre"];
                                    this.direccion = data["direccion"];
                                    this.telefono = data["telefono"];
                                    this.email = data["email"];
                                    this.usuario = data["usuario"];
                                    this.password = data["password"];
                                    this.idrol = data["idrol"];
                                    break;
                                }

                        }


                    }

                }


           },

           desactivarUsuario(id){

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Estas seguro de desactivar el usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
                cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {

                    let me=this;

                axios.put('/user/desactivar',{

                    'id':id

                }).then(function (response) {
                     //console.log(response);
                     me.listarUsuario(1,'','nombre');

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

            activarUsuario(id){

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Estas seguro de activar el usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
                cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {

                    let me=this;

                axios.put('/user/activar',{

                    'id':id

                }).then(function (response) {
                     //console.log(response);
                     me.listarUsuario(1,'','nombre');

                     swalWithBootstrapButtons.fire(
                    'Activado',
                    'El registro del usuario ha sido activado',
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
            this.listarCompra(1,this.buscar,this.criterio);
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

</style>
