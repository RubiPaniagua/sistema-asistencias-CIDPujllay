@extends('layouts.app')

@section('title', 'Dashboard de Administración - CID PUJLLAY')

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
            <a href="/admin/justificaciones" class="px-4 py-2 bg-amber-500/20 hover:bg-amber-500 text-amber-300 hover:text-slate-950 font-medium rounded-xl text-sm transition shadow-sm border border-amber-500/30 flex items-center gap-1.5">
                📄 Justificaciones
            </a>
            <a href="/admin/usuarios" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium rounded-xl text-sm transition shadow-sm border border-slate-600/50">
                👥 Usuarios
            </a>
            <button onclick="confirmarSalida()" class="px-4 py-2 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white font-medium rounded-xl text-sm transition shadow-sm border border-rose-500/30">
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

        <div class="flex flex-col items-center bg-slate-900/90 p-6 rounded-2xl border border-indigo-500/40 min-w-[280px] shadow-xl z-10">
            <span class="text-xs text-slate-400 uppercase tracking-widest font-semibold mb-1">Código Activo</span>
            
            <!-- Código Display + Botón Copiar al Lado -->
            <div class="flex items-center justify-center gap-2 my-2 w-full">
                <span id="codigo-remoto-display" class="text-3xl font-mono font-black tracking-widest text-emerald-400">------</span>
                <button id="btn-copiar" onclick="copiarCodigoRemoto()" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-emerald-400 border border-slate-700 rounded-xl transition-all active:scale-95 flex items-center justify-center" title="Copiar código">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012-2m0 10h2m0 0h2m-2 0v2m0-2v-2"></path>
                    </svg>
                </button>
            </div>

            <span id="expiracion-display" class="text-xs text-indigo-300 mb-4 text-center">Sin sesión activa actualmente</span>
            <button 
                onclick="generarCodigoRemoto()" 
                class="w-full bg-emerald-500 hover:bg-emerald-400 active:scale-95 text-slate-950 font-bold py-3 px-5 rounded-xl shadow-lg shadow-emerald-500/20 transition duration-200 flex items-center justify-center gap-2 text-sm">
                ⚡ Generar Nuevo Código
            </button>
        </div>
    </div>

    <!-- Panel de Verificación de Auditoría Remota (Las 3 Marcas del Día) -->
    <div class="bg-slate-800/80 border border-slate-700/60 rounded-3xl shadow-xl overflow-hidden backdrop-blur-md p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-700/60 pb-4">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    🔍 Auditoría de Marcas Remotas de Hoy
                </h3>
                <p class="text-slate-400 text-xs">Control interno de las hasta 3 verificaciones realizadas por los practicantes remotos el día de hoy.</p>
            </div>
            <button onclick="cargarAuditoriaRemota()" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-semibold rounded-xl transition flex items-center gap-1.5 self-start">
                🔄 Actualizar
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-700/60 text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        <th class="py-3 px-4">Practicante</th>
                        <th class="py-3 px-4">Carrera</th>
                        <th class="py-3 px-4 text-center">Verificaciones Hoy</th>
                        <th class="py-3 px-4">Detalle de Horas Registradas</th>
                    </tr>
                </thead>
                <tbody id="tabla-auditoria-remota" class="divide-y divide-slate-700/40 text-sm text-slate-300">
                    <tr>
                        <td colspan="4" class="py-6 text-center text-slate-500">Cargando registros remotos de hoy...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Salida -->
<div id="modal-salir" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-3xl shadow-2xl max-w-md w-full mx-4 space-y-6 text-center transform transition-all scale-95" id="modal-contenido">
        <div class="w-16 h-16 bg-rose-500/10 border border-rose-500/20 text-rose-500 rounded-full flex items-center justify-center mx-auto text-2xl">
            🚪
        </div>
        
        <div class="space-y-2">
            <h3 class="text-xl font-bold text-white">¿Desea salir del Panel de Administración?</h3>
            <p class="text-slate-400 text-sm leading-relaxed">
                Se cerrará tu sesión actual y serás redirigido al menú principal del sistema de asistencias.
            </p>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button onclick="cancelarSalida()" class="flex-1 py-3 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-sm transition border border-slate-700">
                Cancelar
            </button>
            <button onclick="cerrarSesionConfirmado()" class="flex-1 py-3 px-4 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-xl text-sm transition shadow-lg shadow-rose-600/20">
                Sí, Salir
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const token = localStorage.getItem('auth_token');
if (!token) {
    window.location.href = '/admin/login';
} else {
    fetch('/api/me', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            localStorage.removeItem('auth_token');
            window.location.href = '/admin/login';
        }
    }).catch(() => {});
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

        const data = await response.json().catch(() => ({}));

        if (response.ok && (data.ok || data.codigo_temporal)) {
            const codigo = data.codigo_temporal || data.codigo;
            document.getElementById('codigo-remoto-display').innerText = codigo;
            const expiraTime = data.expira_en || data.expires_at;
            if (expiraTime) {
                const expira = new Date(expiraTime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                document.getElementById('expiracion-display').innerText = `Expira a las: ${expira}`;
            } else {
                document.getElementById('expiracion-display').innerText = 'Código generado correctamente';
            }
        } else {
            const errorMessage = data.message || data.error || `Error HTTP: ${response.status}`;
            alert('No se pudo generar: ' + errorMessage);
        }
    } catch (err) {
        console.error(err);
        alert('Error de conexión con el servidor o la API.');
    }
}

function copiarCodigoRemoto() {
    const codigo = document.getElementById('codigo-remoto-display').innerText.trim();
    if (codigo === '------') return;

    navigator.clipboard.writeText(codigo).then(() => {
        const btn = document.getElementById('btn-copiar');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = `<span class="text-xs font-bold text-emerald-400 px-1">¡Copiado!</span>`;
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 1500);
    }).catch(err => {
        console.error('Error al copiar:', err);
    });
}

// Cargar la auditoría de marcas remotas del día actual
async function cargarAuditoriaRemota() {
    const tbody = document.getElementById('tabla-auditoria-remota');
    
    try {
        const response = await fetch(`/api/auditoria-remota`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await response.json();
        tbody.innerHTML = '';

        if (response.ok && data.ok && data.data.length > 0) {
            data.data.forEach(info => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-700/30 transition";

                const totalMarcas = info.horas.length;
                let badgeProgreso = '';

                if (totalMarcas >= 3) {
                    badgeProgreso = `<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">3/3 Completado</span>`;
                } else if (totalMarcas === 2) {
                    badgeProgreso = `<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">2/3 Parcial</span>`;
                } else {
                    badgeProgreso = `<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">${totalMarcas}/3 Verificación</span>`;
                }

                const horasHtml = info.horas.map(h => `<span class="px-2.5 py-1 bg-slate-900 border border-slate-700 rounded-lg font-mono text-xs text-emerald-300">${h}</span>`).join(' ');

                tr.innerHTML = `
                    <td class="py-3 px-4 font-semibold text-white">${info.usuario}</td>
                    <td class="py-3 px-4 text-slate-400 text-xs">${info.carrera}</td>
                    <td class="py-3 px-4 text-center">${badgeProgreso}</td>
                    <td class="py-3 px-4 flex flex-wrap gap-2 items-center">${horasHtml || '<span class="text-slate-500 italic text-xs">Sin marcas registradas aún</span>'}</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="4" class="py-6 text-center text-slate-500 text-xs">No hay registros de asistencias remotas para el día de hoy.</td></tr>`;
        }
    } catch (e) {
        console.error(e);
        tbody.innerHTML = `<tr><td colspan="4" class="py-6 text-center text-rose-400 text-xs">Error al cargar la auditoría remota.</td></tr>`;
    }
}

// Lógica para confirmar y cerrar sesión
function confirmarSalida() {
    const modal = document.getElementById('modal-salir');
    modal.classList.remove('hidden');
}

function cancelarSalida() {
    const modal = document.getElementById('modal-salir');
    modal.classList.add('hidden');
}

function cerrarSesionConfirmado() {
    localStorage.removeItem('auth_token');
    window.location.href = '/'; // Redirecciona directamente al menú principal de asistencias
}

document.addEventListener('DOMContentLoaded', () => {
    cargarAuditoriaRemota();
});
</script>
@endpush