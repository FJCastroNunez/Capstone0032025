<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'id_cliente',
        'nombre',
        'empresa',
        'correo',
        'telefono',
        'direccion',
        'created_at',
        'updated_at'
    ];
}
