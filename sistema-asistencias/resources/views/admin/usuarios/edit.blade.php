@extends('layouts.app')

@section('title', 'Editar Usuario - CID PUJLLAY')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-12">
    <div class="bg-[#0f1d36]/90 border border-blue-900/40 p-6 rounded-2xl shadow-xl backdrop-blur-md flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold tracking-wider text-indigo-400 uppercase">Panel de Administración</span>
            <h2 class="text-2xl font-bold text-white mt-1">Editar Usuario</h2>
            <p class="text-slate-400 text-sm mt-0.5">Modifica los datos del colaborador o practicante.</p>
        </div>
        <a href="/admin/usuarios" class="bg-slate-800/80 hover:bg-slate-700 text-slate-200 border border-slate-700/60 font-semibold px-4 py-2 rounded-xl text-sm transition shadow-sm">
            ← Volver
        </a>
    </div>

    <div class="bg-[#0f1d36]/90 border border-blue-900/40 p-8 rounded-2xl shadow-xl backdrop-blur-md">
        <form id="form-editar-usuario" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Código / DNI</label>
                    <div class="relative flex items-center">
                        <input type="text" id="codigo" value="{{ $usuario->codigo ?? $usuario->dni }}" required placeholder="Ingrese DNI..."
                            class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <button type="button" onclick="buscarPorDni()" class="absolute right-2 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg transition shadow">
                            🔍 Buscar
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Rol del Sistema</label>
                    <select id="rol" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="practicante" {{ $usuario->rol == 'practicante' ? 'selected' : '' }}>Practicante</option>
                        <option value="monitor" {{ $usuario->rol == 'monitor' ? 'selected' : '' }}>Monitor(a)</option>
                        <option value="admin" {{ $usuario->rol == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nombres</label>
                    <input type="text" id="nombres" value="{{ $usuario->nombres }}" required
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Apellidos</label>
                    <input type="text" id="apellidos" value="{{ $usuario->apellidos }}" required
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Modalidad</label>
                    <select id="modalidad" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="presencial" {{ $usuario->modalidad == 'presencial' ? 'selected' : '' }}>Presencial</option>
                        <option value="remoto" {{ $usuario->modalidad == 'remoto' ? 'selected' : '' }}>Remoto</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Carrera Profesional</label>
                    <select id="carrera_id" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="">Cargando carreras...</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-blue-900/30">
                <a href="/admin/usuarios" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-sm transition border border-slate-700">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const token = localStorage.getItem('auth_token');
if (!token) window.location.href = '/admin/login';

const carreraActualId = "{{ $usuario->carrera_id }}";

// Cargar las carreras vía API para rellenar el select de nombres de forma limpia
async function cargarCarreras() {
    try {
        const response = await fetch('/api/carreras', {
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        const select = document.getElementById('carrera_id');
        select.innerHTML = '<option value="">Seleccione una carrera...</option>';
        
        const listaCarreras = Array.isArray(data) ? data : (data.carreras || data.data || []);
        listaCarreras.forEach(c => {
            const selected = String(c.id) === String(carreraActualId) ? 'selected' : '';
            select.innerHTML += `<option value="${c.id}" ${selected}>${c.nombre}</option>`;
        });
    } catch (e) {
        console.error('Error al cargar carreras', e);
    }
}

// Buscar y autocompletar por DNI
async function buscarPorDni() {
    const query = document.getElementById('codigo').value.trim();
    if (!query) {
        alert('Por favor ingrese un DNI o código.');
        return;
    }

    try {
        const response = await fetch(`/api/practicante/buscar?dni=${query}`, {
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();

        if (response.ok && (data.usuario || data.practicante || data.data)) {
            const user = data.usuario || data.practicante || data.data;
            document.getElementById('nombres').value = user.nombres || user.nombre || '';
            document.getElementById('apellidos').value = user.apellidos || '';
            if (user.carrera_id) document.getElementById('carrera_id').value = user.carrera_id;
            if (user.modalidad) document.getElementById('modalidad').value = user.modalidad;
            alert('¡Datos autocompletados correctamente!');
        } else {
            alert('No se encontró ningún registro con ese DNI.');
        }
    } catch (err) {
        alert('Error al consultar los datos.');
    }
}

// Guardar cambios
document.getElementById('form-editar-usuario').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const datos = {
        codigo: document.getElementById('codigo').value,
        nombres: document.getElementById('nombres').value,
        apellidos: document.getElementById('apellidos').value,
        rol: document.getElementById('rol').value,
        modalidad: document.getElementById('modalidad').value,
        carrera_id: document.getElementById('carrera_id').value,
    };

    try {
        const response = await fetch(`/api/usuarios/{{ $usuario->id }}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(datos)
        });

        if (response.ok) {
            alert('Usuario actualizado correctamente.');
            window.location.href = '/admin/usuarios';
        } else {
            const errData = await response.json().catch(() => ({}));
            alert('Error al actualizar: ' + (errData.message || 'Verifique los datos.'));
        }
    } catch (err) {
        alert('Error de conexión con el servidor.');
    }
});

document.addEventListener('DOMContentLoaded', cargarCarreras);
</script>
@endpush