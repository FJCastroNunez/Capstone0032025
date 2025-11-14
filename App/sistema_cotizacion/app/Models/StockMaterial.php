<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMaterial extends Model
{
    protected $table = 'stock_material';
    protected $primaryKey = 'id_stock';
    public $timestamps = false; // usamos creado_en / actualizado_en

    protected $fillable = [
        'id_material',
        'stock_actual',
        'stock_minimo',
        'ubicacion',
    ];

    // 🔗 Relación con Material
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id');
    }
}
