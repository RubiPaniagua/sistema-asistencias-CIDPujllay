@extends('layouts.app')

@section('title', 'Dashboard de Administración')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-12">
    <!-- Header Admin -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-800/80 border border-slate-700/60 p-6 rounded-3xl shadow-xl backdrop-blur-md">
        <div>
            <span class="text-indigo-400 font-semibold text-xs tracking-wider uppercase">Panel de Control</span>
            <h2 class="text-2xl font-bold text-white">Gestión de Asistencias</h2>
            <p class="text-slate-400 text-sm mt-0.5">Genera códigos temporales de acceso para los practicantes remotos.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="/admin/reportes" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium rounded-xl text-sm transition shadow-sm border border-slate-600/50">
                📊 Reportes
            </a>
            <a href="/admin/usuarios" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium rounded-xl text-sm transition shadow-sm border border-slate-600/50">
                👥 Usuarios
            </a>
            <button onclick="cerrarSesion()" class="px-4 py-2 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white font-medium rounded-xl text-sm transition shadow-sm border border-rose-500/30">
                Salir
            </button>
        </div>
    </div>

    <!-- Generador de Código Remoto -->
    <div class="bg-gradient-to-br from-indigo-950 via-indigo-900 to-slate-900 text-white rounded-3xl p-6 sm:p-10 shadow-2xl border border-indigo-500/30 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="space-y-3 text-center md:text-left z-10 max-w-xl">
            <span class="inline-block bg-indigo-500/20 text-indigo-300 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-indigo-500/30">Control Remoto</span>
            <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Sesión para Practicantes</h3>
            <p class="text-indigo-200 text-sm leading-relaxed">Haz clic en generar para otorgar un código de asistencia temporal. Este vencerá automáticamente de acuerdo con la configuración de la API.</p>
        </div>

        <div class="flex flex-col items-center bg-slate-900/90 p-6 rounded-2xl border border-indigo-500/40 min-w-[260px] shadow-xl z-10">
            <span class="text-xs text-slate-400 uppercase tracking-widest font-semibold mb-1">Código Activo</span>
            <span id="codigo-remoto-display" class="text-3xl font-mono font-black tracking-widest text-emerald-400 my-2">------</span>
            <span id="expiracion-display" class="text-xs text-indigo-300 mb-4 text-center">Sin sesión activa actualmente</span>
            <button 
                onclick="generarCodigoRemoto()" 
                class="w-full bg-emerald-500 hover:bg-emerald-400 active:scale-95 text-slate-950 font-bold py-3 px-5 rounded-xl shadow-lg shadow-emerald-500/20 transition duration-200 flex items-center justify-center gap-2 text-sm">
                ⚡ Generar Nuevo Código
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Verificar autenticación
const token = localStorage.getItem('auth_token');
if (!token) {
    window.location.href = '/admin/login';
}

async function generarCodigoRemoto() {
    try {
        const response = await fetch('/api/sesion-remota', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        // Intentar leer la respuesta como JSON
        const data = await response.json().catch(() => ({}));

        if (response.ok && (data.ok || data.codigo_temporal)) {
            document.getElementById('codigo-remoto-display').innerText = data.codigo_temporal || data.codigo;
            const expiraTime = data.expira_en || data.expires_at;
            if (expiraTime) {
                const expira = new Date(expiraTime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                document.getElementById('expiracion-display').innerText = `Expira a las: ${expira}`;
            } else {
                document.getElementById('expiracion-display').innerText = 'Código generado correctamente';
            }
        } else {
            // Muestra el error exacto que devuelva Laravel o el estado HTTP
            const errorMessage = data.message || data.error || `Error HTTP: ${response.status}`;
            alert('No se pudo generar: ' + errorMessage);
            console.error('Detalle del error API:', data);
        }
    } catch (err) {
        console.error(err);
        alert('Error de conexión con el servidor o la API.');
    }
}

function cerrarSesion() {
    localStorage.removeItem('auth_token');
    window.location.href = '/admin/login';
}
</script>
@endpush