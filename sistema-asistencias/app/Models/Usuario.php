<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Carrera;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'codigo',
        'dni',
        'nombres',
        'apellidos',
        'institucion_id',
        'carrera_id',
        'rol',
        'modalidad',
        'activo',
        'password',
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Relación con el modelo Carrera
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'usuario_id');
    }

    public function sesionesGeneradas()
    {
        return $this->hasMany(SesionRemota::class, 'generado_por');
    }
}