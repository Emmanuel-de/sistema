<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\DigitalizacionController;
use App\Http\Controllers\ComplementarController;
// Ruta principal del dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Rutas para obtener datos via AJAX (opcional)
Route::prefix('api')->group(function () {
    Route::get('/carpetas-data', [DashboardController::class, 'getCarpetasData'])->name('api.carpetas');
    Route::get('/audiencias-data', [DashboardController::class, 'getAudienciasData'])->name('api.audiencias');
    Route::get('/etapas-data', [DashboardController::class, 'getEtapasData'])->name('api.etapas');
});

// Si necesitas autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.authenticated');
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
