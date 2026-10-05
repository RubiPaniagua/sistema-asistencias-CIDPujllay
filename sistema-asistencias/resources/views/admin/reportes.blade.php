@extends('layouts.app')

@section('title', 'Reportes de Asistencia')

@section('content')
<div class="space-y-6">
    <!-- Encabezado con navegación de regreso -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Reportes de Asistencia</h2>
            <p class="text-gray-500 text-sm">Filtra y consulta los registros de asistencia presenciales y remotos[cite: 1]</p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/dashboard" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-xl text-sm transition flex items-center gap-1">
                ⬅️ Volver al Dashboard
            </a>
            <button onclick="window.print()" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold px-4 py-2 rounded-xl text-sm transition">
                🖨️ Imprimir
            </button>
        </div>
    </div>

    <!-- Barra de Filtros -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <form id="form-filtros" onsubmit="event.preventDefault(); cargarReportes();" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label for="desde" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Desde</label>
                <input type="date" id="desde" name="desde" class="w-full px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
            </div>

            <div>
                <label for="hasta" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Hasta</label>
                <input type="date" id="hasta" name="hasta" class="w-full px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
            </div>

            <div>
                <label for="modalidad" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Modalidad</label>
                <select id="modalidad" name="modalidad" class="w-full px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
                    <option value="">Todas</option>
                    <option value="presencial">Presencial</option>
                    <option value="remoto">Remoto</option>
                </select>
            </div>

            <div>
                <label for="estado" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Estado</label>
                <select id="estado" name="estado" class="w-full px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
                    <option value="">Todos</option>
                    <option value="a_tiempo">A tiempo</option>
                    <option value="tardanza">Tardanza</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl shadow transition text-sm">
                    🔍 Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabla de Resultados -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-semibold">
                        <th class="p-4">Usuario</th>
                        <th class="p-4">Carrera</th>
                        <th class="p-4">Fecha</th>
                        <th class="p-4">Entrada</th>
                        <th class="p-4">Salida</th>
                        <th class="p-4">Modalidad</th>
                        <th class="p-4">Estado</th>
                    </tr>
                </thead>
                <tbody id="tabla-reportes" class="divide-y divide-gray-100 text-gray-700">
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400">Cargando datos...</td>
                    </tr>
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

async function cargarReportes() {
    const desde = document.getElementById('desde').value;
    const hasta = document.getElementById('hasta').value;
    const modalidad = document.getElementById('modalidad').value;
    const estado = document.getElementById('estado').value;

    // Construcción dinámica de Query Params[cite: 1]
    const params = new URLSearchParams();
    if (desde) params.append('desde', desde);
    if (hasta) params.append('hasta', hasta);
    if (modalidad) params.append('modalidad', modalidad);
    if (estado) params.append('estado', estado);

    try {
        const response = await fetch(`/api/reportes?${params.toString()}`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}` // Auth Sanctum[cite: 1]
            }
        });

        const data = await response.json();
        const tbody = document.getElementById('tabla-reportes');
        tbody.innerHTML = '';

        if (response.ok && data.ok && data.data.length > 0) {
            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-gray-50 transition";
                
                const badgeModalidad = item.modalidad === 'presencial' 
                    ? '<span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-semibold">Presencial</span>'
                    : '<span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full font-semibold">Remoto</span>';

                const badgeEstado = item.estado === 'a_tiempo'
                    ? '<span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded-full font-semibold">A tiempo</span>'
                    : '<span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full font-semibold">Tardanza</span>';

                tr.innerHTML = `
                    <td class="p-4 font-bold text-gray-900">${item.usuario}</td>
                    <td class="p-4 text-gray-500">${item.carrera || '-'}</td>
                    <td class="p-4">${item.fecha}</td>
                    <td class="p-4 font-mono">${item.hora_entrada || '-'}</td>
                    <td class="p-4 font-mono">${item.hora_salida || '-'}</td>
                    <td class="p-4">${badgeModalidad}</td>
                    <td class="p-4">${badgeEstado}</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-gray-400">No se encontraron registros de asistencia con los filtros seleccionados.</td></tr>`;
        }
    } catch (err) {
        document.getElementById('tabla-reportes').innerHTML = `<tr><td colspan="7" class="p-8 text-center text-rose-500 font-semibold">Error al cargar los reportes desde el servidor.</td></tr>`;
    }
}

// Cargar reportes iniciales al abrir la página
document.addEventListener('DOMContentLoaded', cargarReportes);
</script>
@endpush