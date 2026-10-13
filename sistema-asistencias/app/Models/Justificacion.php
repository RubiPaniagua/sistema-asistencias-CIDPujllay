<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justificacion extends Model
{
    use HasFactory;

    protected $table = 'justificaciones'; // Nombre de tu tabla en la base de datos

    protected $fillable = [
        'usuario_id',
        'identificacion',
        'tipo',
        'fecha',
        'motivo',
        'evidencia_path',
        'estado',
    ];

    // Relación con el usuario/practicante
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}