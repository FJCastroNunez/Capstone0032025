<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizacion';
    protected $primaryKey = 'id_cotizacion';

    protected $fillable = [
        'id_cliente',
        'id_usuario',
        'fecha',
        'estado',
        'subtotal',
        'impuestos',
        'total',
        'observaciones',
        'created_at',
        'updated_at'
    ];

    public function id_cliente()
    {
        return $this->hasMany(Cliente::class, 'id_cliente');
    }

    public function id_usuario()
    {
        return $this->hasMany(Usuario::class, 'id_usuario');
    }
    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }

    public function adjuntos()
    {
        return $this->hasMany(CotizacionAdjunta::class, 'id_cotizacion', 'id_cotizacion');
    }
}
