<?php

use App\Http\Middleware\CheckModuleAccess;
use App\Livewire\Planificar\Planificar;
use App\Livewire\Rol\Roles;
use App\Livewire\Usuario\Usuarios;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

     Route::get('/dashboard', function () {return view('dashboard');})
    ->name('dashboard');

    // Rutas del módulo de configuración
    Route::middleware(['auth', CheckModuleAccess::class.':configuracion'])->group(function () {
        Route::get('/configuracion/roles', Roles::class)->name('roles');
        Route::get('/configuracion/usuarios', Usuarios::class)->name('usuarios');
        
    });
    // Rutas del módulo de planificacion
    Route::middleware(['auth', CheckModuleAccess::class.':planificacion'])->group(function () {
        Route::get('/planificacion/planificar', Planificar::class)->name('planificar');
        
    });

});
