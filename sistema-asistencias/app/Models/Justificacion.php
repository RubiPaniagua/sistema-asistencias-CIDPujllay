<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Justificacion extends Model
{
    protected $table = 'justificaciones';

    protected $fillable = [
        'usuario_id',
        'asistencia_id',
        'fecha',
        'tipo',
        'motivo',
        'evidencia_path',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class);
    }
}