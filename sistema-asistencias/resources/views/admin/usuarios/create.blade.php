@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="max-w-xl mx-auto w-full bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Registrar Nuevo Usuario</h2>
        <p class="text-gray-500 text-sm">Completa los campos para dar de alta a un estudiante o administrador</p>
    </div>

    <form id="form-crear-usuario" onsubmit="event.preventDefault(); guardarUsuario();" class="space-y-4">
        <div>
            <label for="codigo" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Código / DNI</label>
            <input type="text" id="codigo" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
        </div>

        <div>
            <label for="nombre" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nombre Completo</label>
            <input type="text" id="nombre" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="rol" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Rol</label>
                <select id="rol" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
                    <option value="practicante">Practicante</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <div>
                <label for="modalidad" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Modalidad</label>
                <select id="modalidad" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
                    <option value="presencial">Presencial</option>
                    <option value="remoto">Remoto</option>
                </select>
            </div>
        </div>

        <div>
            <label for="carrera_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">ID Carrera</label>
            <input type="number" id="carrera_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-200 outline-none text-sm" placeholder="Ej. 1">
        </div>

        <div class="flex gap-3 pt-4">
            <a href="/admin/usuarios" class="w-1/2 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition text-sm">Cancelar</a>
            <button type="submit" class="w-1/2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow transition text-sm">Guardar</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const token = localStorage.getItem('auth_token');
if (!token) window.location.href = '/admin/login';

async function guardarUsuario() {
    const payload = {
        codigo: document.getElementById('codigo').value.trim(),
        nombre: document.getElementById('nombre').value.trim(),
        rol: document.getElementById('rol').value,
        modalidad: document.getElementById('modalidad').value,
        carrera_id: document.getElementById('carrera_id').value,
        activo: true
    };

    try {
        const response = await fetch('/api/usuarios', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`[cite: 1]
            },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            window.location.href = '/admin/usuarios';
        } else {
            alert('Error al guardar el usuario.');
        }
    } catch (err) {
        alert('Error de conexión.');
    }
}
</script>
@endpush