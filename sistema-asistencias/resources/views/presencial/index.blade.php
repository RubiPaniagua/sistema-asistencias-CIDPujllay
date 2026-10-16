@extends('layouts.app')

@section('title', 'Asistencia Presencial - CID PUJLLAY')

@section('content')
<div class="w-full max-w-xl mx-auto my-auto px-2 sm:px-4 py-3 sm:py-6 overflow-x-hidden">
    
    <!-- Botón Volver al menú principal -->
    <div class="mb-3 sm:mb-5 text-center">
        <a href="{{ route('welcome') }}" class="inline-flex items-center text-xs font-bold text-slate-300 hover:text-amber-300 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al menú principal
        </a>
    </div>

    <!-- Contenedor Tarjeta Fija y Simétrica -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-3xl shadow-2xl overflow-hidden p-4 sm:p-8 space-y-4 sm:space-y-6">
        
        <!-- Header con Logo -->
        <div class="text-center border-b border-white/10 pb-4">
            <div class="inline-block transition-transform hover:scale-105 duration-300 mb-1">
                <img src="{{ asset('images/logo-pujllay.png') }}" alt="Logo CID Pujllay" class="h-16 sm:h-24 w-auto mx-auto object-contain drop-shadow-lg">
            </div>
            <h1 class="text-base sm:text-xl font-black text-white tracking-tight">Centro de Investigación y Desarrollo</h1>
            <p class="text-[11px] sm:text-xs text-amber-300 font-bold tracking-wide mt-0.5">Control de Asistencia Presencial • Practicantes</p>
        </div>

        <!-- Alerta Dinámica (Visible y con buen contraste) -->
        <div id="alerta-presencial" class="hidden p-4 rounded-2xl text-center font-bold text-xs shadow-lg transition-all duration-300"></div>

        <form id="form-presencial" onsubmit="event.preventDefault();" class="space-y-4">
            
            <!-- Campo DNI y Fecha -->
            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1 truncate">DNI / DOCUMENTO <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <input type="text" id="p_dni" oninput="buscarUsuarioDNI()" maxlength="8" inputmode="numeric" class="w-full bg-slate-900/80 border border-white/15 rounded-xl px-2.5 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 font-bold tracking-wider" placeholder="Ej. 74827384" autocomplete="off" required>
                        <span id="loader-dni" class="hidden absolute right-2 top-3 text-amber-300 text-xs animate-spin">🔄</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1 truncate">FECHA ACTUAL (SERVIDOR)</label>
                    <input type="text" id="p_fecha_mostrar" class="w-full bg-slate-950/60 border border-white/5 rounded-xl px-2 sm:px-4 py-2.5 sm:py-3 text-xs text-slate-300 text-center font-bold outline-none cursor-default" readonly>
                </div>
            </div>

            <!-- Tarjeta de Confirmación de Usuario -->
            <div id="card-verificacion" class="hidden bg-slate-900/90 border border-amber-400/40 rounded-2xl p-3 sm:p-4 transition-all shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-400/20 border border-amber-400/40 flex items-center justify-center text-amber-300 font-bold shrink-0 text-sm sm:text-base">
                            👤
                        </div>
                        <div class="space-y-0.5 min-w-0">
                            <p class="text-white font-black text-xs sm:text-sm uppercase tracking-wide truncate" id="txt-confirm-nombre">-</p>
                            <p class="text-slate-300 text-[10px] sm:text-[11px] font-medium truncate" id="txt-confirm-carrera">-</p>
                            <p class="text-amber-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider" id="txt-confirm-institucion">-</p>
                        </div>
                    </div>
                    <button type="button" onclick="limpiarDni()" class="bg-white/10 hover:bg-white/20 text-slate-200 hover:text-amber-300 border border-white/10 px-2.5 py-1 rounded-xl text-[10px] font-bold transition shrink-0 ml-2">
                        Cambiar
                    </button>
                </div>
            </div>

            <!-- Inputs invisibles -->
            <input type="hidden" id="p_nombres">
            <input type="hidden" id="p_especialidad">
            <input type="hidden" id="p_institucion">

            <!-- Actividad -->
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">ACTIVIDADES A DESARROLLAR <span class="text-rose-400">*</span></label>
                <textarea id="p_actividad" rows="2" class="w-full bg-slate-900/80 border border-white/15 rounded-xl px-3 sm:px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 transition-all resize-none" placeholder="Describe los avances o tareas planificadas para hoy..."></textarea>
            </div>

            <!-- Botones de Acción -->
            <div class="grid grid-cols-2 gap-2 sm:gap-3 pt-1">
                <button type="button" onclick="procesarPresencial('entrada')" class="py-3 sm:py-3.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-black rounded-xl shadow-lg transition duration-200 text-[11px] sm:text-xs uppercase tracking-wider flex items-center justify-center gap-1 active:scale-[0.98]">
                    <span>🟢 MARCAR ENTRADA</span>
                </button>
                <button type="button" onclick="procesarPresencial('salida')" class="py-3 sm:py-3.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black rounded-xl shadow-lg transition duration-200 text-[11px] sm:text-xs uppercase tracking-wider flex items-center justify-center gap-1 active:scale-[0.98]">
                    <span>🔴 MARCAR SALIDA</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let timerBusqueda = null;

document.addEventListener('DOMContentLoaded', () => {
    const hoy = new Date();
    document.getElementById('p_fecha_mostrar').value = hoy.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
});

function buscarUsuarioDNI() {
    clearTimeout(timerBusqueda);
    const dni = document.getElementById('p_dni').value.trim();

    if (dni.length < 8) {
        document.getElementById('card-verificacion').classList.add('hidden');
        document.getElementById('p_nombres').value = '';
        document.getElementById('p_especialidad').value = '';
        document.getElementById('p_institucion').value = '';
        return;
    }

    document.getElementById('loader-dni').classList.remove('hidden');

    timerBusqueda = setTimeout(async () => {
        try {
            const res = await fetch(`/api/practicante/buscar?dni=${encodeURIComponent(dni)}`);
            const data = await res.json();
            document.getElementById('loader-dni').classList.add('hidden');

            if (res.ok && data.encontrado) {
                document.getElementById('p_nombres').value = data.nombres || '';
                document.getElementById('p_especialidad').value = data.especialidad || '';
                document.getElementById('p_institucion').value = data.institucion || 'OTRO';

                document.getElementById('txt-confirm-nombre').innerText = data.nombres || '';
                document.getElementById('txt-confirm-carrera').innerText = data.especialidad || '';
                document.getElementById('txt-confirm-institucion').innerText = data.institucion || 'OTRO';
                
                document.getElementById('card-verificacion').classList.remove('hidden');
            } else {
                document.getElementById('card-verificacion').classList.add('hidden');
            }
        } catch (e) {
            console.error(e);
            document.getElementById('loader-dni').classList.add('hidden');
        }
    }, 200);
}

function limpiarDni() {
    document.getElementById('p_dni').value = '';
    document.getElementById('p_nombres').value = '';
    document.getElementById('p_especialidad').value = '';
    document.getElementById('p_institucion').value = '';
    document.getElementById('card-verificacion').classList.add('hidden');
    document.getElementById('p_dni').focus();
}

async function procesarPresencial(tipo) {
    const dni = document.getElementById('p_dni').value.trim();
    const nombres = document.getElementById('p_nombres').value.trim();
    const especialidad = document.getElementById('p_especialidad').value.trim();
    const institucion = document.getElementById('p_institucion').value.trim() || 'OTRO';
    const actividad = document.getElementById('p_actividad').value.trim();

    if (!dni || dni.length < 8) return mostrarAlerta('Ingresa un DNI válido de 8 dígitos', 'error');
    if (!nombres) return mostrarAlerta('No se encontraron datos asociados a este DNI', 'error');
    if (tipo === 'entrada' && !actividad) return mostrarAlerta('Describe las actividades a desarrollar para la entrada', 'error');

    try {
        const res = await fetch(`/api/asistencia/${tipo}`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                dni: dni,
                institucion: institucion,
                nombres: nombres,
                especialidad: especialidad,
                actividad: actividad
            })
        });

        const data = await res.json();
        if (res.ok && data.ok) {
            // Redirección inmediata al menú principal tras marcar con éxito
            window.location.href = "{{ route('welcome') }}";
        } else {
            mostrarAlerta(data.error || 'Error al procesar el registro', 'error');
        }
    } catch (e) {
        mostrarAlerta('Error de conexión con el servidor', 'error');
    }
}

function mostrarAlerta(msg, tipo) {
    const box = document.getElementById('alerta-presencial');
    box.innerHTML = msg;
    box.className = `p-4 rounded-2xl text-center font-bold text-xs backdrop-blur-md border shadow-xl ${tipo === 'exito' ? 'bg-emerald-500/30 text-emerald-100 border-emerald-400/60' : 'bg-rose-500/30 text-rose-100 border-rose-400/60'}`;
    box.classList.remove('hidden');
}
</script>
@endpush