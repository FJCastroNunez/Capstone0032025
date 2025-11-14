<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ReporteCotizacionController;

Route::get('/welcome', function () {
    return view('landing');
});

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil/actualizar', [PerfilController::class, 'update'])->name('perfil.update');
});
Route::get('/cotizaciones/visitas', [CotizacionController::class, 'listarVisitas'])->name('cotizaciones.visitas');
Route::get('/cotizacion/visita', [App\Http\Controllers\CotizacionController::class, 'createVisita'])
    ->name('cotizaciones.visita');
Route::post('/cotizacion/visita', [App\Http\Controllers\CotizacionController::class, 'storeVisita'])
    ->name('cotizaciones.storeVisita');
Route::get('/cotizaciones/{id}/editExterno', [CotizacionController::class, 'editExterno'])
    ->name('cotizaciones.edit.externo');


Route::middleware(['auth'])->group(function () {

    // 🟩 ADMIN (rol = 1)
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('materiales', MaterialController::class);

    // 🟦 VENDEDOR (rol = 2)
    Route::resource('cotizaciones', CotizacionController::class);

    // 🏠 Redirección
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('inventario')->group(function () {
        Route::get('/', [InventarioController::class, 'index'])->name('inventario.index');
        Route::get('/{id_material}', [InventarioController::class, 'detalle'])->name('inventario.detalle');
        Route::get('/{id_material}/movimiento', [InventarioController::class, 'movimientoForm'])->name('inventario.movimiento.form');
        Route::post('/{id_material}/movimiento', [InventarioController::class, 'guardarMovimiento'])->name('inventario.movimiento.guardar');
        Route::post('/{id_material}/config', [InventarioController::class, 'actualizarConfig'])->name('inventario.config.guardar');
        Route::prefix('compras')->group(function () {
            Route::get('/', [CompraController::class, 'index'])->name('compras.index');
            Route::get('/crear', [CompraController::class, 'create'])->name('compras.create');
            Route::post('/', [CompraController::class, 'store'])->name('compras.store');
            Route::get('/{id}', [CompraController::class, 'show'])->name('compras.show');
        });

        // PROVEEDORES
        Route::prefix('proveedores')->group(function () {
            Route::get('/', [ProveedorController::class, 'index'])->name('proveedores.index');
            Route::get('/crear', [ProveedorController::class, 'create'])->name('proveedores.create');
            Route::post('/', [ProveedorController::class, 'store'])->name('proveedores.store');
            Route::get('/{id}/editar', [ProveedorController::class, 'edit'])->name('proveedores.edit');
            Route::put('/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
            Route::delete('/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
        });

        Route::get(
            '/reportes/cotizaciones-vendedor',
            [ReporteCotizacionController::class, 'index']
        )->name('reportes.cotizaciones_vendedor');

        Route::get(
            '/reportes/cotizaciones-vendedor/pdf',
            [ReporteCotizacionController::class, 'exportarPDF']
        )->name('reportes.cotizaciones_vendedor_pdf');
    });

    Route::post(
        '/cotizaciones/{id}/facturar',
        [App\Http\Controllers\CotizacionController::class, 'facturar']
    )->name('cotizacion.facturar');
});

/*Cotizaciones Externas*/






Route::get('/cotizaciones/{id}/descargar', [CotizacionController::class, 'descargarPdf'])
    ->name('cotizaciones.descargar');

Route::get('/cotizaciones/{id}/descargarExterno', [CotizacionController::class, 'pdfExterno'])
    ->name('cotizaciones.descargar.externo');

Route::delete('/cotizaciones/adjunto/{id}', [CotizacionController::class, 'eliminarAdjunto'])
    ->name('cotizaciones.eliminarAdjunto');

Route::post('/cotizaciones/{id}/enviar', [App\Http\Controllers\CotizacionController::class, 'enviarPorCorreo'])
    ->name('cotizaciones.enviar');

Route::get('/cotizacion/visita', [App\Http\Controllers\CotizacionController::class, 'createVisita'])
    ->name('cotizaciones.visita');

Route::post('/cotizacion/visita', [App\Http\Controllers\CotizacionController::class, 'storeVisita'])
    ->name('cotizaciones.storeVisita');

Route::get('/cotizacion/{id}/marcar-vista', [CotizacionController::class, 'marcarVista'])
    ->name('cotizacion.marcarVista');
