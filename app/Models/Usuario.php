<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;
    
    // 1. Especificar la tabla si no es el plural por defecto (users)
    protected $table = 'usuarios';

    // 2. Especificar la columna de contraseña (debe ser 'clave')
    protected $password = 'clave';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre',
        'correo',
        'clave', // Usamos 'clave' para asignación masiva
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'clave', // Ocultar 'clave' en las respuestas JSON
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            // Cifrado automático de la contraseña (usa 'clave')
            'clave' => 'hashed', 
        ];
    }
}