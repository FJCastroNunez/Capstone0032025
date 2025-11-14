<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoStockMaterial extends Model
{
    protected $table = 'movimiento_stock_material';
    protected $primaryKey = 'id_movimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_material',
        'tipo_movimiento',
        'cantidad',
        'motivo',
        'referencia',
        'id_usuario',
        'fecha_movimiento',
    ];

    // 🔗 Relación con Material
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id');
    }
}
