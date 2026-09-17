@extends('layouts.app')

@section('title', 'Registro Remoto de Asistencia')

@section('content')
<div class="max-w-md mx-auto w-full bg-white rounded-3xl shadow-2xl p-6 sm:p-8 border border-gray-100">
    <!-- Encabezado con badge -->
    <div class="text-center mb-6">
        <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
            Modalidad Remota
        </span>
        <h2 class="text-2xl font-black text-gray-900">Asistencia desde Casa</h2>
        <p class="text-gray-500 text-sm mt-1">Ingresa el código temporal proporcionado por tu administrador y tu código personal</p>
    </div>

    <!-- Alerta de Respuesta -->
    <div id="alerta-remoto" class="hidden mb-6 p-4 rounded-2xl text-center font-medium text-sm transition-all duration-300"></div>

    <!-- Formulario Remoto -->
    <form id="form-remoto" onsubmit="event.preventDefault(); registrarRemoto();" class="space-y-5">
        <div>
            <label for="codigo_temporal" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                Código Temporal de Sesión
            </label>
            <input 
                type="text" 
                id="codigo_temporal" 
                name="codigo_temporal"
                class="w-full text-center text-xl font-mono tracking-widest px-4 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none transition uppercase" 
                placeholder="EJ. ABC123" 
                required
            >
        </div>

        <div>
            <label for="codigo_usuario" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                Tu Código de Usuario / DNI
            </label>
            <input 
                type="text" 
                id="codigo_usuario" 
                name="codigo_usuario"
                class="w-full text-center text-xl tracking-wider px-4 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none transition uppercase" 
                placeholder="EJ. U12345" 
                required
            >
        </div>

        <button 
            type="submit" 
            id="btn-marcar"
            class="w-full bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 transition duration-150 text-base mt-2 flex justify-center items-center">
            📲 Marcar Asistencia Remota
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
async function registrarRemoto() {
    const inputTemp = document.getElementById('codigo_temporal');
    const inputUsuario = document.getElementById('codigo_usuario');
    const btn = document.getElementById('btn-marcar');
    
    const codigo_temporal = inputTemp.value.trim();
    const codigo_usuario = inputUsuario.value.trim();

    if (!codigo_temporal || !codigo_usuario) {
        mostrarAlerta('Por favor completa ambos campos.', 'error');
        return;
    }

    // Deshabilitar botón durante la petición
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');

    try {
        const response = await fetch('/api/asistencia/remota', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                codigo_temporal: codigo_temporal,
                codigo_usuario: codigo_usuario
            })
        });

        const data = await response.json();

        if (response.ok && data.ok) {
            const mensaje = `¡Asistencia Remota Registrada!<br><strong>${data.nombre}</strong> (${data.carrera})<br>Hora: ${data.hora}`;
            mostrarAlerta(mensaje, 'exito');
            inputTemp.value = '';
            inputUsuario.value = '';
        } else {
            // Error de expirado, no válido o usado
            mostrarAlerta(data.error || 'Código temporal vencido o datos incorrectos.', 'error');
        }
    } catch (err) {
        mostrarAlerta('Error de conexión con el servidor.', 'error');
    } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function mostrarAlerta(mensaje, tipo) {
    const alerta = document.getElementById('alerta-remoto');
    alerta.innerHTML = mensaje;
    alerta.classList.remove('hidden', 'bg-emerald-100', 'text-emerald-800', 'border-emerald-400', 'bg-rose-100', 'text-rose-800', 'border-rose-400');
    
    if (tipo === 'exito') {
        alerta.classList.add('bg-emerald-100', 'text-emerald-800', 'border', 'border-emerald-400');
    } else {
        alerta.classList.add('bg-rose-100', 'text-rose-800', 'border', 'border-rose-400');
    }

    setTimeout(() => {
        alerta.classList.add('hidden');
    }, 5000);
}
</script>
@endpush