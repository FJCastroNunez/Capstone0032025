<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    protected $table = 'detalle_cotizacion';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_cotizacion',
        'id_producto',
        'id_material',
        'ancho',
        'alto',
        'espesor',
        'valor_m2',
        'subtotal',
        'created_at',
        'updated_at'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id');
    }

    // 🔹 Relación inversa con Cotización
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }
}
