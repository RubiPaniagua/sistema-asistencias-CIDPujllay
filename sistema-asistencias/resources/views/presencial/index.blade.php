@extends('layouts.app')

@section('title', 'Kiosco Presencial - Registro de Asistencia')

@section('content')
<div class="max-w-xl mx-auto w-full bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
    <!-- Encabezado -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Registro Presencial</h2>
        <p class="text-gray-500 text-sm">Ingresa tu código de usuario para registrar tu entrada o salida</p>
    </div>

    <!-- Alerta dinámica de respuesta (Éxito / Error) -->
    <div id="alerta-mensaje" class="hidden mb-6 p-4 rounded-xl text-center font-medium transition-all duration-300"></div>

    <!-- Formulario Kiosco -->
    <form id="form-presencial" onsubmit="event.preventDefault();" class="space-y-6">
        <div>
            <label for="codigo_usuario" class="block text-sm font-semibold text-gray-700 mb-2">Código de Usuario / DNI</label>
            <input 
                type="text" 
                id="codigo_usuario" 
                name="codigo"
                class="w-full text-center text-2xl tracking-widest px-4 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none transition uppercase" 
                placeholder="Ej. U12345" 
                autofocus 
                required
            >
        </div>

        <!-- Botones de Acción -->
        <div class="grid grid-cols-2 gap-4 pt-2">
            <button 
                type="button" 
                onclick="registrarAsistencia('entrada')" 
                class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold py-4 rounded-xl shadow-lg transition duration-150 flex items-center justify-center text-lg">
                🟢 Entrada
            </button>
            <button 
                type="button" 
                onclick="registrarAsistencia('salida')" 
                class="w-full bg-amber-600 hover:bg-amber-700 active:scale-95 text-white font-bold py-4 rounded-xl shadow-lg transition duration-150 flex items-center justify-center text-lg">
                🔴 Salida
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
async function registrarAsistencia(tipo) {
    const inputCodigo = document.getElementById('codigo_usuario');
    const alerta = document.getElementById('alerta-mensaje');
    const codigo = inputCodigo.value.trim();

    if (!codigo) {
        mostrarAlerta('Por favor ingresa tu código de usuario.', 'error');
        return;
    }

    const endpoint = tipo === 'entrada' ? '/api/asistencia/entrada' : '/api/asistencia/salida';

    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ codigo: codigo })
        });

        const data = await response.json();

        if (response.ok && data.ok) {
            const mensaje = `¡Asistencia (${tipo.toUpperCase()}) Registrada!<br><strong>${data.nombre}</strong> (${data.carrera})<br>Hora: ${data.hora}`;
            mostrarAlerta(mensaje, 'exito');
            inputCodigo.value = '';
        } else {
            // Maneja error 404 retornado por el backend
            mostrarAlerta(data.error || 'Código no válido o no autorizado', 'error');
        }
    } catch (err) {
        mostrarAlerta('Error de conexión con el servidor de asistencias.', 'error');
    } finally {
        inputCodigo.focus();
    }
}

function mostrarAlerta(mensaje, tipo) {
    const alerta = document.getElementById('alerta-mensaje');
    alerta.innerHTML = mensaje;
    alerta.classList.remove('hidden', 'bg-emerald-100', 'text-emerald-800', 'border-emerald-400', 'bg-rose-100', 'text-rose-800', 'border-rose-400');
    
    if (tipo === 'exito') {
        alerta.classList.add('bg-emerald-100', 'text-emerald-800', 'border', 'border-emerald-400');
    } else {
        alerta.classList.add('bg-rose-100', 'text-rose-800', 'border', 'border-rose-400');
    }

    // Ocultar la alerta automáticamente después de 4 segundos
    setTimeout(() => {
        alerta.classList.add('hidden');
    }, 4000);
}
</script>
@endpush