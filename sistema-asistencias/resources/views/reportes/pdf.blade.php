<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 5px;
            text-align: left;
        }

        th {
            background: #eeeeee;
        }
    </style>
</head>

<body>

    <h2>Reporte de Asistencias</h2>

    <table>
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Institución</th>
                <th>Carrera</th>
                <th>Fecha</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Modalidad</th>
                <th>Estado</th>
                <th>Actividad</th>
                <th>Justificación</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($asistencias as $a)
                <tr>
                    <td>{{ $a->usuario->dni ?? 'N/A' }}</td>

                    <td>
                        {{ $a->usuario->nombre_completo ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $a->usuario->institucion->nombre ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $a->usuario->carrera->nombre ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $a->fecha?->format('d/m/Y') }}
                    </td>

                    <td>{{ $a->hora_entrada }}</td>

                    <td>
                        {{ $a->hora_salida ?? 'Pendiente' }}
                    </td>

                    <td>{{ $a->modalidad }}</td>

                    <td>{{ $a->estado }}</td>

                    <td>
                        {{ $a->actividad ?? 'Sin actividad' }}
                    </td>

                    <td>
                        {{ $a->justificaciones->pluck('motivo')->join(' | ') ?: '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>