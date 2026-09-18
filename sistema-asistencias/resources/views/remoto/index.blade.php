@extends('layouts.app')

@section('title', 'Asistencia Remota - CID PUJLLAY')

@section('content')
<div class="max-w-xl mx-auto my-auto w-full px-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden">
        
        <!-- Header Elegante -->
        <div class="bg-gradient-to-r from-[#0b2b52] via-[#0f3b70] to-[#1e508e] p-6 text-white text-center border-b border-amber-500/30">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-white/10 rounded-xl mb-3 text-amber-400 font-black text-xl tracking-wider border border-white/10 shadow-inner">
                CID
            </div>
            <h1 class="text-xl font-bold tracking-tight">Centro de Investigación y Desarrollo</h1>
            <p class="text-xs text-amber-300/90 font-medium mt-1">Control de Asistencia Virtual • Practicantes</p>
        </div>

        <div class="p-6 sm:p-8">
            <!-- Selección de Institución -->
            <div class="mb-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Institución / Convenio <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" onclick="seleccionarInst('SENATI')" id="r-btn-senati" class="btn-inst-r py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        SENATI
                    </button>
                    <button type="button" onclick="seleccionarInst('UNSA')" id="r-btn-unsa" class="btn-inst-r py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        UNSA
                    </button>
                    <button type="button" onclick="seleccionarInst('CIMAC')" id="r-btn-cimac" class="btn-inst-r py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        CIMAC
                    </button>
                    <button type="button" onclick="seleccionarInst('OTRO')" id="r-btn-otro" class="btn-inst-r py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        OTRO
                    </button>
                </div>
            </div>

            <div id="alerta-remoto" class="hidden mb-5 p-4 rounded-xl text-center font-medium text-sm transition-all duration-300"></div>

            <form id="form-remoto" onsubmit="event.preventDefault(); registrarRemoto();" class="space-y-4">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">DNI / Documento <span class="text-rose-500">*</span></label>
                        <input type="text" id="r_dni" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-[#0f3b70] focus:bg-white text-sm font-semibold outline-none transition text-slate-800" placeholder="Ej. 74829103" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Código Sesión Virtual <span class="text-rose-500">*</span></label>
                        <input type="text" id="r_codigo_sesion" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-[#0f3b70] focus:bg-white text-sm font-semibold outline-none transition uppercase tracking-widest text-slate-800" placeholder="EJ. ABC123" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nombres y Apellidos <span class="text-rose-500">*</span></label>
                        <input type="text" id="r_nombres" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-[#0f3b70] focus:bg-white text-sm font-semibold outline-none transition text-slate-800" placeholder="Ej. Juan Perez Garcia" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Fecha Actual (Servidor)</label>
                        <input type="text" id="r_fecha_mostrar" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-200/70 border border-slate-300 text-sm font-bold text-slate-600 cursor-not-allowed text-center outline-none" readonly>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Especialidad / Carrera <span class="text-rose-500">*</span></label>
                    <input type="text" id="r_especialidad" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-[#0f3b70] focus:bg-white text-sm font-semibold outline-none transition text-slate-800" placeholder="Ej. Ingeniería de Software / Educación" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Actividad Desarrollada <span class="text-rose-500">*</span></label>
                    <textarea id="r_actividad" rows="2" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-[#0f3b70] focus:bg-white text-sm outline-none transition text-slate-800" placeholder="Escribe los avances logrados durante el día..." required></textarea>
                </div>

                <button type="submit" id="btn-marcar-r" class="w-full py-3.5 bg-[#0f3b70] hover:bg-[#0c2e58] text-white font-bold rounded-xl shadow-md transition duration-200 text-sm flex justify-center items-center gap-2 mt-2">
                    <span>📲 Registrar Asistencia Virtual</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let instRemoto = '';

document.addEventListener('DOMContentLoaded', () => {
    const hoy = new Date();
    document.getElementById('r_fecha_mostrar').value = hoy.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
});

function seleccionarInst(inst) {
    instRemoto = inst;
    document.querySelectorAll('.btn-inst-r').forEach(b => b.className = 'btn-inst-r py-2.5 px-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition');
    const btn = document.getElementById(`r-btn-${inst.toLowerCase()}`);
    if(btn) btn.className = 'btn-inst-r py-2.5 px-2 border border-[#0f3b70] bg-[#0f3b70] text-white rounded-xl text-xs font-bold transition shadow-sm';
}

async function registrarRemoto() {
    const dni = document.getElementById('r_dni').value.trim();
    const codigo_sesion = document.getElementById('r_codigo_sesion').value.trim();
    const nombres = document.getElementById('r_nombres').value.trim();
    const especialidad = document.getElementById('r_especialidad').value.trim();
    const actividad = document.getElementById('r_actividad').value.trim();

    if (!instRemoto) return mostrarAlerta('Selecciona tu Institución / Convenio', 'error');
    if (!dni || !codigo_sesion || !nombres || !especialidad || !actividad) {
        return mostrarAlerta('Completa todos los campos solicitados', 'error');
    }

    try {
        const res = await fetch('/api/asistencia/remota', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                institucion: instRemoto,
                dni: dni,
                codigo_temporal: codigo_sesion,
                nombres: nombres,
                especialidad: especialidad,
                actividad: actividad
            })
        });

        const data = await res.json();
        if (res.ok && data.ok) {
            mostrarAlerta(`¡REGISTRO VIRTUAL EXITOSO!<br><strong>${nombres}</strong>`, 'exito');
            document.getElementById('form-remoto').reset();
            const hoy = new Date();
            document.getElementById('r_fecha_mostrar').value = hoy.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
        } else {
            mostrarAlerta(data.error || 'Código de sesión vencido o datos incorrectos', 'error');
        }
    } catch (e) {
        mostrarAlerta('Error de conexión con el servidor', 'error');
    }
}

function mostrarAlerta(msg, tipo) {
    const box = document.getElementById('alerta-remoto');
    box.innerHTML = msg;
    box.className = `mb-5 p-4 rounded-xl text-center font-medium text-sm border ${tipo === 'exito' ? 'bg-emerald-100 text-emerald-800 border-emerald-400' : 'bg-rose-100 text-rose-800 border-rose-400'}`;
}
</script>
@endpush