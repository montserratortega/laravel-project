<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Compra;
use App\Models\DetalleCompra;

class CompraController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $compras = Compra::join('proveedores','compras.idproveedor','=','proveedores.id')
            ->join('users','compras.idusuario','=','users.id')
            ->select('compras.id','compras.num_compra','compras.fecha_compra','compras.total',
            'compras.estado','proveedores.nombre','users.usuario')
            ->orderBy('compras.id', 'desc')->paginate(3);
        }
        else{
            $compras = Compra::join('proveedores','compras.idproveedor','=','proveedores.id')
            ->join('users','compras.idusuario','=','users.id')
            ->select('compras.id','compras.num_compra','compras.fecha_compra','compras.total',
            'compras.estado','proveedores.nombre','users.usuario')
            ->where('compras.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('compras.id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total'        => $compras->total(),
                'current_page' => $compras->currentPage(),
                'per_page'     => $compras->perPage(),
                'last_page'    => $compras->lastPage(),
                'from'         => $compras->firstItem(),
                'to'           => $compras->lastItem(),
            ],
            'compras' => $compras
        ];
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $mytime= Carbon::now('America/Mexico_City');

            $compra = new Compra();
            $compra->idproveedor = $request->idproveedor;
            $compra->idusuario = \Auth::user()->id;
            $compra->num_compra = $request->num_compra;
            $compra->fecha_compra = $mytime->toDateString();
            $compra->total = $request->total;
            $compra->estado = 'Registrado';
            $compra->save();

            //Array de detalles
            $detalles = $request->data;


            //Recorro todos los elementos

            foreach($detalles as $a=>$det)
            {
                $detalle = new DetalleCompra();
                /*enviamos valores a las propiedades del objeto detalle*/
                /*al idcompra del objeto detalle le envio el id del objeto compra, que es el objeto que se ingresó en la tabla compras de la bd*/
                $detalle->idcompra = $compra->id;
                $detalle->idproducto = $det['idproducto'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();
            }


            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $compra = Compra::findOrFail($request->id);
        $compra->estado = 'Anulado';
        $compra->save();
    }
}