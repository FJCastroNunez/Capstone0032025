<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'id_producto',
        'nombre',
        'descripcion',
        'precio',
        'unidad',
        'stock',
        'activo',
        'created_at',
        'updated_at'
    ];
}
