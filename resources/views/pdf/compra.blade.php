<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de compra</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif;
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }


        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }

        #encabezado{
        text-align: center;
        margin-left: 35%;
        margin-right: 35%;
        font-size: 15px;
        }

        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        background:#33AFFF;
        }

        section{
        clear: left;
        }

        #cliente{
        text-align: left;
        }

        #faproveedor{
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #faproveedor thead{
        padding: 20px;
        background:#33AFFF;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;
        }

        #faccomprador{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #faccomprador thead{
        padding: 20px;
        background: #33AFFF;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
        }

        #facproducto{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facproducto thead{
        padding: 20px;
        background: #33AFFF;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
        }


    </style>
    <body>
        @foreach ($compra as $v)
        <header>
            <!--<div id="logo">
                <img src="img/logo.png" alt="" id="imagen">
            </div>-->

             <div>

                <table id="datos">
                    <thead>
                        <tr>
                            <th id="">DATOS DEL PROVEEDOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><p id="proveedor">Nombre: {{$v->nombre}}<br>
                            Direcci??n: {{$v->direccion}}<br>
                            Tel??fono: {{$v->telefono}}<br>
                            Email: {{$v->email}}</</p></th>
                            COMPRA: {{$v->num_compra}}<br>
                        </tr>
                    </tbody>
                </table>
            </div>


        </header>
        <br>

        @endforeach
        <br>
        <section>
            <div>
                <table id="faccomprador">
                    <thead>
                        <tr id="fv">
                            <th>COMPRADOR</th>
                            <th>FECHA COMPRA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$v->usuario}}</td>
                            <td>{{$v->created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
            <div>
                <table id="facproducto">
                    <thead>
                        <tr id="fa">
                            <th>CANTIDAD</th>
                            <th>PRODUCTO</th>
                            <th>PRECIO COMPRA</th>
                            <th>PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalles as $det)
                        <tr>
                            <td>{{$det->cantidad}}</td>
                            <td>{{$det->producto}}</td>
                            <td>{{$det->precio}}</td>
                            <td>{{$det->cantidad*$det->precio}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @foreach ($compra as $v)
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <td>$ {{$v->total}}</td>
                        </tr>
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
             <!--puedes poner un mensaje aqui-->
             <div id="datos">
                <p id="encabezado">
                    <b></b>
                </p>
            </div>
        </footer>
    </body>
</html>
