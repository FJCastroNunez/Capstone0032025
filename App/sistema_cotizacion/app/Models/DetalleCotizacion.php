<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    protected $table = 'detalle_cotizacion';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_detalle',
        'id_cotizacion',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'descuento',
        'total',
        'created_at',
        'updated_at'
    ];

    public function id_cotizacion()
    {
        return $this->hasMany(Cotizacion::class, 'id_cotizacion');
    }

    public function id_producto()
    {
        return $this->hasMany(Producto::class, 'id_producto');
    }
}
