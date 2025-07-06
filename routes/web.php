<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\DigitalizacionController;
use App\Http\Controllers\ComplementarController;
use App\Http\Controllers\PendienteController;
use App\Http\Controllers\CarpetanucController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\NotificacionController;

// Login routes (public)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas para obtener datos via AJAX (opcional)
    Route::prefix('api')->group(function () {
        Route::get('/carpetas-data', [DashboardController::class, 'getCarpetasData'])->name('api.carpetas');
        Route::get('/audiencias-data', [DashboardController::class, 'getAudienciasData'])->name('api.audiencias');
        Route::get('/etapas-data', [DashboardController::class, 'getEtapasData'])->name('api.etapas');
    });
});

// RUTAS ESPECÍFICAS DE RECEPCIÓN (DEBEN IR ANTES DEL RESOURCE)
// Ruta para búsquedas - DEBE IR ANTES del resource
Route::get('/recepciones/search', [RecepcionController::class, 'search'])->name('recepcion.search');

// CRUD completo de recepciones usando resource
Route::resource('recepciones', RecepcionController::class, [
    'names' => [
        'index' => 'recepcion.index',
        'create' => 'recepcion.create',
        'store' => 'recepcion.store',
        'show' => 'recepcion.show',
        'edit' => 'recepcion.edit',
        'update' => 'recepcion.update',
        'destroy' => 'recepcion.destroy'
    ]
]);

Route::get('/recepcion/get-by-nuc', [RecepcionController::class, 'getRecepcionByNUC'])->name('recepcion.get_by_nuc');
// RUTAS DE DIGITALIZACIÓN
// ==============================================



// Rutas específicas ANTES del resource (para evitar conflictos)
Route::get('/digitalizaciones/search', [DigitalizacionController::class, 'buscar'])->name('digitalizacion.search');
Route::get('/digitalizaciones/estadisticas', [DigitalizacionController::class, 'estadisticas'])->name('digitalizacion.estadisticas');
Route::get('/digitalizaciones/{digitalizacion}/download/{fileIndex}', [DigitalizacionController::class, 'downloadFile'])->name('digitalizacion.download-file');
Route::get('/digitalizaciones/{digitalizacion}/view/{fileIndex}', [DigitalizacionController::class, 'viewFile'])->name('digitalizacion.view-file');
Route::get('/digitalizaciones/{digitalizacion}/download-all', [DigitalizacionController::class, 'downloadAll'])->name('digitalizacion.download-all');
Route::post('/digitalizaciones/{digitalizacion}/estado', [DigitalizacionController::class, 'cambiarEstado'])->name('digitalizacion.cambiar-estado');
Route::post('/digitalizaciones/{digitalizacion}/duplicar', [DigitalizacionController::class, 'duplicar'])->name('digitalizacion.duplicar');



// CRUD completo de digitalizaciones
Route::resource('digitalizaciones', DigitalizacionController::class, [
'names' => [
'index' => 'digitalizacion.index',
'create' => 'digitalizacion.create',
'store' => 'digitalizacion.store',
'show' => 'digitalizacion.show',
'edit' => 'digitalizacion.edit',
'update' => 'digitalizacion.update',
'destroy' => 'digitalizacion.destroy'
]
]);



// ==============================================
// RUTAS ALTERNATIVAS PARA DIGITALIZACIÓN
// ==============================================



// Rutas alternativas más amigables para digitalización
Route::get('/digitalizar', [DigitalizacionController::class, 'index'])->name('digitalizar.index');
Route::get('/digitalizar/crear', [DigitalizacionController::class, 'create'])->name('digitalizar.create');
Route::post('/digitalizar', [DigitalizacionController::class, 'store'])->name('digitalizar.store');
Route::get('/digitalizar/{digitalizacion}', [DigitalizacionController::class, 'show'])->name('digitalizar.show');
Route::get('/digitalizar/{digitalizacion}/editar', [DigitalizacionController::class, 'edit'])->name('digitalizar.edit');
Route::put('/digitalizar/{digitalizacion}', [DigitalizacionController::class, 'update'])->name('digitalizar.update');
Route::delete('/digitalizar/{digitalizacion}', [DigitalizacionController::class, 'destroy'])->name('digitalizar.destroy');
Route::get('/digitalizar/{digitalizacion}/archivo/{indiceArchivo}', [DigitalizacionController::class, 'descargarArchivo'])->name('digitalizar.descargarArchivo');
Route::get('/digitalizaciones/{id}/descargar', [DigitalizacionController::class, 'descargarArchivo'])->name('digitalizaciones.descargar');
Route::post('/digitalizar/{digitalizacion}/estado', [DigitalizacionController::class, 'cambiarEstado'])->name('digitalizar.cambiarEstado');
Route::get('/digitalizaciones/{digitalizacion}/download-file/{fileIndex}', [DigitalizacionController::class, 'downloadFile'])
    ->name('digitalizacion.downloadFile'); 
Route::post('/digitalizar/generar-pdf', [DigitalizacionController::class, 'generatePdf'])->name('digitalizar.generatePdf');

// ==============================================
// RUTAS para complementar
// ==============================================


Route::get('/complementar/getRecepcionData', [App\Http\Controllers\ComplementarController::class, 'getRecepcionData'])->name('complementar.getRecepcionData');
// Ruta GET para mostrar el formulario de creación (ej. /complementar)
Route::get('/complementar', [ComplementarController::class, 'create'])->name('complementar.create');

// Ruta POST para guardar el formulario (ej. /complementar)
Route::post('/complementar', [ComplementarController::class, 'store'])->name('complementar.store');

// Grupo de rutas para otras acciones de Complementar, usando un prefijo y nombres base
Route::prefix('complementar')->name('complementar.')->group(function () {
    // Listar todos los documentos complementarios (ej. /complementar/lista)
    Route::get('/lista', [ComplementarController::class, 'index'])->name('index'); // Usar this.route('complementar.index')

    // Mostrar documento específico (ej. /complementar/ver/{id})
    Route::get('/ver/{id}', [ComplementarController::class, 'show'])->name('show');

    // Formulario de edición (ej. /complementar/editar/{id})
    Route::get('/editar/{id}', [ComplementarController::class, 'edit'])->name('edit');

    // Actualizar documento (ej. /complementar/actualizar/{id})
    Route::put('/actualizar/{id}', [ComplementarController::class, 'update'])->name('update');

    // Eliminar documento (soft delete) (ej. /complementar/eliminar/{id})
    Route::delete('/eliminar/{id}', [ComplementarController::class, 'destroy'])->name('destroy');

    // Búsqueda de documentos (ej. /complementar/buscar)
    Route::get('/buscar', [ComplementarController::class, 'search'])->name('search');

    // Exportar documentos (ej. /complementar/exportar)
    Route::get('/exportar', [ComplementarController::class, 'export'])->name('export');
});

// ==============================================
// RUTAS para Pendiente
// ==============================================

// Rutas específicas ANTES del resource (para evitar conflictos)
Route::get('/pendientes/search', [PendienteController::class, 'buscar'])->name('pendientes.search');
Route::post('/pendientes/search', [PendienteController::class, 'buscar'])->name('pendientes.search.post');
Route::get('/pendientes/estadisticas', [PendienteController::class, 'estadisticas'])->name('pendientes.estadisticas');
Route::post('/pendientes/liberar', [PendienteController::class, 'liberar'])->name('pendientes.liberar');
Route::post('/pendientes/rechazar', [PendienteController::class, 'rechazar'])->name('pendientes.rechazar');
Route::post('/pendientes/turnar', [PendienteController::class, 'turnar'])->name('pendientes.turnar');
Route::post('/pendientes/generar', [PendienteController::class, 'generar'])->name('pendientes.generar');
Route::post('/pendientes/actualizar-estado-masivo', [PendienteController::class, 'actualizarEstadoMasivo'])->name('pendientes.actualizar-estado-masivo');

// CRUD completo de pendientes usando resource
Route::resource('pendientes', PendienteController::class, [
    'names' => [
        'index' => 'pendientes.index',
        'create' => 'pendientes.create',
        'store' => 'pendientes.store',
        'show' => 'pendientes.show',
        'edit' => 'pendientes.edit',
        'update' => 'pendientes.update',
        'destroy' => 'pendientes.destroy'
    ]
]);

// ==============================================
// RUTAS ALTERNATIVAS PARA PENDIENTES
// ==============================================

// Rutas alternativas más amigables para pendientes
Route::get('/docs-pendientes', [PendienteController::class, 'index'])->name('docs-pendientes.index');
Route::get('/mis-pendientes', function () {
    return redirect()->route('pendientes.index', ['asignado_a' => auth()->id()]);
})->name('mis-pendientes');
Route::get('/urgentes', function () {
    return redirect()->route('pendientes.index', ['prioridad' => 'urgente']);
})->name('urgentes');
Route::get('/liberados-hoy', function () {
    return redirect()->route('pendientes.index', ['fecha_liberacion' => today()]);
})->name('liberados-hoy');

// Descargas de reportes
Route::get('/pendientes/reporte/{tipo}', function($tipo) {
    // Implementar lógica de descarga de reportes
    // $tipo puede ser: pdf, excel, csv
    return response()->json(['message' => 'Funcionalidad de descarga en desarrollo']);
})->where('tipo', 'pdf|excel|csv')->name('pendientes.descargar-reporte');



// ==============================================
// RUTAS Carpeta de busqueda 
// ==============================================

// Ruta para mostrar la vista index de carpetanuc
Route::get('/carpetanuc', [CarpetanucController::class, 'index'])->name('carpetanuc.index');

// Rutas adicionales para el CRUD completo de carpetanuc
Route::resource('carpetanuc', CarpetanucController::class);

// O si prefieres definir rutas específicas individualmente:
/*
Route::get('/carpetanuc', [CarpetanucController::class, 'index'])->name('carpetanuc.index');
Route::get('/carpetanuc/create', [CarpetanucController::class, 'create'])->name('carpetanuc.create');
Route::post('/carpetanuc', [CarpetanucController::class, 'store'])->name('carpetanuc.store');
Route::get('/carpetanuc/{id}', [CarpetanucController::class, 'show'])->name('carpetanuc.show');
Route::get('/carpetanuc/{id}/edit', [CarpetanucController::class, 'edit'])->name('carpetanuc.edit');
Route::put('/carpetanuc/{id}', [CarpetanucController::class, 'update'])->name('carpetanuc.update');
Route::delete('/carpetanuc/{id}', [CarpetanucController::class, 'destroy'])->name('carpetanuc.destroy');
*/

// Ruta específica para la búsqueda de carpetas (si necesitas manejar el formulario de búsqueda)
Route::post('/carpetanuc/buscar', [CarpetanucController::class, 'buscar'])->name('carpetanuc.buscar');

// Rutas para documentos pendientes
Route::get('/pendientes', [App\Http\Controllers\PendienteController::class, 'index'])->name('pendientes.index');
Route::get('/pendientes/create', [App\Http\Controllers\PendienteController::class, 'create'])->name('pendientes.create');
Route::post('/pendientes', [App\Http\Controllers\PendienteController::class, 'store'])->name('pendientes.store');
Route::get('/pendientes/{id}', [App\Http\Controllers\PendienteController::class, 'show'])->name('pendientes.show');
Route::post('/pendientes/liberar', [App\Http\Controllers\PendienteController::class, 'liberar'])->name('pendientes.liberar');
Route::post('/pendientes/rechazar', [App\Http\Controllers\PendienteController::class, 'rechazar'])->name('pendientes.rechazar');
Route::post('/pendientes/turnar', [App\Http\Controllers\PendienteController::class, 'turnar'])->name('pendientes.turnar');

// Rutas adicionales para funcionalidades específicas
Route::get('/carpetanuc/audiencia/{id}', [CarpetanucController::class, 'audiencia'])->name('carpetanuc.audiencia');
Route::post('/carpetanuc/generar-trabajo', [CarpetanucController::class, 'generarTrabajo'])->name('carpetanuc.generar-trabajo');
Route::get('/carpetanuc/datos-imputado/{id}', [CarpetanucController::class, 'datosImputado'])->name('carpetanuc.datos-imputado');

//------------------
// Rutas de videos
//-----------------
// Ruta para mostrar el formulario de subida de videos
Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create'); 

// Ruta para procesar la subida de videos (la que necesitas)
Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');

// Rutas adicionales para un CRUD completo (opcional)
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');
Route::get('/videos/{video}/edit', [VideoController::class, 'edit'])->name('videos.edit');
Route::put('/videos/{video}', [VideoController::class, 'update'])->name('videos.update');
Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');

//--------------------------
// RUTA DE NOTIFICASIOONES
//--------------------------

Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
Route::get('/notificaciones/create', [NotificacionController::class, 'create'])->name('notificaciones.create');
Route::post('/notificaciones', [NotificacionController::class, 'store'])->name('notificaciones.store');
Route::get('/notificaciones/{notificacion}', [NotificacionController::class, 'show'])->name('notificaciones.show');
Route::get('/notificaciones/{notificacion}/edit', [NotificacionController::class, 'edit'])->name('notificaciones.edit');
Route::put('/notificaciones/{notificacion}', [NotificacionController::class, 'update'])->name('notificaciones.update');
Route::delete('/notificaciones/{notificacion}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');