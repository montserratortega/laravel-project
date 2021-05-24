<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    //use HasFactory;

    protected $table = 'compras';

    protected $fillable = [
        'idproveedor',
        'idusuario',
        'num_compra',
        'fecha_compra',
        'total',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Models\Proveedor');
    }
}
