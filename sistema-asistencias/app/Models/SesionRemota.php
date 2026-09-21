<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionRemota extends Model
{
    protected $fillable = [
        'codigo_temporal',
        'generado_por',
        'expira_en',
        'usado',
    ];

    protected function casts(): array
    {
        return [
            'expira_en' => 'datetime',
            'usado' => 'boolean',
        ];
    }

    public function generador()
    {
        return $this->belongsTo(Usuario::class, 'generado_por');
    }
}
