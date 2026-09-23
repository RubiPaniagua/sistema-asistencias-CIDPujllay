@extends('layouts.app')

@section('title', 'Asistencia Remota - CID PUJLLAY')

@section('content')
<div class="max-w-xl mx-auto w-full my-auto">
    <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-3xl shadow-2xl overflow-hidden">
        
        <!-- Header con Logo Pujllay Optimizado -->
<div class="p-6 text-center border-b border-white/10">
    <div class="mb-2 inline-block transition-transform hover:scale-105 duration-300">
        <!-- Logo más grande (h-24 en móviles, h-28 en pantallas normales) -->
        <img src="{{ asset('images/logo-pujllay.png') }}" 
             alt="Logo CID Pujllay" 
             class="h-24 sm:h-28 w-auto mx-auto object-contain drop-shadow-lg">
    </div>
    
    <h1 class="text-xl font-extrabold text-white tracking-tight mt-1">
        Centro de Investigación y Desarrollo
    </h1>
    <p class="text-xs text-amber-300 font-bold tracking-wide mt-0.5">
        Control de Asistencia Virtual • Practicantes
    </p>
</div>

        <div class="p-6 sm:p-8 space-y-5">
            <!-- Selección de Institución -->
            <div>
                <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-2">
                    Institución / Convenio <span class="text-rose-400">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" onclick="seleccionarInst('SENATI')" id="r-btn-senati" class="btn-inst-r py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all">
                        SENATI
                    </button>
                    <button type="button" onclick="seleccionarInst('UNSA')" id="r-btn-unsa" class="btn-inst-r py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all">
                        UNSA
                    </button>
                    <button type="button" onclick="seleccionarInst('CIMAC')" id="r-btn-cimac" class="btn-inst-r py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all">
                        CIMAC
                    </button>
                    <button type="button" onclick="seleccionarInst('OTRO')" id="r-btn-otro" class="btn-inst-r py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all">
                        OTRO
                    </button>
                </div>
            </div>

            <!-- Alerta Dinámica -->
            <div id="alerta-remoto" class="hidden p-4 rounded-2xl text-center font-medium text-xs backdrop-blur-sm transition-all duration-300"></div>

            <form id="form-remoto" onsubmit="event.preventDefault(); registrarRemoto();" class="space-y-4">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">DNI / Documento <span class="text-rose-400">*</span></label>
                        <input type="text" id="r_dni" class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all font-semibold" placeholder="Ej. 74829103" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Código Sesión Virtual <span class="text-rose-400">*</span></label>
                        <input type="text" id="r_codigo_sesion" class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all font-semibold uppercase tracking-widest" placeholder="EJ. ABC123" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Nombres y Apellidos <span class="text-rose-400">*</span></label>
                        <input type="text" id="r_nombres" class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all font-semibold" placeholder="Ej. Juan Perez Garcia" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Fecha Actual (Servidor)</label>
                        <input type="text" id="r_fecha_mostrar" class="w-full bg-slate-950/60 border border-white/5 rounded-xl px-4 py-2.5 text-xs text-slate-300 cursor-not-allowed text-center outline-none font-bold" readonly>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Especialidad / Carrera <span class="text-rose-400">*</span></label>
                    <input type="text" id="r_especialidad" class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all font-semibold" placeholder="Ej. Ingeniería de Software / Educación" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Actividad Desarrollada <span class="text-rose-400">*</span></label>
                    <textarea id="r_actividad" rows="2" class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all resize-none" placeholder="Escribe los avances logrados durante el día..." required></textarea>
                </div>

                <!-- Botón de Registro en tono Mostaza/Ámbar -->
                <button type="submit" id="btn-marcar-r" class="w-full py-3.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-black rounded-xl shadow-lg transition duration-200 text-xs uppercase tracking-wider flex justify-center items-center gap-2 mt-2 active:scale-[0.98]">
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
    document.querySelectorAll('.btn-inst-r').forEach(b => b.className = 'btn-inst-r py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all');
    const btn = document.getElementById(`r-btn-${inst.toLowerCase()}`);
    if(btn) btn.className = 'btn-inst-r py-2.5 px-2 bg-amber-400 border border-amber-400 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm';
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
    box.className = `p-4 rounded-2xl text-center font-medium text-xs backdrop-blur-sm border ${tipo === 'exito' ? 'bg-amber-400/20 text-amber-200 border-amber-400/50' : 'bg-red-500/20 text-red-200 border-red-500/50'}`;
}
</script>
@endpush