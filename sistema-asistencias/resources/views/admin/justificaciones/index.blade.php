@extends('layouts.app')

@section('title', 'Revisión de Justificaciones - CID PUJLLAY')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 pb-12">
    <!-- Encabezado -->
    <div class="flex items-center justify-between bg-slate-800/80 border border-slate-700/60 p-6 rounded-3xl shadow-xl backdrop-blur-md">
        <div>
            <a href="/admin/dashboard" class="text-amber-400 hover:text-amber-300 text-xs font-bold flex items-center mb-1">
                ← Volver al Dashboard
            </a>
            <h2 class="text-2xl font-bold text-white">Revisión de Justificaciones</h2>
            <p class="text-slate-400 text-sm">Aprueba o rechaza los permisos e inasistencias presentados por los practicantes.</p>
        </div>
    </div>

    <!-- Tabla de Justificaciones -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl shadow-xl overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-800/60 text-amber-300 font-bold uppercase tracking-wider border-b border-slate-700">
                    <tr>
                        <th class="p-3">Practicante / DNI</th>
                        <th class="p-3">Fecha Falta</th>
                        <th class="p-3">Motivo / Detalle</th>
                        <th class="p-3">Evidencia</th>
                        <th class="p-3 text-center">Estado</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 font-medium">
                    @forelse($justificaciones as $item)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="p-3">
                            <p class="text-white font-bold">{{ $item->usuario->nombre_completo ?? ($item->identificacion ?? 'N/A') }}</p>
                            <p class="text-slate-400 text-[11px]">DNI: {{ $item->usuario->dni ?? $item->identificacion }}</p>
                        </td>
                        <td class="p-3 font-mono text-amber-200">
                            {{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
                        </td>
                        <td class="p-3 max-w-xs">
                            <p class="text-slate-200 truncate" title="{{ $item->motivo }}">{{ $item->motivo }}</p>
                        </td>
                        <td class="p-3">
                            @if($item->evidencia_path)
                                <a href="{{ route('admin.justificaciones.evidencia', $item->id) }}" target="_blank" class="text-indigo-400 hover:text-indigo-300 underline flex items-center gap-1 font-semibold">
                                    📎 Ver Archivo
                                </a>
                            @else
                                <span class="text-slate-500 italic">Sin adjunto</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            @if($item->estado === 'pendiente')
                                <span class="bg-amber-400/20 text-amber-300 border border-amber-400/30 px-2.5 py-1 rounded-full font-bold">Pendiente</span>
                            @elseif($item->estado === 'aprobado')
                                <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2.5 py-1 rounded-full font-bold">Aprobado</span>
                            @else
                                <span class="bg-rose-500/20 text-rose-300 border border-rose-500/30 px-2.5 py-1 rounded-full font-bold">Rechazado</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            @if($item->estado === 'pendiente')
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.justificaciones.aprobar', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg transition active:scale-95 text-[11px]">
                                            ✓ Aprobar
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.justificaciones.rechazar', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-lg transition active:scale-95 text-[11px]">
                                            ✕ Rechazar
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-slate-500 text-[11px] italic">Atendido</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400 italic">No hay solicitudes de justificación registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection