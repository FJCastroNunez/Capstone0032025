<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'clave'
    ];


    /**
     * Laravel usa este método para identificar el campo de login.
     * Como tu columna se llama 'correo', lo redefinimos aquí.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
}
