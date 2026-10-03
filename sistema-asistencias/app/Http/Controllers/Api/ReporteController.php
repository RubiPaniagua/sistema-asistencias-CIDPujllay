<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Exports\AsistenciasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = Asistencia::with([
            'usuario.carrera',
            'usuario.institucion',
            'justificaciones'
        ]);

        if ($request->desde) {
            $query->whereDate('fecha', '>=', $request->desde);
        }

        if ($request->hasta) {
            $query->whereDate('fecha', '<=', $request->hasta);
        }

        if ($request->modalidad) {
            $query->where('modalidad', $request->modalidad);
        }

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        return response()->json([
            'ok' => true,
            'data' => $query->get(),
        ]);
    }

    public function excel(Request $request)
    {
        $filtros = $request->only([
            'desde',
            'hasta',
            'modalidad',
            'estado'
        ]);

        return Excel::download(
            new AsistenciasExport($filtros),
            'reporte-asistencias.xlsx'
        );
    }

    public function pdf(Request $request)
    {
        $query = Asistencia::with([
            'usuario.carrera',
            'usuario.institucion',
            'justificaciones'
        ]);

        if ($request->desde) {
            $query->whereDate('fecha', '>=', $request->desde);
        }

        if ($request->hasta) {
            $query->whereDate('fecha', '<=', $request->hasta);
        }

        if ($request->modalidad) {
            $query->where('modalidad', $request->modalidad);
        }

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        $asistencias = $query->get();

        $pdf = Pdf::loadView('reportes.pdf', compact('asistencias'));

        return $pdf->download('reporte-asistencias.pdf');
    }
}