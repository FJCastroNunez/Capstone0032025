<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CotizacionController;

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

Route::middleware(['auth'])->group(function () {

    // 🟩 ADMIN (rol = 1)
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('productos', ProductoController::class);

    // 🟦 VENDEDOR (rol = 2)
    Route::resource('cotizaciones', CotizacionController::class);

    // 🏠 Redirección
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
