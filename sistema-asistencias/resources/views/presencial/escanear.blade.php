@extends('layouts.app')

@section('title', 'Escanear QR Presencial - CID PUJLLAY')

@section('content')
<div class="w-full max-w-lg mx-auto my-auto px-4 py-6 flex flex-col items-center justify-center min-h-[85vh]">
    
    <!-- Botón Volver -->
    <div class="mb-4 text-center w-full">
        <a href="{{ route('welcome') }}" class="inline-flex items-center text-xs font-bold text-slate-300 hover:text-amber-300 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al menú principal
        </a>
    </div>

    <!-- Contenedor Principal de la Cámara en Vivo -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-3xl shadow-2xl p-5 sm:p-6 w-full text-center space-y-4">
        
        <div class="space-y-1">
            <h1 class="text-lg font-black text-white tracking-tight">Escanea el QR de la Oficina</h1>
            <p class="text-xs text-amber-300 font-medium">Apunta la cámara al código QR ubicado en CID Pujllay</p>
        </div>

        <!-- Alerta Dinámica -->
        <div id="alerta-escaner" class="hidden p-3 rounded-2xl text-center font-bold text-xs shadow-lg"></div>

        <!-- Visor de la Cámara en Vivo -->
        <div id="reader" class="w-full rounded-2xl overflow-hidden border-2 border-amber-400/50 bg-slate-950 shadow-inner"></div>

        <div id="estado-camara" class="text-xs text-slate-300 animate-pulse">
            Iniciando cámara de escaneo...
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrCode = null;

document.addEventListener('DOMContentLoaded', () => {
    iniciarCamaraEnVivo();
});

function iniciarCamaraEnVivo() {
    html5QrCode = new Html5Qrcode("reader");
    
    const config = { fps: 15, qrbox: { width: 240, height: 240 } };

    html5QrCode.start(
        { facingMode: "environment" }, 
        config,
        async (decodedText) => {
            // Código QR detectado en tiempo real por la cámara
            validarTokenQrDetectado(decodedText);
        },
        (errorMessage) => {
            // Ignoramos errores de cuadros por segundo vacíos mientras busca
        }
    ).catch(err => {
        document.getElementById('estado-camara').innerText = "No se pudo acceder a la cámara. Revisa los permisos.";
        mostrarAlerta("Permiso de cámara denegado o no disponible.", "error");
    });
}

async function validarTokenQrDetectado(token) {
    // Calculamos el token esperado del día (ej. CIDPUJLLAY-2026-10-16)
    const hoy = new Date();
    const tokenEsperado = `CIDPUJLLAY-${hoy.getFullYear()}-${String(hoy.getMonth() + 1).padStart(2, '0')}-${String(hoy.getDate()).padStart(2, '0')}`;

    if (token.trim() === tokenEsperado) {
        if (html5QrCode && html5QrCode.isScanning) {
            await html5QrCode.stop();
        }

        mostrarAlerta("¡Código QR correcto! Redirigiendo al formulario...", "exito");
        document.getElementById('estado-camara').innerText = "¡Verificado con éxito!";

        // Redirige de inmediato al formulario original de asistencia
        setTimeout(() => {
            window.location.href = "{{ route('asistencia.presencial.formulario') }}"; // Ajusta tu ruta al formulario original
        }, 1200);
    } else {
        mostrarAlerta("Código QR inválido o de un día anterior.", "error");
    }
}

function mostrarAlerta(msg, tipo) {
    const box = document.getElementById('alerta-escaner');
    box.innerHTML = msg;
    box.className = `p-3 rounded-2xl text-center font-bold text-xs backdrop-blur-md border shadow-xl ${tipo === 'exito' ? 'bg-emerald-500/30 text-emerald-100 border-emerald-400/60' : 'bg-rose-500/30 text-rose-100 border-rose-400/60'}`;
    box.classList.remove('hidden');
}
</script>
@endpushs