<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    LoginController,  InicioController
};
Route::middleware(['auth'])->group(function () {

    Route::get('/inicio', function () {
        return view('index');
    })->name('temas');


});


// Rutas de autenticación
Route::get('/', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'loginVerify'])->name('login.verify');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
