<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = [
        'usuario_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'modalidad',
        'estado',
        'justificacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
