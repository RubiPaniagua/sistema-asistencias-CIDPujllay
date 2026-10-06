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
        button, .btn, nav, aside, form, .dashboard-cards, .header-acciones {
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

<div class="max-w-7xl mx-auto space-y-8 pb-12">
    
    <!-- Header de la Sección -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-800/80 border border-slate-700/60 p-6 rounded-3xl shadow-xl backdrop-blur-md">
        <div>
            <span class="text-indigo-400 font-semibold text-xs tracking-wider uppercase">Módulo de Auditoría</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Reportes de Asistencia</h2>
            <p class="text-slate-400 text-sm mt-1">Filtra por colaborador o selecciona un rango temporal de asistencia.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 header-acciones">
            <a href="/admin/dashboard" class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium rounded-xl text-sm transition shadow-sm border border-slate-600/50 flex items-center gap-2">
                ← Volver al Dashboard
            </a>
            <!-- Botón de Impresión Física -->
            <button onclick="window.print()" class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl text-sm transition shadow-md flex items-center gap-2">
                🖨️ Imprimir
            </button>
            <!-- Botón de Vista PDF en pestaña aparte -->
            <button onclick="abrirPdf()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-sm transition shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                📄 Ver / Descargar PDF
            </button>
        </div>
    </div>

    <!-- Tarjetas de Métricas Rápidas (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 dashboard-cards">
        <div class="bg-slate-800/80 border border-slate-700/60 p-6 rounded-3xl shadow-xl flex items-center justify-between backdrop-blur-md">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Registros</p>
                <h3 id="kpi-total" class="text-3xl font-extrabold text-white mt-1">0</h3>
            </div>
            <div class="w-12 h-12 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400 text-xl font-bold border border-indigo-500/30">📋</div>
        </div>
        <div class="bg-slate-800/80 border border-slate-700/60 p-6 rounded-3xl shadow-xl flex items-center justify-between backdrop-blur-md">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">A Tiempo</p>
                <h3 id="kpi-atiempo" class="text-3xl font-extrabold text-emerald-400 mt-1">0</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-400 text-xl font-bold border border-emerald-500/30">✅</div>
        </div>
        <div class="bg-slate-800/80 border border-slate-700/60 p-6 rounded-3xl shadow-xl flex items-center justify-between backdrop-blur-md">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tardanzas</p>
                <h3 id="kpi-tardanza" class="text-3xl font-extrabold text-amber-400 mt-1">0</h3>
            </div>
            <div class="w-12 h-12 bg-amber-500/20 rounded-2xl flex items-center justify-center text-amber-400 text-xl font-bold border border-amber-500/30">⏱️</div>
        </div>
    </div>

    <!-- Barra de Filtros Simplificada y Ordenada -->
    <div class="bg-slate-800/80 border border-slate-700/60 p-6 rounded-3xl shadow-xl backdrop-blur-md">
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
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2">
                    🔍 Filtrar
                </button>
                <button type="button" onclick="limpiarFiltros()" class="bg-slate-700 hover:bg-slate-600 text-slate-300 py-2.5 px-3 rounded-xl text-sm transition flex items-center justify-center" title="Limpiar filtros">
                    🔄
                </button>
            </div>
        </form>
    </div>

    <!-- Área de Resultados y Tabla lista para Impresión / PDF -->
    <div class="reportes-print-area bg-slate-800/80 border border-slate-700/60 rounded-3xl shadow-xl overflow-hidden backdrop-blur-md">
        
        <!-- Encabezado exclusivo para PDF / Impresión -->
        <div class="print-header">
            <h2>CID PUJLLAY - REPORTE DE ASISTENCIA</h2>
            <p>Sistema de Gestión y Control de Asistencias • Generado el {{ date('d/m/Y H:i') }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-700/60 text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        <th class="py-4 px-6">Usuario</th>
                        <th class="py-4 px-6">Carrera</th>
                        <th class="py-4 px-6">Fecha</th>
                        <th class="py-4 px-6">Entrada</th>
                        <th class="py-4 px-6">Salida</th>
                        <th class="py-4 px-6">Modalidad</th>
                        <th class="py-4 px-6">Estado</th>
                    </tr>
                </thead>
                <tbody id="tabla-reportes" class="divide-y divide-slate-700/50 text-sm text-slate-300">
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-500">Cargando datos...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-slate-900/40 border-t border-slate-700/60 text-xs text-slate-400 flex items-center justify-between">
            <span>Mostrando registros filtrados del sistema de asistencias</span>
            <span>CID Pujllay 2026</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const token = localStorage.getItem('auth_token');
if (!token) window.location.href = '/admin/login';

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

    // Abre una nueva pestaña hacia la ruta de exportación PDF con los filtros actuales
    window.open(`/admin/reportes/pdf?${params.toString()}`, '_blank');
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
            const total = data.data.length;
            const aTiempo = data.data.filter(i => i.estado === 'a_tiempo').length;
            const tardanzas = data.data.filter(i => i.estado === 'tardanza').length;

            document.getElementById('kpi-total').innerText = total;
            document.getElementById('kpi-atiempo').innerText = aTiempo;
            document.getElementById('kpi-tardanza').innerText = tardanzas;

            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-700/30 transition";
                
                const badgeModalidad = item.modalidad === 'remoto' 
                    ? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">Remoto</span>'
                    : '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">Presencial</span>';

                const badgeEstado = item.estado === 'a_tiempo'
                    ? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">A tiempo</span>'
                    : '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Tardanza</span>';

                tr.innerHTML = `
                    <td class="py-4 px-6 font-medium text-white flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-300 font-bold flex items-center justify-center border border-indigo-500/30 text-xs">
                            ${(item.usuario || 'U').substring(0, 2).toUpperCase()}
                        </div>
                        ${item.usuario}
                    </td>
                    <td class="py-4 px-6 text-slate-400">${item.carrera || '-'}</td>
                    <td class="py-4 px-6">${item.fecha}</td>
                    <td class="py-4 px-6 font-mono text-xs text-emerald-400">${item.hora_entrada || '-'}</td>
                    <td class="py-4 px-6 font-mono text-xs text-amber-400">${item.hora_salida || '-'}</td>
                    <td class="py-4 px-6">${badgeModalidad}</td>
                    <td class="py-4 px-6">${badgeEstado}</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            document.getElementById('kpi-total').innerText = 0;
            document.getElementById('kpi-atiempo').innerText = 0;
            document.getElementById('kpi-tardanza').innerText = 0;
            tbody.innerHTML = `<tr><td colspan="7" class="py-12 text-center text-slate-500">No se encontraron registros de asistencia con los filtros seleccionados.</td></tr>`;
        }
    } catch (err) {
        document.getElementById('tabla-reportes').innerHTML = `<tr><td colspan="7" class="py-12 text-center text-rose-400 font-semibold">Error al cargar los reportes desde el servidor.</td></tr>`;
    }
}

function limpiarFiltros() {
    document.getElementById('form-filtros').reset();
    document.getElementById('contenedor-fecha').classList.add('hidden');
    cargarReportes();
}

document.addEventListener('DOMContentLoaded', cargarReportes);
</script>
@endpush