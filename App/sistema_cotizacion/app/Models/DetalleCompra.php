<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compra';
    protected $primaryKey = 'id_detalle_compra';
    public $timestamps = false;

    protected $fillable = [
        'id_compra',
        'id_material',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id');
    }
}
