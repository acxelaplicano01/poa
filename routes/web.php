<?php

use App\Http\Controllers\LogViewerController;
use App\Http\Controllers\ModuleRedirectController;
use App\Http\Middleware\CheckModuleAccess;
use App\Livewire\Actas\TipoActaEntregas;
use App\Livewire\Admin\SessionManager;
use App\Livewire\Categoria\Categorias;
use App\Livewire\Consolidado\Consolidado;
use App\Livewire\Cub\Cubs;
use App\Livewire\Departamento\Departamentos;
use App\Livewire\EjecucionPresupuestaria\EstadosEjecucionPresupuestaria;
use App\Livewire\Empleado\Empleados;
use App\Livewire\GrupoGastos\Fuentes;
use App\Livewire\GrupoGastos\GrupoGastos;
use App\Livewire\Institucion\Instituciones;
use App\Livewire\Planificar\Planificar;
use App\Livewire\ProcesCompra\ProcesCompras;
use App\Livewire\Requerir\Requerir;
use App\Livewire\Requisicion\EstadosRequisicion;
use App\Livewire\Requisicion\UnidadMedidas;
use App\Livewire\Rol\Roles;
use App\Livewire\Seguimiento\Seguimiento;
use App\Livewire\Actividad\TipoActividades;
use App\Livewire\Mes\Trimestres;
use App\Livewire\Usuario\Usuarios;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/error/404', 'errors.404')->name('error.404');
Route::view('/error/500', 'errors.500')->name('error.500');
Route::view('/error/403', 'errors.403')->name('error.403');


Route::get('/modulo/{module}', [ModuleRedirectController::class, 'redirectToModule'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('module.redirect');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Ruta para el dashboard
    Route::get('/dashboard', function () {
        return view('dashboard'); })
        ->name('dashboard');

    //Falta meter estas rutas a su modulo correspondiente
    Route::get('/trimestres', Trimestres::class)
        ->name('trimestres');

    Route::get('/tipoactividades', TipoActividades::class)
        ->name('tipoactividades');

    Route::get('/tipo-acta-entregas', TipoActaEntregas::class)
        ->name('tipo-acta-entregas');

    Route::get('/unidad-medidas', UnidadMedidas::class)
        ->name('unidad-medidas');

    Route::get('/categorias', Categorias::class)
        ->name('categorias');

    Route::get('/estados-ejecucion', EstadosEjecucionPresupuestaria::class)
        ->name('estados-ejecucion');

    Route::get('/estados-requisicion', EstadosRequisicion::class)
        ->name('estados-requisicion');

    Route::get('/fuentes', Fuentes::class)
        ->name('fuentes');

    Route::get('/grupo-gastos', GrupoGastos::class)
        ->name('grupo-gastos');

    Route::get('/instituciones', Instituciones::class)
        ->name('instituciones');



    // Rutas del módulo de configuración
    Route::middleware(['auth', CheckModuleAccess::class . ':configuracion'])->group(function () {

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
    Route::middleware(['auth', CheckModuleAccess::class . ':planificacion'])->group(function () {

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

    // Rutas para el visor de logs
    Route::middleware(['auth', CheckModuleAccess::class . ':logs'])->group(function () {
        Route::get('/logs', [LogViewerController::class, 'index'])
            ->name('logs')
            ->middleware('can:logs.logs');

        Route::get('/logs/dashboard', [LogViewerController::class, 'dashboard'])
            ->name('logsdashboard')
            ->middleware('can:logs.logsdashboard');

        Route::get('/logs/sessions', SessionManager::class)
            ->name('sessions')
            ->middleware('can:logs.sessions');

        Route::get('/logs/{log}', [LogViewerController::class, 'show'])
            ->name('logs.show')
            ->middleware('can:logs.logsshow');

        Route::post('/logs/cleanup', [LogViewerController::class, 'cleanup'])
            ->name('cleanup')
            ->middleware('can:logs.logscleanup');
    });

});
