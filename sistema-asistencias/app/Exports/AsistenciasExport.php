<?php

namespace App\Exports;

use App\Models\Asistencia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AsistenciasExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected array $filtros)
    {
    }

    public function collection()
    {
        $query = Asistencia::with([
            'usuario.carrera',
            'usuario.institucion'
        ]);

        if (!empty($this->filtros['desde'])) {
            $query->whereDate('fecha', '>=', $this->filtros['desde']);
        }

        if (!empty($this->filtros['hasta'])) {
            $query->whereDate('fecha', '<=', $this->filtros['hasta']);
        }

        if (!empty($this->filtros['modalidad'])) {
            $query->where('modalidad', $this->filtros['modalidad']);
        }

        if (!empty($this->filtros['estado'])) {
            $query->where('estado', $this->filtros['estado']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'DNI',
            'Nombre',
            'Institución',
            'Carrera',
            'Fecha',
            'Entrada',
            'Salida',
            'Modalidad',
            'Estado',
            'Actividad',
            'Justificación',
        ];
    }

    public function map($asistencia): array
    {
        return [
            $asistencia->usuario->dni ?? 'N/A',
            $asistencia->usuario->nombre_completo ?? 'N/A',
            $asistencia->usuario->institucion->nombre ?? 'N/A',
            $asistencia->usuario->carrera->nombre ?? 'N/A',
            $asistencia->fecha?->format('d/m/Y'),
            $asistencia->hora_entrada,
            $asistencia->hora_salida,
            $asistencia->modalidad,
            $asistencia->estado,
            $asistencia->actividad,
            $asistencia->justificacion,
        ];
    }
}