@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto px-2 sm:px-0">
    {{-- Cabecera Principal --}}
    <div class="bg-[#0f1d36]/90 border border-blue-900/40 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xl backdrop-blur-md flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="text-xs font-semibold tracking-wider text-indigo-400 uppercase">Módulo de Administración</span>
            <h2 class="text-xl sm:text-2xl font-bold text-white mt-1">Gestión de Usuarios</h2>
            <p class="text-slate-400 text-xs sm:text-sm mt-0.5">Administra practicantes y usuarios del sistema de asistencias.</p>
        </div>
        <!-- Botones superiores adaptados a móvil (2 en línea) y flex en escritorio -->
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 sm:gap-3">
            <a href="/admin/dashboard" class="bg-slate-800/80 hover:bg-slate-700 text-slate-200 border border-slate-700/60 font-semibold px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-2 shadow-sm text-center">
                ← Volver
            </a>
            <button onclick="abrirModalCrear()" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm shadow-lg shadow-indigo-600/30 transition flex items-center justify-center gap-2 cursor-pointer text-center">
                ➕ Nuevo Usuario
            </button>
        </div>
    </div>

    {{-- Tarjetas de Estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <div class="bg-[#0f1d36]/90 border border-blue-900/40 p-4 sm:p-5 rounded-2xl shadow-xl backdrop-blur-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Registrados</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1" id="stat-total">0</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-500/10 border border-indigo-500/20 rounded-xl flex items-center justify-center text-lg sm:text-xl shadow-inner">👥</div>
        </div>
        <div class="bg-[#0f1d36]/90 border border-blue-900/40 p-4 sm:p-5 rounded-2xl shadow-xl backdrop-blur-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Usuarios Activos</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-400 mt-1" id="stat-activos">0</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-lg sm:text-xl shadow-inner">✅</div>
        </div>
        <div class="bg-[#0f1d36]/90 border border-blue-900/40 p-4 sm:p-5 rounded-2xl shadow-xl backdrop-blur-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Monitores / Admins</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-400 mt-1" id="stat-monitores">0</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center justify-center text-lg sm:text-xl shadow-inner">🛡️</div>
        </div>
    </div>

    {{-- Barra de Filtros --}}
    <div class="bg-[#0f1d36]/90 border border-blue-900/40 p-4 sm:p-5 rounded-2xl shadow-xl backdrop-blur-md grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Buscar Usuario</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">🔍</span>
                <input type="text" id="input-buscar" placeholder="Nombre o código..." onkeyup="filtrarUsuarios()" 
                    class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 placeholder-slate-500 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Filtrar por Rol</label>
            <select id="filtro-rol" onchange="filtrarUsuarios()" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                <option value="">Todos los roles</option>
                <option value="practicante">Practicante</option>
                <option value="monitor">Monitor(a)</option>
                <option value="admin">Administrador</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Modalidad</label>
            <select id="filtro-modalidad" onchange="filtrarUsuarios()" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                <option value="">Ambas (Todas)</option>
                <option value="presencial">Presencial</option>
                <option value="remoto">Remoto</option>
            </select>
        </div>
    </div>

    {{-- Tabla de Datos --}}
    <div class="bg-[#0f1d36]/90 border border-blue-900/40 rounded-2xl shadow-xl backdrop-blur-md overflow-hidden">
        <div class="p-4 px-6 border-b border-blue-900/30 flex justify-between items-center bg-[#0a1426]/60">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider" id="contador-registros">Cargando registros...</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm min-w-[700px]">
                <thead>
                    <tr class="border-b border-blue-900/30 text-slate-400 font-semibold text-xs uppercase tracking-wider bg-[#0a1426]/40">
                        <th class="p-3 sm:p-4 px-4 sm:px-6">Código / DNI</th>
                        <th class="p-3 sm:p-4">Nombre / Colaborador</th>
                        <th class="p-3 sm:p-4">Rol</th>
                        <th class="p-3 sm:p-4">Modalidad</th>
                        <th class="p-3 sm:p-4">Estado</th>
                        <th class="p-3 sm:p-4 text-center px-4 sm:px-6">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-usuarios" class="divide-y divide-blue-900/20 text-xs sm:text-sm text-slate-300">
                    <tr><td colspan="6" class="p-8 text-center text-slate-500">Cargando usuarios...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Flotante de Crear Nuevo Usuario -->
<div id="modal-crear" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="bg-[#0f1d36] border border-blue-900/50 p-5 sm:p-8 rounded-2xl sm:rounded-3xl shadow-2xl max-w-xl w-full mx-4 space-y-4 sm:space-y-5">
        <div class="flex items-center justify-between border-b border-blue-900/30 pb-3">
            <div>
                <span class="text-xs font-semibold tracking-wider text-indigo-400 uppercase">Panel de Administración</span>
                <h3 class="text-lg sm:text-xl font-bold text-white mt-0.5">Registrar Nuevo Usuario</h3>
            </div>
            <button onclick="cerrarModalCrear()" class="text-slate-400 hover:text-white text-lg font-bold px-2 py-1 rounded-lg bg-slate-800/60">✕</button>
        </div>

        <form id="form-crear-usuario" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">DNI (8 dígitos)</label>
                    <input type="text" id="crear-dni" required maxlength="8"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)"
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner"
                        placeholder="Ej: 74827384">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Rol del Sistema</label>
                    <select id="crear-rol" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="practicante">Practicante</option>
                        <option value="monitor">Monitor(a)</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nombres</label>
                    <input type="text" id="crear-nombres" required placeholder="Ej: Juan"
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Apellidos</label>
                    <input type="text" id="crear-apellidos" required placeholder="Ej: Pérez Gómez"
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Modalidad</label>
                    <select id="crear-modalidad" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="presencial">Presencial</option>
                        <option value="remoto">Remoto</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Carrera</label>
                    <select id="crear-carrera_id" onchange="verificarNuevaCarrera('crear')" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="">Seleccione...</option>
                        @foreach(\App\Models\Carrera::all() as $carrera)
                            <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                        @endforeach
                        <option value="NUEVA" class="text-indigo-400 font-bold">➕ Agregar nueva...</option>
                    </select>
                    <input type="text" id="crear-nueva-carrera" placeholder="Escriba nueva carrera..." class="hidden mt-2 w-full bg-[#0a1426] border border-indigo-500/60 text-slate-200 text-xs rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Institución</label>
                    <select id="crear-institucion_id" onchange="verificarNuevaInstitucion('crear')" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="">Seleccione...</option>
                        @foreach(\App\Models\Institucion::all() as $institucion)
                            <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                        @endforeach
                        <option value="NUEVA" class="text-indigo-400 font-bold">➕ Agregar nueva...</option>
                    </select>
                    <input type="text" id="crear-nueva-institucion" placeholder="Escriba nueva institución..." class="hidden mt-2 w-full bg-[#0a1426] border border-indigo-500/60 text-slate-200 text-xs rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-blue-900/30">
                <button type="button" onclick="cerrarModalCrear()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-sm transition border border-slate-700">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">
                    Registrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Flotante de Editar Usuario -->
<div id="modal-editar" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="bg-[#0f1d36] border border-blue-900/50 p-5 sm:p-8 rounded-2xl sm:rounded-3xl shadow-2xl max-w-xl w-full mx-4 space-y-4 sm:space-y-5" id="modal-contenido">
        <div class="flex items-center justify-between border-b border-blue-900/30 pb-3">
            <div>
                <span class="text-xs font-semibold tracking-wider text-indigo-400 uppercase">Panel de Administración</span>
                <h3 class="text-lg sm:text-xl font-bold text-white mt-0.5">Editar Usuario</h3>
            </div>
            <button onclick="cerrarModalEditar()" class="text-slate-400 hover:text-white text-lg font-bold px-2 py-1 rounded-lg bg-slate-800/60">✕</button>
        </div>

        <form id="form-editar-usuario" class="space-y-4">
            <input type="hidden" id="edit-id">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">DNI (8 dígitos)</label>
                    <input type="text" id="edit-codigo" required maxlength="8"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)"
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Rol del Sistema</label>
                    <select id="edit-rol" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="practicante">Practicante</option>
                        <option value="monitor">Monitor(a)</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nombres</label>
                    <input type="text" id="edit-nombres" required
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Apellidos</label>
                    <input type="text" id="edit-apellidos" required
                        class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Modalidad</label>
                    <select id="edit-modalidad" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="presencial">Presencial</option>
                        <option value="remoto">Remoto</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Carrera</label>
                    <select id="edit-carrera_id" onchange="verificarNuevaCarrera('edit')" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="">Seleccione...</option>
                        @foreach(\App\Models\Carrera::all() as $carrera)
                            <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                        @endforeach
                        <option value="NUEVA" class="text-indigo-400 font-bold">➕ Agregar nueva...</option>
                    </select>
                    <input type="text" id="edit-nueva-carrera" placeholder="Escriba nueva carrera..." class="hidden mt-2 w-full bg-[#0a1426] border border-indigo-500/60 text-slate-200 text-xs rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Institución</label>
                    <select id="edit-institucion_id" onchange="verificarNuevaInstitucion('edit')" class="w-full bg-[#0a1426] border border-blue-900/50 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 transition shadow-inner">
                        <option value="">Seleccione...</option>
                        @foreach(\App\Models\Institucion::all() as $institucion)
                            <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                        @endforeach
                        <option value="NUEVA" class="text-indigo-400 font-bold">➕ Agregar nueva...</option>
                    </select>
                    <input type="text" id="edit-nueva-institucion" placeholder="Escriba nueva institución..." class="hidden mt-2 w-full bg-[#0a1426] border border-indigo-500/60 text-slate-200 text-xs rounded-lg px-3 py-2">
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

let usuariosGlobales = [];

async function cargarUsuarios() {
    try {
        const response = await fetch('/api/usuarios', {
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        
        if (response.ok && data.ok) {
            usuariosGlobales = data.data || data.usuarios || [];
            actualizarEstadisticas(usuariosGlobales);
            mostrarUsuarios(usuariosGlobales);
        } else {
            document.getElementById('tabla-usuarios').innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-400 font-semibold">Error al obtener los datos del servidor.</td></tr>`;
        }
    } catch (err) {
        document.getElementById('tabla-usuarios').innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-400 font-semibold">Error de conexión con la API.</td></tr>`;
    }
}

function actualizarEstadisticas(lista) {
    document.getElementById('stat-total').innerText = lista.length;
    document.getElementById('stat-activos').innerText = lista.filter(u => u.activo == 1 || u.activo === true).length;
    document.getElementById('stat-monitores').innerText = lista.filter(u => u.rol === 'monitor' || u.rol === 'admin').length;
}

function mostrarUsuarios(lista) {
    const tbody = document.getElementById('tabla-usuarios');
    const contador = document.getElementById('contador-registros');
    tbody.innerHTML = '';

    contador.innerText = `Mostrando ${lista.length} usuario(s) registrado(s)`;

    if (lista.length > 0) {
        lista.forEach(user => {
            const tr = document.createElement('tr');
            tr.className = "hover:bg-blue-950/30 transition";
            
            const activoVal = user.activo == 1 || user.activo === true;
            const badgeActivo = activoVal 
                ? '<span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs px-2.5 py-1 rounded-lg font-semibold whitespace-nowrap">Activo</span>'
                : '<span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 text-xs px-2.5 py-1 rounded-lg font-semibold whitespace-nowrap">Inactivo</span>';

            const rolTexto = (user.rol || 'practicante').toLowerCase();
            const badgeRol = (rolTexto === 'monitor' || rolTexto === 'admin')
                ? '<span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 text-xs px-2.5 py-1 rounded-lg font-semibold capitalize whitespace-nowrap">' + rolTexto + '</span>'
                : '<span class="bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 text-xs px-2.5 py-1 rounded-lg font-semibold capitalize whitespace-nowrap">' + rolTexto + '</span>';

            const nombreCompleto = `${user.nombres || ''} ${user.apellidos || ''}`.trim() || user.nombre || 'Sin Nombre';
            const iniciales = nombreCompleto.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            const codigoIdentificacion = user.codigo || user.dni || 'S/N';

            let accionesHtml = `
                <button onclick="abrirModalEditar(${user.id})" class="text-indigo-400 hover:text-indigo-300 font-semibold text-xs bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/20 px-3 py-1.5 rounded-lg transition whitespace-nowrap">Editar</button>
            `;

            if (activoVal) {
                accionesHtml += `
                    <button onclick="cambiarEstado(${user.id})" class="text-amber-400 hover:text-amber-300 font-semibold text-xs bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 px-3 py-1.5 rounded-lg transition whitespace-nowrap">Desactivar</button>
                `;
            } else {
                accionesHtml += `
                    <button onclick="cambiarEstado(${user.id})" class="text-emerald-400 hover:text-emerald-300 font-semibold text-xs bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 px-3 py-1.5 rounded-lg transition whitespace-nowrap">Activar</button>
                    <button onclick="eliminarPermanente(${user.id})" class="text-rose-400 hover:text-rose-300 font-semibold text-xs bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 px-3 py-1.5 rounded-lg transition whitespace-nowrap">Eliminar</button>
                `;
            }

            tr.innerHTML = `
                <td class="p-3 sm:p-4 px-4 sm:px-6 font-mono font-bold text-slate-200 whitespace-nowrap">${codigoIdentificacion}</td>
                <td class="p-3 sm:p-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs shrink-0">${iniciales}</div>
                        <span class="font-semibold text-white">${nombreCompleto}</span>
                    </div>
                </td>
                <td class="p-3 sm:p-4 whitespace-nowrap">${badgeRol}</td>
                <td class="p-3 sm:p-4 capitalize whitespace-nowrap text-slate-300">${user.modalidad || 'Presencial'}</td>
                <td class="p-3 sm:p-4 whitespace-nowrap">${badgeActivo}</td>
                <td class="p-3 sm:p-4 px-4 sm:px-6 text-center space-x-2 whitespace-nowrap">${accionesHtml}</td>
            `;
            tbody.appendChild(tr);
        });
    } else {
        tbody.innerHTML = `<tr><td colspan="6" class="p-10 text-center text-slate-500">No se encontraron usuarios.</td></tr>`;
    }
}

function filtrarUsuarios() {
    const texto = document.getElementById('input-buscar').value.toLowerCase();
    const rolSeleccionado = document.getElementById('filtro-rol').value.toLowerCase();
    const modalidadSeleccionada = document.getElementById('filtro-modalidad').value.toLowerCase();

    const filtrados = usuariosGlobales.filter(user => {
        const nombreCompleto = `${user.nombres || ''} ${user.apellidos || ''}`.toLowerCase();
        const codigo = (user.codigo || '').toLowerCase();
        const dni = (user.dni || '').toLowerCase();

        const nombreMatch = nombreCompleto.includes(texto) || codigo.includes(texto) || dni.includes(texto);
        const rolMatch = rolSeleccionado === '' || (user.rol || '').toLowerCase() === rolSeleccionado;
        const modalidadMatch = modalidadSeleccionada === '' || (user.modalidad || '').toLowerCase() === modalidadSeleccionada;

        return nombreMatch && rolMatch && modalidadMatch;
    });

    mostrarUsuarios(filtrados);
}

function verificarNuevaCarrera(prefix) {
    const select = document.getElementById(`${prefix}-carrera_id`);
    const input = document.getElementById(`${prefix}-nueva-carrera`);
    if (select.value === 'NUEVA') {
        input.classList.remove('hidden');
        input.focus();
    } else {
        input.classList.add('hidden');
    }
}

function verificarNuevaInstitucion(prefix) {
    const select = document.getElementById(`${prefix}-institucion_id`);
    const input = document.getElementById(`${prefix}-nueva-institucion`);
    if (select.value === 'NUEVA') {
        input.classList.remove('hidden');
        input.focus();
    } else {
        input.classList.add('hidden');
    }
}

function abrirModalCrear() {
    document.getElementById('form-crear-usuario').reset();
    document.getElementById('crear-nueva-carrera').classList.add('hidden');
    document.getElementById('crear-nueva-institucion').classList.add('hidden');
    document.getElementById('modal-crear').classList.remove('hidden');
}

function cerrarModalCrear() {
    document.getElementById('modal-crear').classList.add('hidden');
}

document.getElementById('form-crear-usuario').addEventListener('submit', async (e) => {
    e.preventDefault();
    const dniInput = document.getElementById('crear-dni').value;

    if (dniInput.length !== 8) {
        alert('El DNI debe tener exactamente 8 dígitos.');
        return;
    }

    const datos = {
        dni: dniInput,
        nombres: document.getElementById('crear-nombres').value,
        apellidos: document.getElementById('crear-apellidos').value,
        rol: document.getElementById('crear-rol').value,
        modalidad: document.getElementById('crear-modalidad').value,
        carrera_id: document.getElementById('crear-carrera_id').value,
        nueva_carrera: document.getElementById('crear-nueva-carrera').value,
        institucion_id: document.getElementById('crear-institucion_id').value,
        nueva_institucion: document.getElementById('crear-nueva-institucion').value,
    };

    try {
        const response = await fetch('/api/usuarios', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(datos)
        });

        if (response.ok) {
            cerrarModalCrear();
            window.location.reload();
        } else {
            const errData = await response.json().catch(() => ({}));
            alert('Error al registrar: ' + (errData.message || 'Verifique los datos.'));
        }
    } catch (err) {
        alert('Error de conexión con el servidor.');
    }
});

function abrirModalEditar(id) {
    const user = usuariosGlobales.find(u => u.id === id);
    if (!user) return;

    document.getElementById('edit-id').value = user.id;
    document.getElementById('edit-codigo').value = user.codigo || user.dni || '';
    document.getElementById('edit-nombres').value = user.nombres || '';
    document.getElementById('edit-apellidos').value = user.apellidos || '';
    document.getElementById('edit-rol').value = user.rol || 'practicante';
    document.getElementById('edit-modalidad').value = user.modalidad || 'presencial';
    document.getElementById('edit-carrera_id').value = user.carrera_id || '';
    document.getElementById('edit-institucion_id').value = user.institucion_id || '';

    document.getElementById('edit-nueva-carrera').classList.add('hidden');
    document.getElementById('edit-nueva-institucion').classList.add('hidden');

    document.getElementById('modal-editar').classList.remove('hidden');
}

function cerrarModalEditar() {
    document.getElementById('modal-editar').classList.add('hidden');
}

document.getElementById('form-editar-usuario').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('edit-id').value;
    const dniInput = document.getElementById('edit-codigo').value;

    if (dniInput.length !== 8) {
        alert('El DNI debe tener exactamente 8 dígitos.');
        return;
    }
    
    const datos = {
        codigo: dniInput,
        dni: dniInput,
        nombres: document.getElementById('edit-nombres').value,
        apellidos: document.getElementById('edit-apellidos').value,
        rol: document.getElementById('edit-rol').value,
        modalidad: document.getElementById('edit-modalidad').value,
        carrera_id: document.getElementById('edit-carrera_id').value,
        nueva_carrera: document.getElementById('edit-nueva-carrera').value,
        institucion_id: document.getElementById('edit-institucion_id').value,
        nueva_institucion: document.getElementById('edit-nueva-institucion').value,
    };

    try {
        const response = await fetch(`/api/usuarios/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(datos)
        });

        if (response.ok) {
            cerrarModalEditar();
            window.location.reload();
        } else {
            const errData = await response.json().catch(() => ({}));
            alert('Error al actualizar: ' + (errData.message || 'Verifique los datos.'));
        }
    } catch (err) {
        alert('Error de conexión con el servidor.');
    }
});

async function cambiarEstado(id) {
    try {
        const response = await fetch(`/api/usuarios/${id}/toggle`, {
            method: 'PATCH',
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            cargarUsuarios();
        } else {
            alert('No se pudo cambiar el estado del usuario.');
        }
    } catch (err) {
        alert('Error de conexión con el servidor.');
    }
}

async function eliminarPermanente(id) {
    if (!confirm('¿Estás seguro de eliminar permanentemente este usuario inactivo?')) return;
    try {
        const response = await fetch(`/api/usuarios/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            cargarUsuarios();
        } else {
            alert('No se pudo eliminar el usuario.');
        }
    } catch (err) {
        alert('Error de conexión con el servidor.');
    }
}

document.addEventListener('DOMContentLoaded', cargarUsuarios);
</script>
@endpush