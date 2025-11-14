<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'material';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'espesor',
        'valor_m2'
    ];

    public function stock()
    {
        // Un material tiene 1 registro principal de stock
        return $this->hasOne(StockMaterial::class, 'id_material', 'id');
    }

    public function movimientos()
    {
        // Un material tiene muchos movimientos de stock
        return $this->hasMany(MovimientoStockMaterial::class, 'id_material', 'id');
    }
}
