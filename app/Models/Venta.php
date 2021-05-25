<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    //use HasFactory;
    protected $table = 'ventas';
    protected $fillable =[
        'idcliente',
        'idusuario',
        'num_venta',
        'fecha_venta',
        'total',
        'estado'
    ];
}
