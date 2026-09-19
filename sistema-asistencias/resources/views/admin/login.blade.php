@extends('layouts.app')

@section('title', 'Acceso Administrativo')

@section('content')
<div class="max-w-md mx-auto w-full bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-gray-900">Iniciar Sesión</h2>
        <p class="text-gray-500 text-sm mt-1">Acceso restringido para administradores</p>
    </div>

    <div id="alerta-login" class="hidden mb-6 p-4 rounded-xl text-center font-medium text-sm transition-all duration-300"></div>

    <form id="form-login" onsubmit="event.preventDefault(); iniciarSesion();" class="space-y-5">
        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Correo Electrónico</label>
            <input 
                type="email" 
                id="email" 
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none transition" 
                placeholder="admin@cidpujllay.org" 
                required
            >
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Contraseña</label>
            <input 
                type="password" 
                id="password" 
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none transition" 
                placeholder="••••••••" 
                required
            >
        </div>

        <button 
            type="submit" 
            id="btn-login"
            class="w-full bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold py-3.5 rounded-xl shadow-lg transition duration-150">
            Ingresar al Panel
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
async function iniciarSesion() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const alerta = document.getElementById('alerta-login');

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (response.ok && data.ok) {
            // Guardar token Sanctum en localStorage
            localStorage.setItem('auth_token', data.token);
            window.location.href = '/admin/dashboard';
        } else {
            alerta.innerText = data.error || 'Credenciales incorrectas.';
            alerta.className = 'mb-6 p-4 rounded-xl text-center font-medium text-sm bg-rose-100 text-rose-800 border border-rose-400 block';
        }
    } catch (err) {
        alerta.innerText = 'Error al conectar con el servidor.';
        alerta.className = 'mb-6 p-4 rounded-xl text-center font-medium text-sm bg-rose-100 text-rose-800 border border-rose-400 block';
    }
}
</script>
@endpush