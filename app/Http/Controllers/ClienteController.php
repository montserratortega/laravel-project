<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    //

    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar= $request->buscar;
        $criterio= $request->criterio;

        if($buscar==''){

            $clientes= Cliente::orderBy('id','desc')->paginate(3);
        } else{

            $clientes= Cliente::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(3);
        }



        return[

                'pagination' => [
                'total'         => $clientes->total(),
                'current_page'  => $clientes->currentPage(),
                'per_page'      => $clientes->perPage(),
                'last_page'     => $clientes->lastPage(),
                'from'          => $clientes->firstItem(),
                'to'            => $clientes->lastItem(),

                ],

            'clientes'  =>$clientes

            ];


    }

    public function store(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $cliente= new Cliente();
        $cliente->nombre = $request->nombre;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->direccion = $request->direccion;
        $cliente->save();
    }

    public function update(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $cliente= Cliente::findOrFail($request->id);
        $cliente->nombre = $request->nombre;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->direccion = $request->direccion;
        $cliente->save();
    }

}
