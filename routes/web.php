<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\DigitalizacionController;
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