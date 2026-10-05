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
        'codigo_temporal', //temp
        'generado_por',
        'expira_en', //en
        'usado',
    ];

    protected $casts = [
        'expira_en' => 'datetime', //en
        'usado' => 'boolean',
    ];

    /**
     * Verifica si la sesión virtual sigue activa y vigente según el tiempo actual.
     */
    public function esValida(): bool
    {
        return $this->usado 
            && Carbon::now('America/Lima')->lessThanOrEqualTo($this->expira_en);
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'generado_por');
    }
}