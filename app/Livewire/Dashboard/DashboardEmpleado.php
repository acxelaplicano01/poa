<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento\Departamento;
use App\Models\TechoUes\TechoDepto;
use App\Models\Presupuestos\Presupuesto;
use App\Models\Actividad\Actividad;
use App\Models\Tareas\Tarea;
use App\Models\EjecucionPresupuestaria\DetalleEjecucionPresupuestaria;
use App\Models\Poa\Poa;
use App\Models\Mes\Trimestre;
use App\Models\Mes\Mes;

#[Layout('layouts.app')]
class DashboardEmpleado extends Component
{
    public $departamentos = [];
    public $departamentoSeleccionado = null;
    public $estadisticasGenerales = [];
    public $estadisticasPorFuente = [];
    public $estadisticasPorGrupoGasto = [];
    public $actividadesRecientes = [];
    public $estadisticasMensuales = [];
    public $poaActivo = null;
    
    // Filtros adicionales
    public $trimestres = [];
    public $trimestreSeleccionado = null;
    public $anios = [];
    public $anioSeleccionado = null;
    public $mesesDelTrimestre = [];

    public function mount()
    {
        $this->cargarDepartamentosUsuario();
        
        if (!empty($this->departamentos)) {
            $this->departamentoSeleccionado = $this->departamentos[0]['id'] ?? null;
        }
        
        $this->cargarPoaActivo();
        $this->cargarTrimestres();
        $this->cargarAnios();
        
        // Establecer año actual si está disponible
        if ($this->poaActivo) {
            $this->anioSeleccionado = $this->poaActivo->anio;
        }
        
        $this->cargarEstadisticas();
    }

    public function cargarDepartamentosUsuario()
    {
        $user = Auth::user();
        
        if (!$user || !$user->empleado) {
            $this->departamentos = [];
            return;
        }

        $empleado = $user->empleado;
        $departamentosEmpleado = $empleado->departamentos()->get();

        $this->departamentos = $departamentosEmpleado->map(function ($depto) {
            return [
                'id' => $depto->id,
                'nombre' => $depto->name,
                'siglas' => $depto->siglas,
            ];
        })->toArray();
    }
    
    public function cargarTrimestres()
    {
        $this->trimestres = Trimestre::orderBy('id')->get()->map(function ($trimestre) {
            return [
                'id' => $trimestre->id,
                'nombre' => $trimestre->trimestre,
            ];
        })->toArray();
    }
    
    public function cargarAnios()
    {
        // Obtener años disponibles de los POAs
        $this->anios = Poa::orderBy('anio', 'desc')
            ->pluck('anio')
            ->unique()
            ->values()
            ->toArray();
    }

    public function updatedDepartamentoSeleccionado()
    {
        $this->cargarEstadisticas();
        $this->dispatch('charts-update');
    }
    
    public function updatedTrimestreSeleccionado()
    {
        // Cargar meses del trimestre seleccionado
        if ($this->trimestreSeleccionado) {
            $this->mesesDelTrimestre = Mes::where('idTrimestre', $this->trimestreSeleccionado)
                ->pluck('id')
                ->toArray();
        } else {
            $this->mesesDelTrimestre = [];
        }
        $this->cargarEstadisticas();
        $this->dispatch('charts-update');
    }
    
    public function updatedAnioSeleccionado()
    {
        $this->cargarEstadisticas();
        $this->dispatch('charts-update');
    }
    public function cargarPoaActivo()
    {
        $this->poaActivo = Poa::where('activo', true)
            ->orderBy('anio', 'desc')
            ->first();
    }

    public function cargarEstadisticas()
    {
        if (!$this->departamentoSeleccionado) {
            $this->estadisticasGenerales = [];
            $this->estadisticasPorFuente = [];
            $this->estadisticasPorGrupoGasto = [];
            $this->actividadesRecientes = [];
            $this->estadisticasMensuales = [];
            return;
        }

        $this->cargarEstadisticasGenerales();
        $this->cargarEstadisticasPorFuente();
        $this->cargarEstadisticasPorGrupoGasto();
        $this->cargarActividadesRecientes();
        $this->cargarEstadisticasMensuales();
    }
    
    private function obtenerPoaPorAnio()
    {
        if ($this->anioSeleccionado) {
            return Poa::where('anio', $this->anioSeleccionado)->first();
        }
        return $this->poaActivo;
    }

    private function cargarEstadisticasGenerales()
    {
        $idDepartamento = $this->departamentoSeleccionado;
        
        // Determinar el POA a usar (por año seleccionado o activo)
        $poa = $this->obtenerPoaPorAnio();
        if (!$poa) return;
        
        $idPoa = $poa->id;

        // Presupuesto Asignado (Techo del departamento)
        $presupuestoAsignado = TechoDepto::where('idDepartamento', $idDepartamento)
            ->where('idPoa', $idPoa)
            ->sum('monto');

        // Presupuesto Planificado (suma de presupuestos en tareas del departamento)
        $queryPlanificado = Presupuesto::whereHas('tarea', function ($query) use ($idDepartamento, $idPoa) {
            $query->where('idDeptartamento', $idDepartamento)
                  ->where('idPoa', $idPoa);
        });
        
        // Aplicar filtro de trimestre si está seleccionado
        if ($this->trimestreSeleccionado && !empty($this->mesesDelTrimestre)) {
            $queryPlanificado->whereIn('idMes', $this->mesesDelTrimestre);
        }
        
        $presupuestoPlanificado = $queryPlanificado->sum('total');

        // Presupuesto Ejecutado (suma de montos ejecutados en detalles de ejecución)
        $queryEjecutado = DetalleEjecucionPresupuestaria::whereHas('presupuesto.tarea', function ($query) use ($idDepartamento, $idPoa) {
            $query->where('idDeptartamento', $idDepartamento)
                  ->where('idPoa', $idPoa);
        });
        
        // Aplicar filtro de trimestre en ejecución
        if ($this->trimestreSeleccionado && !empty($this->mesesDelTrimestre)) {
            $queryEjecutado->whereHas('presupuesto', function ($query) {
                $query->whereIn('idMes', $this->mesesDelTrimestre);
            });
        }
        
        $presupuestoEjecutado = $queryEjecutado->sum('monto_total_ejecutado');

        // Presupuesto Disponible
        $presupuestoDisponible = $presupuestoAsignado - $presupuestoPlanificado;

        // Porcentajes
        $porcentajePlanificado = $presupuestoAsignado > 0 
            ? round(($presupuestoPlanificado / $presupuestoAsignado) * 100, 2) 
            : 0;

        $porcentajeEjecutado = $presupuestoPlanificado > 0 
            ? round(($presupuestoEjecutado / $presupuestoPlanificado) * 100, 2) 
            : 0;

        // Estadísticas de Actividades
        $totalActividades = Actividad::where('idDeptartamento', $idDepartamento)
            ->where('idPoa', $idPoa)
            ->count();

        $actividadesAprobadas = Actividad::where('idDeptartamento', $idDepartamento)
            ->where('idPoa', $idPoa)
            ->where('estado', 'APROBADO')
            ->count();

        $actividadesEnRevision = Actividad::where('idDeptartamento', $idDepartamento)
            ->where('idPoa', $idPoa)
            ->where('estado', 'REVISION')
            ->count();

        $this->estadisticasGenerales = [
            'presupuestoAsignado' => $presupuestoAsignado,
            'presupuestoPlanificado' => $presupuestoPlanificado,
            'presupuestoEjecutado' => $presupuestoEjecutado,
            'presupuestoDisponible' => $presupuestoDisponible,
            'porcentajePlanificado' => $porcentajePlanificado,
            'porcentajeEjecutado' => $porcentajeEjecutado,
            'totalActividades' => $totalActividades,
            'actividadesAprobadas' => $actividadesAprobadas,
            'actividadesEnRevision' => $actividadesEnRevision,
        ];
    }

    private function cargarEstadisticasPorFuente()
    {
        $idDepartamento = $this->departamentoSeleccionado;
        $poa = $this->obtenerPoaPorAnio();
        if (!$poa) return;
        $idPoa = $poa->id;

        // Obtener techos del departamento agrupados por fuente
        $techosPorFuente = TechoDepto::where('idDepartamento', $idDepartamento)
            ->where('idPoa', $idPoa)
            ->with('techoUE.fuente')
            ->get()
            ->groupBy(function ($techoDepto) {
                return $techoDepto->techoUE->fuente->nombre ?? 'Sin Fuente';
            });

        $estadisticas = [];

        foreach ($techosPorFuente as $fuenteNombre => $techos) {
            $techoTotal = $techos->sum('monto');
            
            // Obtener ID de la fuente del primer techo
            $idFuente = $techos->first()->techoUE->idFuente ?? null;

            // Presupuesto planificado para esta fuente
            $queryPlanificado = Presupuesto::whereHas('tarea', function ($query) use ($idDepartamento, $idPoa) {
                $query->where('idDeptartamento', $idDepartamento)
                      ->where('idPoa', $idPoa);
            });
            
            if ($idFuente) {
                $queryPlanificado->where('idfuente', $idFuente);
            }
            
            // Aplicar filtro de trimestre
            if ($this->trimestreSeleccionado && !empty($this->mesesDelTrimestre)) {
                $queryPlanificado->whereIn('idMes', $this->mesesDelTrimestre);
            }
            
            $presupuestoPlanificado = $queryPlanificado->sum('total');

            // Presupuesto ejecutado para esta fuente
            $queryEjecutado = DetalleEjecucionPresupuestaria::whereHas('presupuesto', function ($query) use ($idDepartamento, $idPoa, $idFuente) {
                if ($idFuente) {
                    $query->where('idfuente', $idFuente);
                }
                $query->whereHas('tarea', function ($q) use ($idDepartamento, $idPoa) {
                    $q->where('idDeptartamento', $idDepartamento)
                      ->where('idPoa', $idPoa);
                });
            });
            
            // Aplicar filtro de trimestre
            if ($this->trimestreSeleccionado && !empty($this->mesesDelTrimestre)) {
                $queryEjecutado->whereHas('presupuesto', function ($query) {
                    $query->whereIn('idMes', $this->mesesDelTrimestre);
                });
            }
            
            $presupuestoEjecutado = $queryEjecutado->sum('monto_total_ejecutado');

            $presupuestoDisponible = $techoTotal - $presupuestoPlanificado;

            $estadisticas[] = [
                'fuente' => $fuenteNombre,
                'asignado' => $techoTotal,
                'planificado' => $presupuestoPlanificado,
                'ejecutado' => $presupuestoEjecutado,
                'disponible' => $presupuestoDisponible,
            ];
        }

        $this->estadisticasPorFuente = $estadisticas;
    }

    private function cargarEstadisticasPorGrupoGasto()
    {
        $idDepartamento = $this->departamentoSeleccionado;
        $poa = $this->obtenerPoaPorAnio();
        if (!$poa) return;
        $idPoa = $poa->id;

        // Obtener presupuestos agrupados por grupo de gasto
        $queryPresupuestos = Presupuesto::whereHas('tarea', function ($query) use ($idDepartamento, $idPoa) {
            $query->where('idDeptartamento', $idDepartamento)
                  ->where('idPoa', $idPoa);
        });
        
        // Aplicar filtro de trimestre
        if ($this->trimestreSeleccionado && !empty($this->mesesDelTrimestre)) {
            $queryPresupuestos->whereIn('idMes', $this->mesesDelTrimestre);
        }
        
        $presupuestosPorGrupo = $queryPresupuestos->with('grupoGasto')
            ->get()
            ->groupBy(function ($presupuesto) {
                return $presupuesto->grupoGasto->nombre ?? 'Sin Grupo';
            });

        $estadisticas = [];

        foreach ($presupuestosPorGrupo as $grupoNombre => $presupuestos) {
            $totalPlanificado = $presupuestos->sum('total');
            
            // Calcular ejecutado para este grupo
            $idsPresupuestos = $presupuestos->pluck('id');
            $totalEjecutado = DetalleEjecucionPresupuestaria::whereIn('idPresupuesto', $idsPresupuestos)
                ->sum('monto_total_ejecutado');

            $estadisticas[] = [
                'grupo' => $grupoNombre,
                'planificado' => $totalPlanificado,
                'ejecutado' => $totalEjecutado,
            ];
        }

        // Ordenar por planificado descendente
        usort($estadisticas, function ($a, $b) {
            return $b['planificado'] <=> $a['planificado'];
        });

        // Tomar solo los primeros 10
        $this->estadisticasPorGrupoGasto = array_slice($estadisticas, 0, 10);
    }

    private function cargarActividadesRecientes()
    {
        $idDepartamento = $this->departamentoSeleccionado;
        $poa = $this->obtenerPoaPorAnio();
        if (!$poa) return;
        $idPoa = $poa->id;

        $this->actividadesRecientes = Actividad::where('idDeptartamento', $idDepartamento)
            ->where('idPoa', $idPoa)
            ->with(['tipo', 'categoria'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($actividad) {
                // Calcular presupuesto planificado de la actividad
                $queryPresupuesto = Presupuesto::whereHas('tarea', function ($query) use ($actividad) {
                    $query->where('idActividad', $actividad->id);
                });
                
                // Aplicar filtro de trimestre
                if ($this->trimestreSeleccionado && !empty($this->mesesDelTrimestre)) {
                    $queryPresupuesto->whereIn('idMes', $this->mesesDelTrimestre);
                }
                
                $presupuestoPlanificado = $queryPresupuesto->sum('total');

                return [
                    'id' => $actividad->id,
                    'nombre' => $actividad->nombre,
                    'tipo' => $actividad->tipo->tipo ?? 'N/A',
                    'categoria' => $actividad->categoria->categoria ?? 'N/A',
                    'estado' => $actividad->estado,
                    'presupuesto' => $presupuestoPlanificado,
                    'updated_at' => $actividad->updated_at->format('d/m/Y H:i'),
                ];
            })
            ->toArray();
    }

    private function cargarEstadisticasMensuales()
    {
        $idDepartamento = $this->departamentoSeleccionado;
        $poa = $this->obtenerPoaPorAnio();
        if (!$poa) return;
        $idPoa = $poa->id;

        // Obtener presupuestos planificados por mes
        $queryPresupuestos = Presupuesto::whereHas('tarea', function ($query) use ($idDepartamento, $idPoa) {
            $query->where('idDeptartamento', $idDepartamento)
                  ->where('idPoa', $idPoa);
        });
        
        // Aplicar filtro de trimestre
        if ($this->trimestreSeleccionado && !empty($this->mesesDelTrimestre)) {
            $queryPresupuestos->whereIn('idMes', $this->mesesDelTrimestre);
        }
        
        $presupuestosPorMes = $queryPresupuestos->with('mes')
            ->get()
            ->groupBy(function ($presupuesto) {
                return $presupuesto->mes->mes ?? 'Sin Mes';
            });

        $estadisticas = [];

        foreach ($presupuestosPorMes as $mesNombre => $presupuestos) {
            $totalPlanificado = $presupuestos->sum('total');
            
            // Calcular ejecutado para este mes
            $idsPresupuestos = $presupuestos->pluck('id');
            $totalEjecutado = DetalleEjecucionPresupuestaria::whereIn('idPresupuesto', $idsPresupuestos)
                ->sum('monto_total_ejecutado');

            $estadisticas[] = [
                'mes' => $mesNombre,
                'planificado' => $totalPlanificado,
                'ejecutado' => $totalEjecutado,
            ];
        }

        // Ordenar por mes (asumiendo nombres como "Enero", "Febrero", etc.)
        $ordenMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        usort($estadisticas, function ($a, $b) use ($ordenMeses) {
            $indexA = array_search($a['mes'], $ordenMeses);
            $indexB = array_search($b['mes'], $ordenMeses);
            return ($indexA !== false ? $indexA : 999) <=> ($indexB !== false ? $indexB : 999);
        });

        $this->estadisticasMensuales = $estadisticas;
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-empleado');
    }
}
