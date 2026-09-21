@extends('layouts.app')

@section('title', 'Asistencia Presencial - CID PUJLLAY')

@section('content')
<div class="max-w-xl mx-auto w-full my-auto">
    <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-3xl shadow-2xl overflow-hidden">
        
        <!-- Header con Logo Pujllay -->
        <div class="p-6 text-center border-b border-white/10">
            <div class="mb-2 inline-block transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('images/logo-pujllay.png') }}" 
                     alt="Logo CID Pujllay" 
                     class="h-24 sm:h-28 w-auto mx-auto object-contain drop-shadow-lg">
            </div>
            <h1 class="text-xl font-extrabold text-white tracking-tight mt-1">
                Centro de Investigación y Desarrollo
            </h1>
            <p class="text-xs text-amber-300 font-bold tracking-wide mt-0.5">
                Control de Asistencia Presencial • Practicantes
            </p>
        </div>

        <div class="p-6 sm:p-8 space-y-5">
            <!-- Selección de Institución -->
            <div>
                <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-2">
                    Institución / Convenio <span class="text-rose-400">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" onclick="seleccionarInst('SENATI')" id="p-btn-senati" class="btn-inst-p py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all">
                        SENATI
                    </button>
                    <button type="button" onclick="seleccionarInst('UNSA')" id="p-btn-unsa" class="btn-inst-p py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all">
                        UNSA
                    </button>
                    <button type="button" onclick="seleccionarInst('CIMAC')" id="p-btn-cimac" class="btn-inst-p py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all">
                        CIMAC
                    </button>
                    <button type="button" onclick="seleccionarInst('OTRO')" id="p-btn-otro" class="btn-inst-p py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all">
                        OTRO
                    </button>
                </div>
            </div>

            <!-- Alerta Dinámica -->
            <div id="alerta-presencial" class="hidden p-4 rounded-2xl text-center font-medium text-xs backdrop-blur-sm transition-all duration-300"></div>

            <form id="form-presencial" onsubmit="event.preventDefault();" class="space-y-4">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">DNI / Documento <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <input type="text" id="p_dni" oninput="buscarUsuarioDNI()" class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all font-semibold" placeholder="Ej. ADM001 o 74829103" autocomplete="off" required>
                            <span id="loader-dni" class="hidden absolute right-3 top-2.5 text-amber-300 text-xs animate-spin">🔄</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Fecha Actual (Servidor)</label>
                        <input type="text" id="p_fecha_mostrar" class="w-full bg-slate-950/60 border border-white/5 rounded-xl px-4 py-2.5 text-xs text-slate-300 cursor-not-allowed text-center outline-none font-bold" readonly>
                    </div>
                </div>

                <!-- Tarjeta de Confirmación de Identidad -->
                <div id="card-verificacion" class="hidden bg-amber-400/10 border border-amber-400/30 rounded-2xl p-3.5 flex items-center justify-between text-xs backdrop-blur-sm">
                    <div class="flex items-center gap-2.5">
                        <span class="text-lg">👤</span>
                        <div>
                            <p class="text-amber-200 font-bold" id="txt-confirm-nombre">-</p>
                            <p class="text-slate-300 text-[11px]" id="txt-confirm-carrera">-</p>
                        </div>
                    </div>
                    <button type="button" onclick="limpiarDni()" class="bg-slate-900/80 hover:bg-slate-900 text-amber-300 border border-amber-400/30 px-3 py-1.5 rounded-lg text-[11px] font-bold transition">
                        ¿No eres tú? Cambiar
                    </button>
                </div>

                <!-- Campos Autocompletados -->
                <div>
                    <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Nombres y Apellidos <span class="text-rose-400">*</span></label>
                    <input type="text" id="p_nombres" name="nombres" class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all font-semibold" placeholder="Se completará automáticamente al ingresar DNI..." required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Especialidad / Carrera <span class="text-rose-400">*</span></label>
                    <input type="text" id="p_especialidad" name="especialidad" class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all font-semibold" placeholder="Se completará automáticamente..." required>
                </div>

                <div>
                    <label id="lbl-actividad" class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">
                        Actividades a Desarrollar <span class="text-rose-400">*</span>
                    </label>
                    <textarea id="p_actividad" rows="2" class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all resize-none" placeholder="Describe los avances o tareas planificadas para hoy..." required></textarea>
                </div>

                <!-- Botones de Acción -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button" onclick="procesarPresencial('entrada')" class="py-3.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-black rounded-xl shadow-lg transition duration-200 text-xs uppercase tracking-wider flex items-center justify-center gap-1.5 active:scale-[0.98]">
                        <span>🟢 Marcar Entrada</span>
                    </button>
                    <button type="button" onclick="procesarPresencial('salida')" class="py-3.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black rounded-xl shadow-lg transition duration-200 text-xs uppercase tracking-wider flex items-center justify-center gap-1.5 active:scale-[0.98]">
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
let timerBusqueda = null;

document.addEventListener('DOMContentLoaded', () => {
    const hoy = new Date();
    document.getElementById('p_fecha_mostrar').value = hoy.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
});

function seleccionarInst(inst) {
    instPresencial = inst;
    document.querySelectorAll('.btn-inst-p').forEach(b => b.className = 'btn-inst-p py-2.5 px-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-all');
    const btn = document.getElementById(`p-btn-${inst.toLowerCase()}`);
    if(btn) btn.className = 'btn-inst-p py-2.5 px-2 bg-amber-400 border border-amber-400 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm';
}

function buscarUsuarioDNI() {
    clearTimeout(timerBusqueda);
    const dni = document.getElementById('p_dni').value.trim();

    // Permite búsquedas desde 3 caracteres para cubrir ADM001, P202601 o DNIs
    if (dni.length < 3) {
        document.getElementById('card-verificacion').classList.add('hidden');
        return;
    }

    document.getElementById('loader-dni').classList.remove('hidden');

    timerBusqueda = setTimeout(async () => {
        try {
            const res = await fetch(`/api/practicante/buscar?dni=${encodeURIComponent(dni)}`);
            const data = await res.json();
            document.getElementById('loader-dni').classList.add('hidden');

            if (res.ok && data.encontrado) {
                // Rellenar inputs automáticamente
                document.getElementById('p_nombres').value = data.nombres || '';
                document.getElementById('p_especialidad').value = data.especialidad || '';
                
                if (data.institucion) {
                    seleccionarInst(data.institucion);
                }

                // Mostrar tarjeta de verificación
                document.getElementById('txt-confirm-nombre').innerText = data.nombres || '';
                document.getElementById('txt-confirm-carrera').innerText = data.especialidad || '';
                document.getElementById('card-verificacion').classList.remove('hidden');
            } else {
                document.getElementById('card-verificacion').classList.add('hidden');
            }
        } catch (e) {
            console.error(e);
            document.getElementById('loader-dni').classList.add('hidden');
        }
    }, 250);
}

function limpiarDni() {
    document.getElementById('p_dni').value = '';
    document.getElementById('p_nombres').value = '';
    document.getElementById('p_especialidad').value = '';
    document.getElementById('card-verificacion').classList.add('hidden');
    document.getElementById('p_dni').focus();
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
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                codigo: dni,
                institucion: instPresencial,
                nombres: nombres,
                especialidad: especialidad,
                actividad: actividad
            })
        });

        const data = await res.json();
        if (res.ok && data.ok) {
            mostrarAlerta(`¡${tipo.toUpperCase()} REGISTRADA CON ÉXITO!<br><strong>${data.nombre || nombres}</strong>`, 'exito');
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
    document.getElementById('card-verificacion').classList.add('hidden');
    const hoy = new Date();
    document.getElementById('p_fecha_mostrar').value = hoy.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function mostrarAlerta(msg, tipo) {
    const box = document.getElementById('alerta-presencial');
    box.innerHTML = msg;
    box.className = `p-4 rounded-2xl text-center font-medium text-xs backdrop-blur-sm border ${tipo === 'exito' ? 'bg-amber-400/20 text-amber-200 border-amber-400/50' : 'bg-red-500/20 text-red-200 border-red-500/50'}`;
    box.classList.remove('hidden');
}
</script>
@endpush