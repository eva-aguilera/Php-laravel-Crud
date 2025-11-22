<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'estado',
        'responsable',
        'monto',
        'created_by'
    ];
    
    /**
     * Define la relación: Un Proyecto pertenece a un Usuario (el creador).
     */
    public function creador(): BelongsTo
    {
        // El proyecto se relaciona con el modelo Usuario a través de la clave 'created_by'
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    protected $casts = [
        'fecha_inicio' => 'date',
    ];
}
