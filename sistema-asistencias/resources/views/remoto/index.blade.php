@extends('layouts.app')

@section('title', 'Asistencia Remota - CID PUJLLAY')

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
            <p class="text-[11px] sm:text-xs text-amber-300 font-bold tracking-wide mt-0.5">Control de Asistencia Virtual • Practicantes</p>
        </div>

        <!-- Alerta Dinámica (Visible y con buen contraste) -->
        <div id="alerta-remoto" class="hidden p-4 rounded-2xl text-center font-bold text-xs shadow-lg transition-all duration-300"></div>

        <form id="form-remoto" onsubmit="event.preventDefault(); registrarRemoto();" class="space-y-4">
            
            <!-- DNI y Código -->
            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1 truncate">DNI / DOCUMENTO <span class="text-rose-400">*</span></label>
                    <input type="text" id="r_dni" oninput="buscarUsuarioDNI()" maxlength="8" inputmode="numeric" class="w-full bg-slate-900/70 border border-white/10 rounded-xl px-2.5 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 font-bold tracking-wider" placeholder="Ej. 74829103" required>
                </div>
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1 truncate">CÓDIGO SESIÓN <span class="text-rose-400">*</span></label>
                    <input type="text" id="r_codigo_sesion" class="w-full bg-slate-900/70 border border-white/10 rounded-xl px-2.5 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm text-amber-300 placeholder-slate-500 focus:outline-none focus:border-amber-400 font-black uppercase tracking-widest text-center" placeholder="EJ. ABC123" required>
                </div>
            </div>

            <!-- Tarjeta de Confirmación -->
            <div id="card-verificacion-r" class="hidden bg-slate-900/90 border border-amber-400/30 rounded-2xl p-3 sm:p-4 transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-400/20 border border-amber-400/40 flex items-center justify-center text-amber-300 font-bold shrink-0 text-sm sm:text-base">
                            👤
                        </div>
                        <div class="space-y-0.5 min-w-0">
                            <p class="text-white font-black text-xs sm:text-sm uppercase tracking-wide truncate" id="txt-confirm-nombre-r">-</p>
                            <p class="text-slate-300 text-[10px] sm:text-[11px] font-medium truncate" id="txt-confirm-carrera-r">-</p>
                            <p class="text-amber-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider" id="txt-confirm-institucion-r">-</p>
                        </div>
                    </div>
                    <button type="button" onclick="limpiarDniRemoto()" class="bg-white/10 hover:bg-white/20 text-slate-200 hover:text-amber-300 border border-white/10 px-2.5 py-1 rounded-xl text-[10px] font-bold transition shrink-0 ml-2">
                        Cambiar
                    </button>
                </div>
            </div>

            <!-- Inputs invisibles -->
            <input type="hidden" id="r_nombres">
            <input type="hidden" id="r_especialidad">
            <input type="hidden" id="r_institucion">

            <!-- Actividad -->
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">ACTIVIDAD DESARROLLADA <span class="text-rose-400">*</span></label>
                <textarea id="r_actividad" rows="2" class="w-full bg-slate-900/70 border border-white/10 rounded-xl px-3 sm:px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 transition-all resize-none" placeholder="Escribe los avances logrados durante el día..." required></textarea>
            </div>

            <!-- Botón de Registro -->
            <button type="submit" id="btn-marcar-r" class="w-full py-3.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-black rounded-xl shadow-lg transition duration-200 text-xs uppercase tracking-wider flex justify-center items-center gap-2 mt-2 active:scale-[0.98]">
                <span>📲 REGISTRAR ASISTENCIA VIRTUAL</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let timerBusquedaR = null;

function buscarUsuarioDNI() {
    clearTimeout(timerBusquedaR);
    const dni = document.getElementById('r_dni').value.trim();

    if (dni.length < 8) {
        document.getElementById('card-verificacion-r').classList.add('hidden');
        document.getElementById('r_nombres').value = '';
        document.getElementById('r_especialidad').value = '';
        document.getElementById('r_institucion').value = '';
        return;
    }

    timerBusquedaR = setTimeout(async () => {
        try {
            const res = await fetch(`/api/practicante/buscar?dni=${encodeURIComponent(dni)}`);
            const data = await res.json();

            if (res.ok && data.encontrado) {
                document.getElementById('r_nombres').value = data.nombres || '';
                document.getElementById('r_especialidad').value = data.especialidad || '';
                document.getElementById('r_institucion').value = data.institucion || 'OTRO';

                document.getElementById('txt-confirm-nombre-r').innerText = data.nombres || '';
                document.getElementById('txt-confirm-carrera-r').innerText = data.especialidad || '';
                document.getElementById('txt-confirm-institucion-r').innerText = data.institucion || 'OTRO';

                document.getElementById('card-verificacion-r').classList.remove('hidden');
            } else {
                document.getElementById('card-verificacion-r').classList.add('hidden');
            }
        } catch (e) {
            console.error(e);
        }
    }, 200);
}

function limpiarDniRemoto() {
    document.getElementById('r_dni').value = '';
    document.getElementById('r_nombres').value = '';
    document.getElementById('r_especialidad').value = '';
    document.getElementById('r_institucion').value = '';
    document.getElementById('card-verificacion-r').classList.add('hidden');
    document.getElementById('r_dni').focus();
}

async function registrarRemoto() {
    const dni = document.getElementById('r_dni').value.trim();
    const codigo_sesion = document.getElementById('r_codigo_sesion').value.trim();
    const nombres = document.getElementById('r_nombres').value.trim();
    const especialidad = document.getElementById('r_especialidad').value.trim();
    const institucion = document.getElementById('r_institucion').value.trim() || 'OTRO';
    const actividad = document.getElementById('r_actividad').value.trim();

    if (!dni || dni.length < 8) return mostrarAlerta('Ingresa un DNI válido de 8 dígitos', 'error');
    if (!codigo_sesion) return mostrarAlerta('Ingresa el Código de Sesión Virtual', 'error');
    if (!nombres) return mostrarAlerta('No se encontraron datos asociados a este DNI', 'error');
    if (!actividad) return mostrarAlerta('Escribe la actividad desarrollada', 'error');

    try {
        const res = await fetch('/api/asistencia/remota', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                institucion: institucion,
                dni: dni,
                codigo_temporal: codigo_sesion,
                nombres: nombres,
                especialidad: especialidad,
                actividad: actividad
            })
        });

        const data = await res.json();
        if (res.ok && data.ok) {
            mostrarAlerta(`¡REGISTRO VIRTUAL EXITOSO!<br><span class="text-white text-sm">${nombres}</span><br><span class="text-amber-200 text-xs font-normal">Hora: ${data.hora}</span>`, 'exito');
            limpiarDniRemoto();
            document.getElementById('r_codigo_sesion').value = '';
            document.getElementById('r_actividad').value = '';
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
    box.className = `p-4 rounded-2xl text-center font-bold text-xs backdrop-blur-md border shadow-xl ${tipo === 'exito' ? 'bg-emerald-500/30 text-emerald-100 border-emerald-400/60' : 'bg-rose-500/30 text-rose-100 border-rose-400/60'}`;
    box.classList.remove('hidden');
}
</script>
@endpush