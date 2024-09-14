<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    LoginController,  InicioController
};
use App\Http\Controllers\Admin\UserController;



Route::middleware(['auth'])->group(function () {

    Route::get('/inicio', function () {
        return view('index');
    })->name('temas');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.store');
    Route::get('/users/{tecnico}', [UserController::class, 'show'])->name('admin.show');
    Route::get('/users/{tecnico}/edit', [UserController::class, 'edit'])->name('admin.edit');
    Route::put('/users/{tecnico}', [UserController::class, 'update'])->name('admin.update');
    Route::delete('/users/{tecnico}', [UserController::class, 'destroy'])->name('admin.destroy');
    
});


// Rutas de autenticación
Route::get('/', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'loginVerify'])->name('login.verify');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
