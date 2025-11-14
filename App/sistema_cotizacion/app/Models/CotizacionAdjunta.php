<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionAdjunta extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'adjuntos_cotizacion';

    // Clave primaria personalizada
    protected $primaryKey = 'id';

    public $timestamps = false;

    // Campos que pueden asignarse masivamente
    protected $fillable = [
        'id_cotizacion',
        'ruta',
        'nombre_original',
        'formato',
        'fecha_subida'
    ];

    // Relación con Cotizacion
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion');
    }
}
