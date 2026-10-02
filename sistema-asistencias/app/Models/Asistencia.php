<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'carrera_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'modalidad',
        'estado',
        'justificacion',
        'actividad'
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'hora_entrada' => 'datetime',
            'hora_salida' => 'datetime',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}