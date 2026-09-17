@extends('layouts.app')

@section('title', 'Dashboard de Administración')

@section('content')
<div class="space-y-8">
    <!-- Header Admin -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Panel de Control</h2>
            <p class="text-gray-500 text-sm">Gestiona asistencias y códigos de acceso remoto</p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/reportes" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-xl text-sm transition">Reportes</a>
            <a href="/admin/usuarios" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-xl text-sm transition">Usuarios</a>
            <button onclick="cerrarSesion()" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold px-4 py-2 rounded-xl text-sm transition">Salir</button>
        </div>
    </div>

    <!-- Generador de Código Remoto -->
    <div class="bg-indigo-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left">
            <span class="bg-indigo-800 text-indigo-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Control Remoto</span>
            <h3 class="text-2xl font-bold">Generar Sesión para Practicantes Remotos</h3>
            <p class="text-indigo-200 text-sm max-w-xl">El código generado vencerá automáticamente en el tiempo establecido por la API[cite: 1].</p>
        </div>

        <div class="flex flex-col items-center bg-indigo-800/50 p-4 rounded-2xl border border-indigo-700 min-w-[220px]">
            <span id="codigo-remoto-display" class="text-3xl font-mono font-black tracking-widest text-emerald-400 my-1">------</span>
            <span id="expiracion-display" class="text-xs text-indigo-300 mb-3">Sin sesión activa</span>
            <button 
                onclick="generarCodigoRemoto()" 
                class="w-full bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-indigo-950 font-extrabold py-2.5 px-4 rounded-xl shadow transition text-sm">
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
                'Authorization': `Bearer ${token}` // Auth Sanctum[cite: 1]
            }
        });

        const data = await response.json();

        if (response.ok && data.ok) {
            document.getElementById('codigo-remoto-display').innerText = data.codigo_temporal;
            const expira = new Date(data.expira_en).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            document.getElementById('expiracion-display').innerText = `Expira a las: ${expira}`;
        } else {
            alert(data.error || 'No se pudo generar el código.');
        }
    } catch (err) {
        alert('Error de conexión con el servidor.');
    }
}

function cerrarSesion() {
    localStorage.removeItem('auth_token');
    window.location.href = '/admin/login';
}
</script>
@endpush