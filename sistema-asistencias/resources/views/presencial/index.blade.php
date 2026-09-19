@extends('layouts.app')

@section('title', 'Asistencia Presencial - CID PUJLLAY')

@section('content')
<div class="max-w-xl mx-auto my-auto w-full px-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden">
        
        <!-- Header Elegante -->
        <div class="bg-gradient-to-r from-[#0b2b52] via-[#0f3b70] to-[#1e508e] p-6 text-white text-center border-b border-amber-500/30">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-white/10 rounded-xl mb-3 text-amber-400 font-black text-xl tracking-wider border border-white/10 shadow-inner">
                CID
            </div>
            <h1 class="text-xl font-bold tracking-tight">Centro de Investigación y Desarrollo</h1>
            <p class="text-xs text-amber-300/90 font-medium mt-1">Control de Asistencia Presencial • Practicantes</p>
        </div>

        <div class="p-6 sm:p-8">
            <!-- Selección de Institución -->
            <div class="mb-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Institución / Convenio <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" onclick="seleccionarInst('SENATI')" id="p-btn-senati" class="btn-inst-p py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        SENATI
                    </button>
                    <button type="button" onclick="seleccionarInst('UNSA')" id="p-btn-unsa" class="btn-inst-p py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        UNSA
                    </button>
                    <button type="button" onclick="seleccionarInst('CIMAC')" id="p-btn-cimac" class="btn-inst-p py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        CIMAC
                    </button>
                    <button type="button" onclick="seleccionarInst('OTRO')" id="p-btn-otro" class="btn-inst-p py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        OTRO
                    </button>
                </div>
            </div>

            <!-- Alerta Dinámica -->
            <div id="alerta-presencial" class="hidden mb-5 p-4 rounded-xl text-center font-medium text-sm transition-all duration-300"></div>

            <form id="form-presencial" onsubmit="event.preventDefault();" class="space-y-4">
                
                <!-- DNI y Fecha Inalterable -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">DNI / Documento <span class="text-rose-500">*</span></label>
                        <input type="text" id="p_dni" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-[#0f3b70] focus:bg-white text-sm font-semibold outline-none transition text-slate-800" placeholder="Ej. 74829103" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Fecha Actual (Servidor)</label>
                        <input type="text" id="p_fecha_mostrar" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-200/70 border border-slate-300 text-sm font-bold text-slate-600 cursor-not-allowed text-center outline-none" readonly>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nombres y Apellidos <span class="text-rose-500">*</span></label>
                    <input type="text" id="p_nombres" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-[#0f3b70] focus:bg-white text-sm font-semibold outline-none transition text-slate-800 placeholder:font-normal" placeholder="Ej. Maria Lopez Perez" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Especialidad / Carrera <span class="text-rose-500">*</span></label>
                    <input type="text" id="p_especialidad" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-[#0f3b70] focus:bg-white text-sm font-semibold outline-none transition text-slate-800 placeholder:font-normal" placeholder="Ej. Ingeniería de Software / Educación" required>
                </div>

                <!-- Actividad Planificada o Realizada -->
                <div>
                    <label id="lbl-actividad" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Actividades a Desarrollar <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="p_actividad" rows="2" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-[#0f3b70] focus:bg-white text-sm outline-none transition text-slate-800 placeholder:text-slate-400" placeholder="Describe los avances o tareas planificadas para hoy..." required></textarea>
                </div>

                <!-- Botones Entrada / Salida -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button" onclick="procesarPresencial('entrada')" class="py-3.5 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white font-bold rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center gap-1.5">
                        <span>🟢 Marcar Entrada</span>
                    </button>
                    <button type="button" onclick="procesarPresencial('salida')" class="py-3.5 bg-amber-600 hover:bg-amber-700 active:scale-98 text-white font-bold rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center gap-1.5">
                        <span>🔴 Marcar Salida</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let instPresencial = '';

document.addEventListener('DOMContentLoaded', () => {
    // Formato visible inalterable (Día/Mes/Año)
    const hoy = new Date();
    document.getElementById('p_fecha_mostrar').value = hoy.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
});

function seleccionarInst(inst) {
    instPresencial = inst;
    document.querySelectorAll('.btn-inst-p').forEach(b => b.className = 'btn-inst-p py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition');
    const btn = document.getElementById(`p-btn-${inst.toLowerCase()}`);
    if(btn) btn.className = 'btn-inst-p py-2.5 px-2 border border-[#0f3b70] bg-[#0f3b70] text-white rounded-xl text-xs font-bold transition shadow-sm';
}

async function procesarPresencial(tipo) {
    const dni = document.getElementById('p_dni').value.trim();
    const nombres = document.getElementById('p_nombres').value.trim();
    const especialidad = document.getElementById('p_especialidad').value.trim();
    const actividad = document.getElementById('p_actividad').value.trim();

    if (!instPresencial) return mostrarAlerta('Selecciona tu Institución / Convenio', 'error');
    if (!dni || !nombres || !especialidad || !actividad) return mostrarAlerta('Completa todos los campos obligatorios', 'error');

    try {
        const res = await fetch(`/api/asistencia/${tipo}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                institucion: instPresencial,
                dni: dni,
                nombres: nombres,
                especialidad: especialidad,
                actividad: actividad,
                tipo_marcado: tipo // Entrada o Salida
            })
        });

        const data = await res.json();
        if (res.ok && data.ok) {
            mostrarAlerta(`¡${tipo.toUpperCase()} REGISTRADA CON ÉXITO!<br><strong>${nombres}</strong>`, 'exito');
            resetearPresencial();
        } else {
            mostrarAlerta(data.error || 'Error al procesar el registro', 'error');
        }
    } catch (e) {
        mostrarAlerta('Error de conexión con el servidor', 'error');
    }
}

function resetearPresencial() {
    document.getElementById('form-presencial').reset();
    const hoy = new Date();
    document.getElementById('p_fecha_mostrar').value = hoy.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function mostrarAlerta(msg, tipo) {
    const box = document.getElementById('alerta-presencial');
    box.innerHTML = msg;
    box.className = `mb-5 p-4 rounded-xl text-center font-medium text-sm border ${tipo === 'exito' ? 'bg-emerald-100 text-emerald-800 border-emerald-400' : 'bg-rose-100 text-rose-800 border-rose-400'}`;
}
</script>
@endpush