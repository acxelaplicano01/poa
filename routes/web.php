<?php

use App\Http\Controllers\ModuleRedirectController;
use App\Http\Middleware\CheckModuleAccess;
use App\Livewire\Consolidado\Consolidado;
use App\Livewire\Cub\Cubs;
use App\Livewire\Departamento\Departamentos;
use App\Livewire\Empleado\Empleados;
use App\Livewire\Planificar\Planificar;
use App\Livewire\ProcesCompra\ProcesCompras;
use App\Livewire\Requerir\Requerir;
use App\Livewire\Rol\Roles;
use App\Livewire\Seguimiento\Seguimiento;
use App\Livewire\Trimestres;
use App\Livewire\Usuario\Usuarios;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/modulo/{module}', [ModuleRedirectController::class, 'redirectToModule'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('module.redirect');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Ruta para el dashboard
     Route::get('/dashboard', function () {return view('dashboard');})
    ->name('dashboard');

    Route::get('/trimestres', Trimestres::class)->name('trimestres');


    // Rutas del módulo de configuración
    Route::middleware(['auth', CheckModuleAccess::class.':configuracion'])->group(function () {
        
        Route::get('/configuracion/roles', Roles::class)
        ->name('roles')
        ->middleware('can:configuracion.roles');

        Route::get('/configuracion/usuarios', Usuarios::class)
        ->name('usuarios')
        ->middleware('can:configuracion.usuarios');

        Route::get('/configuracion/empleados', Empleados::class)
        ->name('empleados')
        ->middleware('can:configuracion.empleados');

        Route::get('/configuracion/departamentos', Departamentos::class)
        ->name('departamentos')
        ->middleware('can:configuracion.departamentos');

        Route::get('/configuracion/procesoscompras', ProcesCompras::class)
        ->name('procesoscompras')
        ->middleware('can:configuracion.procesoscompras');

        Route::get('/configuracion/cubs', Cubs::class)
        ->name('cubs')
        ->middleware('can:configuracion.cubs');

        
    });
    // Rutas del módulo de planificacion
    Route::middleware(['auth', CheckModuleAccess::class.':planificacion'])->group(function () {
        
        Route::get('/planificacion/planificar', Planificar::class)
        ->name('planificar')
        ->middleware('can:planificacion.planificar');

        Route::get('/planificacion/requerir', Requerir::class)
        ->name('requerir')
        ->middleware('can:planificacion.requerir');

        Route::get('/planificacion/seguimiento', Seguimiento::class)
        ->name('seguimiento')
        ->middleware('can:planificacion.seguimiento');

        Route::get('/planificacion/consolidado', Consolidado::class)
        ->name('consolidado')
        ->middleware('can:planificacion.consolidado');
        
    });

});
