<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    LoginController,
    InicioController,
    ActivoController,
    PasivoController,
    EscaneoController,
    FiltracionController,
    IncidenteController
};
use App\Http\Controllers\Admin\UserController;



Route::middleware(['auth'])->group(function () {

    Route::get('/inicio', function () {
        return view('index');
    })->name('temas');
    Route::get('/users', [UserController::class, 'index'])->middleware('can:admin.users')->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create'])->middleware('can:admin.create')->name('admin.create');
    Route::post('/users', [UserController::class, 'store'])->middleware('can:admin.store')->name('admin.store');
    Route::get('/users/{tecnico}', [UserController::class, 'show'])->middleware('can:admin.show')->name('admin.show');
    Route::get('/users/{tecnico}/edit', [UserController::class, 'edit'])->middleware('can:admin.edit')->name('admin.edit');
    Route::put('/users/{tecnico}', [UserController::class, 'update'])->middleware('can:admin.update')->name('admin.update');
    Route::delete('/users/{tecnico}', [UserController::class, 'destroy'])->middleware('can:admin.destroy')->name('admin.destroy');

    Route::get('/activo', [ActivoController::class, 'index'])->middleware('can:activo.index')->name('activo.index');
    Route::post('/activo', [ActivoController::class, 'scanWebsite'])->middleware('can:activo.scanWebsite')->name('activo.scanWebsite');

    Route::get('/pasivo', [PasivoController::class, 'index'])->middleware('can:pasivo.index')->name('pasivo.index');
    Route::post('/pasivo', [PasivoController::class, 'scanWebsite'])->middleware('can:pasivo.scanWebsite')->name('pasivo.scanWebsite');


    Route::get('/escaneo', [EscaneoController::class, 'index'])->middleware('can:admin.create')->name('escaneo.index');
    Route::get('/escaneo/enviar/{id}', [EscaneoController::class, 'enviar'])->middleware('can:admin.create')->name('escaneo.enviar');
    Route::get('/escaneo/enviar/{id}/ver', [EscaneoController::class, 'show'])->middleware('can:admin.create')->name('escaneo.show');
    #Route::get('/escaneo/enviar', [EscaneoController::class, 'enviar'])->name('escaneo.enviar');
    //Route::post('/escaneo/enviar', [EscaneoController::class, 'enviar'])->name('escaneo.enviar');

    Route::get('/filtracion', [FiltracionController::class, 'index'])->middleware('can:admin.create')->name('filtracion.index');
    Route::post('/filtracion', [FiltracionController::class, 'store'])->middleware('can:admin.create')->name('filtracion.store');

    Route::resource('incidente', IncidenteController::class);

    // Ruta para mostrar el perfil
    Route::get('/perfil/{id}', [UserController::class, 'mostrarPerfil'])->name('perfil');

    // Ruta para actualizar el perfil
    Route::put('/perfil/{id}', [UserController::class, 'actualizarPerfil'])->name('perfil.actualizar');
});


// Rutas de autenticación
Route::get('/', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'loginVerify'])->name('login.verify');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
