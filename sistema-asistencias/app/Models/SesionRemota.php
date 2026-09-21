<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SesionRemota extends Model
{
    use HasFactory;

    protected $table = 'sesion_remotas';

    protected $fillable = [
        'codigo',
        'generado_por',
        'expira_at',
        'activo',
    ];

    protected $casts = [
        'expira_at' => 'datetime',
        'activo' => 'boolean',
    ];

    /**
     * Verifica si la sesión virtual sigue activa y vigente según el tiempo actual.
     */
    public function esValida(): bool
    {
        return $this->activo && Carbon::now('America/Lima')->lessThanOrEqualTo($this->expira_at);
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'generado_por');
    }
}