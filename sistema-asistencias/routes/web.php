<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JustificacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CarreraController;

//YA NO SE USA
/*use App\Http\Controllers\Api\AsistenciaController;*/

// Página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- Rutas de API / Asistencia ---
// TODO ESTO QUEDO COMENTADO, ESAS RUTAS SON DE ENDPOINTS DE API Y YA ESTA VIVIENDO EN ROUTES API.PHP
/*
Route::get('/api/practicante/buscar', [AsistenciaController::class, 'buscarPorDni']);
Route::post('/api/asistencia/entrada', [AsistenciaController::class, 'marcarEntrada']);
Route::post('/api/asistencia/salida', [AsistenciaController::class, 'marcarEntrada']);
Route::post('/api/asistencia/remoto', [AsistenciaController::class, 'marcarRemoto']);
*/

// Ruta de Asistencia Remota
Route::get('/remoto', function () {
    return view('remoto.index');
})->name('asistencia.remota');

// Ruta de Asistencia Presencial
Route::get('/presencial', function () {
    return view('presencial.index');
})->name('asistencia.presencial');

// Formulario de justificaciones
Route::get('/justificaciones/crear', [JustificacionController::class, 'create'])
    ->name('justificaciones.create');

Route::post('/justificaciones', [JustificacionController::class, 'store'])
    ->name('justificaciones.store');

// --- Rutas del Panel de Administración ---
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('login');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/reportes', function () {
    return view('admin.reportes');
})->name('admin.reportes');

// Gestión de Usuarios
Route::get('/admin/usuarios', [UsuarioController::class, 'index'])
    ->name('admin.usuarios.index');

Route::get('/admin/usuarios/crear', [UsuarioController::class, 'create'])
    ->name('admin.usuarios.create');

Route::get('/admin/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])
    ->name('admin.usuarios.edit');


/*
|--------------------------------------------------------------------------
| RUTAS WEB DE MODIFICACIÓN DE USUARIOS - YA NO SE USAN
|--------------------------------------------------------------------------
|
| Estas rutas fueron comentadas porque el CRUD administrativo de usuarios
| ahora se maneja mediante routes/api.php.
|
| Las rutas API están protegidas con:
| auth:sanctum + middleware admin.
|
| De esta forma se evita tener dos caminos distintos para modificar
| usuarios y se centraliza la seguridad y lógica en la API.
|
*/

/*
Route::post('/admin/usuarios', [UsuarioController::class, 'store'])
    ->name('admin.usuarios.store');

Route::put('/admin/usuarios/{usuario}', [UsuarioController::class, 'update'])
    ->name('admin.usuarios.update');

Route::patch('/admin/usuarios/{usuario}/toggle', [UsuarioController::class, 'toggleActivo'])
    ->name('admin.usuarios.toggle');

Route::delete('/admin/usuarios/{usuario}', [UsuarioController::class, 'destroy'])
    ->name('admin.usuarios.destroy');
*/


// Carreras
Route::get('/carreras', [CarreraController::class, 'index'])
    ->name('carreras.index');

Route::get('/carreras/{carrera}', [CarreraController::class, 'show'])
    ->name('carreras.show');


/*
|--------------------------------------------------------------------------
| RUTAS WEB DE MODIFICACIÓN DE CARRERAS - COMENTADAS
|--------------------------------------------------------------------------
|
| Estas rutas modificaban información directamente desde web.php.
|
| Se comentan para evitar operaciones de creación, actualización o
| eliminación sin la protección administrativa utilizada en la API.
|
| Si en el futuro se necesita CRUD administrativo de carreras,
| se recomienda crear sus endpoints dentro de routes/api.php y
| protegerlos con auth:sanctum + admin.
|
*/

/*
Route::post('/carreras', [CarreraController::class, 'store'])
    ->name('carreras.store');

Route::put('/carreras/{carrera}', [CarreraController::class, 'update'])
    ->name('carreras.update');

Route::delete('/carreras/{carrera}', [CarreraController::class, 'destroy'])
    ->name('carreras.destroy');
*/