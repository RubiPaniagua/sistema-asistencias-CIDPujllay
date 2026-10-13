@extends('layouts.app')

@section('title', 'Formulario de Justificación - CID PUJLLAY')

@section('content')
<div class="w-full max-w-xl mx-auto my-auto px-2 sm:px-4 py-3 sm:py-6 overflow-x-hidden">
    
    <!-- Botón Volver al menú principal -->
    <div class="mb-3 sm:mb-5 text-center">
        <a href="{{ route('welcome') }}" class="inline-flex items-center text-xs font-bold text-slate-300 hover:text-amber-300 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al Menú Principal
        </a>
    </div>

    <!-- Contenedor Tarjeta Fija y Simétrica -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-3xl shadow-2xl overflow-hidden p-5 sm:p-8 space-y-4 sm:space-y-6">
        
        <!-- Header con Icono/Logo -->
        <div class="text-center border-b border-white/10 pb-4">
            <div class="inline-block p-3 rounded-2xl bg-amber-400/20 border border-amber-400/40 text-amber-300 mb-2 shadow-lg">
                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h1 class="text-lg sm:text-xl font-black text-white tracking-tight">Formulario de Justificación</h1>
            <p class="text-[11px] sm:text-xs text-amber-300 font-bold tracking-wide mt-0.5">Envía tus datos para análisis de ausencia o tardanza</p>
        </div>

        <!-- Alertas -->
        @if(session('success'))
        <div class="p-4 rounded-2xl text-center font-bold text-xs bg-emerald-500/25 text-emerald-200 border border-emerald-500/50 shadow-lg">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 rounded-2xl text-xs bg-rose-500/25 text-rose-200 border border-rose-500/50 shadow-lg space-y-1">
            <p class="font-bold text-center">Por favor corrige los campos indicados.</p>
        </div>
        @endif

        <!-- Formulario -->
        <form action="{{ route('justificaciones.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Identificación / DNI con Autocompletado -->
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">ID o DNI del Practicante <span class="text-rose-400">*</span></label>
                <div class="relative">
                    <input type="text" id="j_dni" name="identificacion" oninput="buscarUsuarioDNI()" maxlength="8" inputmode="numeric" class="w-full bg-slate-900/80 border border-white/15 rounded-xl px-4 py-3 text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 font-bold tracking-wider" placeholder="Digita tu DNI de 8 dígitos" autocomplete="off" required value="{{ old('identificacion') }}">
                    <span id="loader-dni" class="hidden absolute right-3 top-3.5 text-amber-300 text-xs animate-spin">🔄</span>
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

            <!-- Tipo de Falta y Fecha en 2 columnas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Tipo de Falta <span class="text-rose-400">*</span></label>
                    <select name="tipo" class="w-full bg-slate-900/80 border border-white/15 rounded-xl px-3 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-amber-400 font-bold" required>
                        <option value="" disabled selected>Seleccione...</option>
                        <option value="tardanza" {{ old('tipo') == 'tardanza' ? 'selected' : '' }}>Tardanza</option>
                        <option value="inasistencia" {{ old('tipo') == 'inasistencia' ? 'selected' : '' }}>Inasistencia</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Fecha de Ausencia / Atraso <span class="text-rose-400">*</span></label>
                    <input type="date" name="fecha" class="w-full bg-slate-900/80 border border-white/15 rounded-xl px-3 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-amber-400 font-bold text-center" required value="{{ old('fecha', date('Y-m-d')) }}">
                </div>
            </div>

            <!-- Motivo o Detalle -->
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Detalle de la Justificación <span class="text-rose-400">*</span></label>
                <textarea name="motivo" rows="3" class="w-full bg-slate-900/80 border border-white/15 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 transition-all resize-none" placeholder="Explique brevemente el motivo..." required>{{ old('motivo') }}</textarea>
            </div>

            <!-- Evidencia o Archivo Adjunto -->
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">Comprobante o Evidencia <span class="text-slate-400 font-normal">(Opcional)</span></label>
                <div class="flex items-center gap-2 bg-slate-900/80 border border-white/15 rounded-xl px-3 py-2">
                    <input type="file" name="evidencia" class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-400 file:text-slate-950 hover:file:bg-amber-300 cursor-pointer">
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Permitido: Imágenes (JPG, PNG) o archivos PDF (máx. 5MB).</p>
            </div>

            <!-- Botón de Envío -->
            <button type="submit" class="w-full py-3.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-black rounded-xl shadow-lg transition duration-200 text-xs uppercase tracking-wider flex justify-center items-center gap-2 mt-2 active:scale-[0.98]">
                <span>ENVIAR JUSTIFICACIÓN</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let timerBusqueda = null;

function buscarUsuarioDNI() {
    clearTimeout(timerBusqueda);
    const dni = document.getElementById('j_dni').value.trim();

    if (dni.length < 8) {
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
    document.getElementById('j_dni').value = '';
    document.getElementById('card-verificacion').classList.add('hidden');
    document.getElementById('j_dni').focus();
}
</script>
@endpush