<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'codigo',
        'nombre',
        'carrera_id',
        'rol',
        'modalidad',
        'activo',
        'email',
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

    // Relaciones
    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function sesionesGeneradas()
    {
        return $this->hasMany(SesionRemota::class, 'generado_por');
    }
}