@extends('layouts.app')

@section('title', 'Iniciar Sesión - CID PUJLLAY')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4">
    <div class="bg-[#0f1d36]/90 border border-blue-900/40 p-8 sm:p-10 rounded-3xl shadow-2xl backdrop-blur-md max-w-md w-full space-y-6 relative overflow-hidden">
        
        {{-- Detalle decorativo superior --}}
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="text-center space-y-2 relative z-10">
            <span class="text-xs font-semibold tracking-wider text-indigo-400 uppercase">Panel Administrativo</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Iniciar Sesión</h2>
            <p class="text-slate-400 text-xs sm:text-sm">Acceso restringido para administradores y monitores de CID PUJLLAY.</p>
        </div>

        <form id="form-login" class="space-y-4 relative z-10">
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Correo Electrónico</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">✉️</span>
                    <input type="email" id="email" required placeholder="admin@sistema.com"
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 placeholder-slate-600 text-sm rounded-xl pl-11 pr-4 py-3 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">🔒</span>
                    <input type="password" id="password" required placeholder="••••••••"
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 placeholder-slate-600 text-sm rounded-xl pl-11 pr-4 py-3 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
            </div>

            <div id="login-error" class="hidden p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs text-center font-semibold"></div>

            <button type="submit" class="w-full mt-2 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition shadow-lg shadow-indigo-600/30 cursor-pointer flex items-center justify-center gap-2">
                Ingresar al Panel 🚀
            </button>
        </form>

        <div class="text-center pt-2 border-t border-blue-900/30">
            <a href="/" class="text-xs text-slate-400 hover:text-indigo-400 transition font-medium">
                ← Volver a la página principal
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('form-login').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const errorDiv = document.getElementById('login-error');
    
    errorDiv.classList.add('hidden');

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

        if (response.ok && (data.token || data.access_token)) {
            const token = data.token || data.access_token;
            localStorage.setItem('auth_token', token);
            window.location.href = '/admin/dashboard';
        } else {
            errorDiv.innerText = data.message || data.error || 'Credenciales incorrectas. Verifique sus datos.';
            errorDiv.classList.remove('hidden');
        }
    } catch (err) {
        errorDiv.innerText = 'Error de conexión con el servidor.';
        errorDiv.classList.remove('hidden');
    }
});
</script>
@endpush