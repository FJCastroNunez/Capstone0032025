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
        'cliente',
        'created_at',
        'updated_at'
    ];

    public function datosCliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function id_usuario()
    {
        return $this->hasMany(Usuario::class, 'id_usuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function adjuntos()
    {
        return $this->hasMany(CotizacionAdjunta::class, 'id_cotizacion', 'id_cotizacion');
    }

    public function getInvitadoClienteAttribute()
    {
        return json_decode($this->cliente, true);
    }

    protected $casts = [
        'cliente' => 'array',
    ];
}
