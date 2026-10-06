<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CID PUJLLAY - Reporte de Asistencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #1a365d;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }
        /* Evitar que las fechas, horas y estados se rompan en varias líneas */
        td.no-wrap {
            white-space: nowrap;
        }
        th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>CID PUJLLAY - REPORTE DE ASISTENCIA</h2>
        <p>Sistema de Gestión y Control de Asistencias • Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Carrera</th>
                <th>Fecha</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Modalidad</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registros as $item)
                @php
                    // Limpieza para extraer estrictamente la fecha (YYYY-MM-DD)
                    $fechaLimpia = $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('Y-m-d') : '-';

                    // Limpieza para extraer únicamente la hora (HH:mm:ss) sin fecha ni microsegundos
                    $formatearSoloHora = function($valorHora) {
                        if (!$valorHora) return 'No registrada';
                        try {
                            return \Carbon\Carbon::parse($valorHora)->format('H:i:s');
                        } catch (\Exception $e) {
                            return $valorHora;
                        }
                    };

                    $entradaLimpia = $formatearSoloHora($item->hora_entrada);
                    $salidaLimpia = $item->hora_salida ? $formatearSoloHora($item->hora_salida) : 'No registrada';
                @endphp
                <tr>
                    <td>{{ $item->usuario->nombre_completo ?? ($item->usuario->nombres ?? 'Desconocido') }}</td>
                    <td>{{ $item->usuario->carrera->nombre ?? ($item->carrera->nombre ?? '-') }}</td>
                    <td class="no-wrap">{{ $fechaLimpia }}</td>
                    <td class="no-wrap">{{ $entradaLimpia }}</td>
                    <td class="no-wrap">{{ $salidaLimpia }}</td>
                    <td class="no-wrap">{{ ucfirst($item->modalidad) }}</td>
                    <td class="no-wrap">{{ ucfirst(str_replace('_', ' ', $item->estado)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #777;">No se encontraron registros de asistencia con los filtros seleccionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Mostrando registros filtrados del sistema de asistencias • CID Pujllay {{ date('Y') }}
    </div>

</body>
</html>