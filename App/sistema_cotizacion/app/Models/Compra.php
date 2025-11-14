<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';
    protected $primaryKey = 'id_compra';
    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'numero_documento',
        'fecha',
        'total'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra', 'id_compra');
    }
}
