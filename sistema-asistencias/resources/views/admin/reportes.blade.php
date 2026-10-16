@extends('layouts.app')

@section('title', 'Reportes de Asistencia')

@section('content')
<style>
    /* Estilos específicos para impresión / PDF limpio */
    @media print {
        body * {
            visibility: hidden;
        }

        .reportes-print-area, .reportes-print-area * {
            visibility: visible;
        }

        .reportes-print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100% !important;
            background: white !important;
            color: black !important;
        }

        /* Ocultar elementos que no van en el reporte impreso */
        button, .btn, nav, aside, form, .dashboard-cards, .header-acciones, .col-acciones {
            display: none !important;
        }

        /* Mostrar el encabezado formal de impresión */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .print-header h2 {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .print-header p {
            font-size: 12px;
            color: #475569;
            margin: 5px 0 0 0;
        }

        /* Tabla limpia y legible para PDF */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #cbd5e1 !important;
            padding: 8px 10px !important;
            color: #0f172a !important;
            font-size: 11px !important;
            text-align: left;
        }

        th {
            background-color: #f1f5f9 !important;
            font-weight: bold;
        }

        /* Remover avatares o fondos oscuros en impresión */
        td div.rounded-full {
            display: none !important;
        }
        
        span {
            background: transparent !important;
            color: #0f172a !important;
            border: none !important;
            padding: 0 !important;
        }
    }

    /* Ocultar encabezado formal en la vista web normal */
    .print-header {
        display: none;
    }
</style>

<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8 pb-12 w-full px-2 sm:px-0">
    
    <!-- Header de la Sección -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-800/80 border border-slate-700/60 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xl backdrop-blur-md">
        <div>
            <span class="text-indigo-400 font-semibold text-xs tracking-wider uppercase">Módulo de Auditoría</span>
            <h2 class="text-xl sm:text-3xl font-extrabold text-white tracking-tight">Reportes de Asistencia</h2>
            <p class="text-slate-400 text-xs sm:text-sm mt-1">Filtra por colaborador o selecciona un rango temporal de asistencia.</p>
        </div>
        <!-- Botones superiores adaptados a móvil (2x2) y flex en escritorio -->
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 sm:gap-3 header-acciones">
            <a href="/admin/dashboard" class="px-3 sm:px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium rounded-xl text-xs sm:text-sm transition shadow-sm border border-slate-600/50 flex items-center justify-center gap-2 text-center">
                ← Volver
            </a>
            <!-- Botón de Impresión Física -->
            <button onclick="window.print()" class="px-3 sm:px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl text-xs sm:text-sm transition shadow-md flex items-center justify-center gap-2 cursor-pointer text-center">
                🖨️ Imprimir
            </button>
            <!-- Botón de Vista PDF en pestaña aparte -->
            <button onclick="abrirPdf()" class="col-span-2 sm:col-span-1 px-3 sm:px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-xs sm:text-sm transition shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2 cursor-pointer text-center">
                📄 Ver / Descargar PDF
            </button>
        </div>
    </div>

    <!-- Tarjetas de Métricas Rápidas (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 dashboard-cards">
        <div class="bg-slate-800/80 border border-slate-700/60 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xl flex items-center justify-between backdrop-blur-md">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Registros</p>
                <h3 id="kpi-total" class="text-2xl sm:text-3xl font-extrabold text-white mt-1">0</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-500/20 rounded-xl sm:rounded-2xl flex items-center justify-center text-indigo-400 text-lg sm:text-xl font-bold border border-indigo-500/30">📋</div>
        </div>
        <div class="bg-slate-800/80 border border-slate-700/60 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xl flex items-center justify-between backdrop-blur-md">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">A Tiempo</p>
                <h3 id="kpi-atiempo" class="text-2xl sm:text-3xl font-extrabold text-emerald-400 mt-1">0</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-500/20 rounded-xl sm:rounded-2xl flex items-center justify-center text-emerald-400 text-lg sm:text-xl font-bold border border-emerald-500/30">✅</div>
        </div>
        <div class="bg-slate-800/80 border border-slate-700/60 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xl flex items-center justify-between backdrop-blur-md">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tardanzas</p>
                <h3 id="kpi-tardanza" class="text-2xl sm:text-3xl font-extrabold text-amber-400 mt-1">0</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-500/20 rounded-xl sm:rounded-2xl flex items-center justify-center text-amber-400 text-lg sm:text-xl font-bold border border-amber-500/30">⏱️</div>
        </div>
    </div>

    <!-- Barra de Filtros Simplificada y Ordenada -->
    <div class="bg-slate-800/80 border border-slate-700/60 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xl backdrop-blur-md">
        <form id="form-filtros" onsubmit="event.preventDefault(); cargarReportes();" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            
            <!-- 1. Filtrar por Usuario -->
            <div>
                <label for="usuario" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Filtrar por Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="Nombre del practicante..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 placeholder-slate-600">
            </div>

            <!-- 2. Tipo de Filtro Temporal -->
            <div>
                <label for="tipo_filtro" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Filtrar por Periodo</label>
                <select id="tipo_filtro" name="tipo_filtro" onchange="cambiarTipoFiltro()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="">Todos los registros</option>
                    <option value="fecha">Fecha Específica</option>
                    <option value="semana">Esta Semana</option>
                    <option value="mes">Este Mes</option>
                </select>
            </div>

            <!-- 3. Input Dinámico de Fecha (Aparece solo si elige "fecha") -->
            <div id="contenedor-fecha" class="hidden">
                <label for="fecha" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Seleccionar Fecha</label>
                <input type="date" id="fecha" name="fecha" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <!-- 4. Modalidad (Presencial / Remoto) -->
            <div>
                <label for="modalidad" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Modalidad</label>
                <select id="modalidad" name="modalidad" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="">Ambas (Todas)</option>
                    <option value="presencial">Presencial</option>
                    <option value="remoto">Remoto</option>
                </select>
            </div>

            <!-- Botones de Acción -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2 cursor-pointer">
                    🔍 Filtrar
                </button>
                <button type="button" onclick="limpiarFiltros()" class="bg-slate-700 hover:bg-slate-600 text-slate-300 py-2.5 px-3 rounded-xl text-sm transition flex items-center justify-center cursor-pointer" title="Limpiar filtros">
                    🔄
                </button>
            </div>
        </form>
    </div>

    <!-- Área de Resultados y Tabla lista para Impresión / PDF -->
    <div class="reportes-print-area bg-slate-800/80 border border-slate-700/60 rounded-2xl sm:rounded-3xl shadow-xl overflow-hidden backdrop-blur-md">
        
        <!-- Encabezado exclusivo para PDF / Impresión -->
        <div class="print-header">
            <h2>CID PUJLLAY - REPORTE DE ASISTENCIA</h2>
            <p>Sistema de Gestión y Control de Asistencias • Generado el {{ date('d/m/Y H:i') }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-700/60 text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        <th class="py-3 sm:py-4 px-4 sm:px-6">Usuario</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6">Carrera</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6">Fecha</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6">Entrada</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6">Salida</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6">Modalidad</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6">Estado</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6 col-acciones text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-reportes" class="divide-y divide-slate-700/50 text-xs sm:text-sm text-slate-300">
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-500">Cargando datos...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-slate-900/40 border-t border-slate-700/60 text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>Mostrando registros filtrados del sistema de asistencias</span>
            <span>CID Pujllay 2026</span>
        </div>
    </div>
</div>

<!-- Modal Flotante Completo para Editar Entrada, Salida, Modalidad y Estado -->
<div id="modal-editar-asistencia" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="bg-[#0f1d36] border border-blue-900/50 p-5 sm:p-8 rounded-2xl sm:rounded-3xl shadow-2xl max-w-md w-full mx-4 space-y-4 sm:space-y-5">
        <div class="flex items-center justify-between border-b border-blue-900/30 pb-3">
            <div>
                <span class="text-xs font-semibold tracking-wider text-indigo-400 uppercase">Panel de Control</span>
                <h3 class="text-base sm:text-lg font-bold text-white mt-0.5">Editar Registro de Asistencia</h3>
            </div>
            <button onclick="cerrarModalEditar()" class="text-slate-400 hover:text-white font-bold text-lg px-2 py-1 rounded-lg bg-slate-800/60">✕</button>
        </div>

        <form id="form-editar-asistencia" class="space-y-4">
            <input type="hidden" id="edit-asistencia-id">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Hora Entrada</label>
                    <input type="time" id="edit-hora-entrada" step="1" required
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Hora Salida</label>
                    <input type="time" id="edit-hora-salida" step="1" required
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Modalidad</label>
                    <select id="edit-modalidad" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="presencial">Presencial</option>
                        <option value="remoto">Remoto</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Estado</label>
                    <select id="edit-estado" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="A tiempo">A tiempo</option>
                        <option value="Tardanza">Tardanza</option>
                        <option value="Justificado">Justificado</option>
                        <option value="Falta">Falta</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-blue-900/30">
                <button type="button" onclick="cerrarModalEditar()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-sm transition border border-slate-700">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">
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

let reportesGlobales = [];

function cambiarTipoFiltro() {
    const tipo = document.getElementById('tipo_filtro').value;
    const contenedorFecha = document.getElementById('contenedor-fecha');
    
    if (tipo === 'fecha') {
        contenedorFecha.classList.remove('hidden');
    } else {
        contenedorFecha.classList.add('hidden');
        document.getElementById('fecha').value = '';
    }
}

function abrirPdf() {
    const usuario = document.getElementById('usuario').value;
    const tipoFiltro = document.getElementById('tipo_filtro').value;
    const fecha = document.getElementById('fecha').value;
    const modalidad = document.getElementById('modalidad').value;

    const params = new URLSearchParams();
    if (usuario) params.append('usuario', usuario);
    if (tipoFiltro) params.append('tipo_filtro', tipoFiltro);
    if (fecha) params.append('fecha', fecha);
    if (modalidad) params.append('modalidad', modalidad);

    window.open(`/admin/reportes/pdf?${params.toString()}`, '_blank');
}

function obtenerHoraSalida(item) {
    const modalidadLower = (item.modalidad || '').toLowerCase();
    
    if (modalidadLower === 'remoto') {
        const fechaRegistro = item.fecha ? item.fecha.split('T')[0] : '';
        const hoy = new Date().toLocaleDateString('en-CA');

        const ahora = new Date();
        const horaActual = ahora.getHours();
        
        const esDiaAnterior = fechaRegistro < hoy;
        const esHoyPasadoTreceds = (fechaRegistro === hoy && horaActual >= 13);

        if (esDiaAnterior || esHoyPasadoTreceds) {
            if (item.hora_salida && item.hora_salida !== '-' && item.hora_salida !== 'Pendiente' && !item.hora_salida.includes('13:00:00')) {
                return item.hora_salida;
            }
            return '13:00:00';
        } else {
            if (item.hora_salida && item.hora_salida !== '-' && item.hora_salida !== 'Pendiente' && !item.hora_salida.includes('13:00:00')) {
                return item.hora_salida;
            }
            return 'Pendiente';
        }
    }

    return (item.hora_salida && item.hora_salida !== '-') ? item.hora_salida : 'Pendiente';
}

async function cargarReportes() {
    const usuario = document.getElementById('usuario').value;
    const tipoFiltro = document.getElementById('tipo_filtro').value;
    const fecha = document.getElementById('fecha').value;
    const modalidad = document.getElementById('modalidad').value;

    const params = new URLSearchParams();
    if (usuario) params.append('usuario', usuario);
    if (tipoFiltro) params.append('tipo_filtro', tipoFiltro);
    if (fecha) params.append('fecha', fecha);
    if (modalidad) params.append('modalidad', modalidad);

    try {
        const response = await fetch(`/api/reportes?${params.toString()}`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await response.json();
        const tbody = document.getElementById('tabla-reportes');
        tbody.innerHTML = '';

        if (response.ok && data.ok && data.data.length > 0) {
            reportesGlobales = data.data;
            const total = data.data.length;
            const aTiempo = data.data.filter(i => i.estado.toLowerCase() === 'a_tiempo' || i.estado.toLowerCase() === 'a tiempo').length;
            const tardanzas = data.data.filter(i => i.estado.toLowerCase() === 'tardanza').length;

            document.getElementById('kpi-total').innerText = total;
            document.getElementById('kpi-atiempo').innerText = aTiempo;
            document.getElementById('kpi-tardanza').innerText = tardanzas;

            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-700/30 transition";
                
                const modalidadLower = (item.modalidad || '').toLowerCase();
                const badgeModalidad = modalidadLower === 'remoto' 
                    ? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">Remoto</span>'
                    : '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">Presencial</span>';

                let badgeEstado = '';
                const estadoLower = (item.estado || '').toLowerCase();

                if (estadoLower.includes('justificado')) {
                    badgeEstado = '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">Justificado</span>';
                } else if (estadoLower.includes('tardanza')) {
                    badgeEstado = '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Tardanza</span>';
                } else if (estadoLower.includes('falta')) {
                    badgeEstado = '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">Falta</span>';
                } else {
                    badgeEstado = '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">A tiempo</span>';
                }

                const fechaLimpia = item.fecha ? item.fecha.split('T')[0] : '-';
                const horaSalidaCalculada = obtenerHoraSalida(item);

                tr.innerHTML = `
                    <td class="py-3 sm:py-4 px-4 sm:px-6 font-medium text-white flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-300 font-bold flex items-center justify-center border border-indigo-500/30 text-xs shrink-0">
                            ${(item.usuario || 'U').substring(0, 2).toUpperCase()}
                        </div>
                        <span class="whitespace-nowrap">${item.usuario}</span>
                    </td>
                    <td class="py-3 sm:py-4 px-4 sm:px-6 text-slate-400 whitespace-nowrap">${item.carrera || '-'}</td>
                    <td class="py-3 sm:py-4 px-4 sm:px-6 whitespace-nowrap">${fechaLimpia}</td>
                    <td class="py-3 sm:py-4 px-4 sm:px-6 font-mono text-xs text-emerald-400 whitespace-nowrap">${item.hora_entrada || '-'}</td>
                    <td class="py-3 sm:py-4 px-4 sm:px-6 font-mono text-xs text-amber-400 whitespace-nowrap">${horaSalidaCalculada}</td>
                    <td class="py-3 sm:py-4 px-4 sm:px-6 whitespace-nowrap">${badgeModalidad}</td>
                    <td class="py-3 sm:py-4 px-4 sm:px-6 whitespace-nowrap">${badgeEstado}</td>
                    <td class="py-3 sm:py-4 px-4 sm:px-6 col-acciones text-center whitespace-nowrap">
                        <button onclick="abrirModalEditar(${item.id})" 
                            class="text-indigo-400 hover:text-indigo-300 font-semibold text-xs bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/20 px-3 py-1.5 rounded-lg transition cursor-pointer">
                            ✏️ Editar
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            document.getElementById('kpi-total').innerText = 0;
            document.getElementById('kpi-atiempo').innerText = 0;
            document.getElementById('kpi-tardanza').innerText = 0;
            tbody.innerHTML = `<tr><td colspan="8" class="py-12 text-center text-slate-500">No se encontraron registros de asistencia con los filtros seleccionados.</td></tr>`;
        }
    } catch (err) {
        document.getElementById('tabla-reportes').innerHTML = `<tr><td colspan="8" class="py-12 text-center text-rose-400 font-semibold">Error al cargar los reportes desde el servidor.</td></tr>`;
    }
}

function abrirModalEditar(id) {
    const item = reportesGlobales.find(r => r.id === id);
    if (!item) return;

    document.getElementById('edit-asistencia-id').value = item.id;
    document.getElementById('edit-hora-entrada').value = (item.hora_entrada && item.hora_entrada !== '-') ? item.hora_entrada : '08:00:00';
    
    const horaSalidaVal = obtenerHoraSalida(item);
    document.getElementById('edit-hora-salida').value = horaSalidaVal === 'Pendiente' ? '13:00:00' : horaSalidaVal;
    
    document.getElementById('edit-modalidad').value = (item.modalidad || 'presencial').toLowerCase();

    const est = (item.estado || '').toLowerCase();
    if (est.includes('tardanza')) document.getElementById('edit-estado').value = 'Tardanza';
    else if (est.includes('justificado')) document.getElementById('edit-estado').value = 'Justificado';
    else if (est.includes('falta')) document.getElementById('edit-estado').value = 'Falta';
    else document.getElementById('edit-estado').value = 'A tiempo';

    document.getElementById('modal-editar-asistencia').classList.remove('hidden');
}

function cerrarModalEditar() {
    document.getElementById('modal-editar-asistencia').classList.add('hidden');
}

document.getElementById('form-editar-asistencia').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('edit-asistencia-id').value;
    let horaEntrada = document.getElementById('edit-hora-entrada').value;
    let horaSalida = document.getElementById('edit-hora-salida').value;
    
    if (horaEntrada && horaEntrada.length === 5) horaEntrada += ':00';
    if (horaSalida && horaSalida.length === 5) horaSalida += ':00';

    const datos = {
        hora_entrada: horaEntrada,
        hora_salida: horaSalida,
        modalidad: document.getElementById('edit-modalidad').value,
        estado: document.getElementById('edit-estado').value,
    };

    try {
        const response = await fetch(`/api/asistencias/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(datos)
        });

        const resData = await response.json();

        if (response.ok && resData.ok) {
            cerrarModalEditar();
            cargarReportes();
        } else {
            alert('No se pudo actualizar: ' + (resData.message || 'Verifique los campos.'));
        }
    } catch (err) {
        alert('Error de conexión con el servidor.');
    }
});

function limpiarFiltros() {
    document.getElementById('form-filtros').reset();
    document.getElementById('contenedor-fecha').classList.add('hidden');
    cargarReportes();
}

document.addEventListener('DOMContentLoaded', cargarReportes);
</script>
@endpush