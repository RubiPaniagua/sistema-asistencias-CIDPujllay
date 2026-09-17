@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h2>
            <p class="text-gray-500 text-sm">Administra practicantes y usuarios del sistema</p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/dashboard" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-xl text-sm transition">
                ⬅️ Dashboard
            </a>
            <a href="/admin/usuarios/crear" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-xl text-sm shadow transition">
                ➕ Nuevo Usuario
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-semibold">
                        <th class="p-4">Código</th>
                        <th class="p-4">Nombre</th>
                        <th class="p-4">Rol</th>
                        <th class="p-4">Modalidad</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-usuarios" class="divide-y divide-gray-100 text-gray-700">
                    <tr><td colspan="6" class="p-8 text-center text-gray-400">Cargando usuarios...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const token = localStorage.getItem('auth_token');
if (!token) window.location.href = '/admin/login';

async function cargarUsuarios() {
    try {
        const response = await fetch('/api/usuarios', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`[cite: 1]
            }
        });

        const data = await response.json();
        const tbody = document.getElementById('tabla-usuarios');
        tbody.innerHTML = '';

        if (response.ok && data.length > 0) {
            data.forEach(user => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-gray-50 transition";
                
                const badgeActivo = user.activo 
                    ? '<span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-semibold">Activo</span>'
                    : '<span class="bg-rose-100 text-rose-700 text-xs px-2.5 py-1 rounded-full font-semibold">Inactivo</span>';

                tr.innerHTML = `
                    <td class="p-4 font-mono font-bold text-gray-900">${user.codigo}</td>
                    <td class="p-4 font-semibold">${user.nombre}</td>
                    <td class="p-4 capitalize">${user.rol}</td>
                    <td class="p-4 capitalize">${user.modalidad || 'N/A'}</td>
                    <td class="p-4">${badgeActivo}</td>
                    <td class="p-4 text-center space-x-2">
                        <a href="/admin/usuarios/${user.id}/editar" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs">Editar</a>
                        <button onclick="eliminarUsuario(${user.id})" class="text-rose-600 hover:text-rose-900 font-bold text-xs">Desactivar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-gray-400">No hay usuarios registrados.</td></tr>`;
        }
    } catch (err) {
        document.getElementById('tabla-usuarios').innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-500 font-semibold">Error al cargar usuarios.</td></tr>`;
    }
}

async function eliminarUsuario(id) {
    if (!confirm('¿Estás seguro de desactivar este usuario?')) return;

    try {
        const response = await fetch(`/api/usuarios/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`[cite: 1]
            }
        });

        if (response.ok) {
            cargarUsuarios();
        } else {
            alert('No se pudo desactivar el usuario.');
        }
    } catch (err) {
        alert('Error al conectar con el servidor.');
    }
}

document.addEventListener('DOMContentLoaded', cargarUsuarios);
</script>
@endpush