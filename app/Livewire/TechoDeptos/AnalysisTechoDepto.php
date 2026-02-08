<?php

namespace App\Livewire\TechoDeptos;

use Livewire\Component;
use App\Models\Departamento\Departamento;
use App\Models\TechoUes\TechoDepto;
use App\Models\Poa\Poa;
use App\Models\Presupuestos\Presupuesto;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Requisicion\DetalleRequisicion;
use App\Models\EjecucionPresupuestaria\DetalleEjecucionPresupuestaria;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AnalysisTechoDepto extends Component
{
    public $idPoa;
    public $idUE;
    public $idDepartamento;
    public $departamento;
    public $unidadEjecutora;
    public $poa;
    public $techos = [];
    public $presupuestoGeneral = 0;
    public $presupuestoPlanificado = 0;
    public $presupuestoRequerido = 0;
    public $presupuestoEjecutado = 0;

    public function mount($idPoa = null, $idUE = null, $idDepartamento = null)
    {
        // Obtener parámetros de ruta o request
        $this->idPoa = $idPoa ?? request()->route('idPoa') ?? request()->get('idPoa');
        $this->idUE = $idUE ?? request()->route('idUE') ?? request()->get('idUE');
        $this->idDepartamento = $idDepartamento ?? request()->route('idDepartamento') ?? request()->get('idDepartamento');
        
        if (!$this->idPoa || !$this->idUE || !$this->idDepartamento) {
            abort(404, 'Parámetros requeridos faltantes: idPoa, idUE, idDepartamento');
        }
        
        // Cargar POA
        $this->poa = Poa::findOrFail($this->idPoa);
        
        // Cargar unidad ejecutora
        $this->unidadEjecutora = UnidadEjecutora::findOrFail($this->idUE);
        
        // Cargar departamento
        $this->departamento = Departamento::findOrFail($this->idDepartamento);
        
        // Cargar techos
        $techos = TechoDepto::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->where('idDepartamento', $this->idDepartamento)
            ->with(['techoUE.fuente'])
            ->get();
        
        if ($techos->isEmpty()) {
            abort(404, 'No se encontraron techos para este departamento.');
        }
        
        $this->techos = $techos->toArray();
        
        // Calcular presupuesto general
        $this->presupuestoGeneral = $techos->sum('monto');
        
        // Calcular todos los presupuestos
        $this->calculateBudgets();
    }

    private function calculateBudgets()
    {
        // Presupuesto Planificado: Total de presupuestos asignados en actividades/tareas del departamento
        $this->presupuestoPlanificado = Presupuesto::whereHas('tarea', function ($query) {
            $query->where('idDeptartamento', $this->idDepartamento)
                  ->where('idPoa', $this->idPoa);
        })->sum('total');

        // Presupuesto Requerido: Total de cantidades requeridas en detalle_requisicion
        $this->presupuestoRequerido = DetalleRequisicion::where('idPoa', $this->idPoa)
            ->whereHas('presupuesto.tarea', function ($query) {
                $query->where('idDeptartamento', $this->idDepartamento);
            })
            ->selectRaw('SUM(cantidad * COALESCE((SELECT costounitario FROM presupuestos WHERE presupuestos.id = detalle_requisicion.idPresupuesto), 0)) as total')
            ->value('total') ?? 0;

        // Presupuesto Ejecutado: Total de montos ejecutados en detalle_ejecucion_presupuestaria
        $this->presupuestoEjecutado = DetalleEjecucionPresupuestaria::whereHas('detalleRequisicion', function ($query) {
                $query->where('idPoa', $this->idPoa);
            })
            ->whereHas('presupuesto.tarea', function ($query) {
                $query->where('idDeptartamento', $this->idDepartamento);
            })
            ->sum('monto_total_ejecutado') ?? 0;

        // Calcular presupuestos por fuente
        foreach ($this->techos as &$techo) {
            $techoId = $techo['id'] ?? null;
            
            if ($techoId) {
                // Presupuesto Planificado por Fuente
                $fuenteId = $techo['techo_u_e']['idFuente'] ?? null;
                $planificado = 0;
                $requerido = 0;
                $ejecutado = 0;
                
                if ($fuenteId) {
                    $planificado = Presupuesto::whereHas('tarea', function ($query) {
                        $query->where('idDeptartamento', $this->idDepartamento)
                              ->where('idPoa', $this->idPoa);
                    })->where('idFuente', $fuenteId)
                      ->sum('total');

                    // Presupuesto Requerido por Fuente
                    $requerido = DetalleRequisicion::where('idPoa', $this->idPoa)
                        ->whereHas('presupuesto', function ($query) use ($fuenteId) {
                            $query->where('idFuente', $fuenteId)
                                  ->whereHas('tarea', function ($q) {
                                      $q->where('idDeptartamento', $this->idDepartamento);
                                  });
                        })
                        ->selectRaw('SUM(cantidad * COALESCE((SELECT costounitario FROM presupuestos WHERE presupuestos.id = detalle_requisicion.idPresupuesto), 0)) as total')
                        ->value('total') ?? 0;

                    // Presupuesto Ejecutado por Fuente
                    $ejecutado = DetalleEjecucionPresupuestaria::whereHas('detalleRequisicion', function ($query) {
                            $query->where('idPoa', $this->idPoa);
                        })
                        ->whereHas('presupuesto', function ($query) use ($fuenteId) {
                            $query->where('idFuente', $fuenteId)
                                  ->whereHas('tarea', function ($q) {
                                      $q->where('idDeptartamento', $this->idDepartamento);
                                  });
                        })
                        ->sum('monto_total_ejecutado') ?? 0;
                }
                
                $techo['presupuestoPlanificado'] = $planificado;
                $techo['presupuestoRequerido'] = $requerido;
                $techo['presupuestoEjecutado'] = $ejecutado;
            }
        }
    }

    public function render()
    {
        return view('livewire.techo-deptos.analysis-techo-depto', [
            'departamento' => $this->departamento,
            'unidadEjecutora' => $this->unidadEjecutora,
            'poa' => $this->poa,
            'techos' => $this->techos,
            'presupuestoGeneral' => $this->presupuestoGeneral,
            'presupuestoPlanificado' => $this->presupuestoPlanificado,
            'presupuestoRequerido' => $this->presupuestoRequerido,
            'presupuestoEjecutado' => $this->presupuestoEjecutado,
        ]);
    }
}
