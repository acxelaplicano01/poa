<?php

namespace App\Livewire\TechoUes;

use Livewire\Component;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\TechoUes\TechoUe;
use App\Models\TechoUes\TechoDepto;
use App\Models\Poa\Poa;
use App\Models\Presupuestos\Presupuesto;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AnalysisTechoUe extends Component
{
    public $idPoa;
    public $idUE;
    public $unidadEjecutora;
    public $poa;
    public $techos = [];
    public $presupuestoGeneral = 0;
    public $presupuestoAsignado = 0;
    public $presupuestoPlanificado = 0;
    public $presupuestoRequerido = 0;
    public $presupuestoEjecutado = 0;
    public $presupuestosPorFuente = [];

    public function mount($idPoa = null, $idUE = null)
    {
        // Obtener idPoa de parámetros de ruta o request
        $this->idPoa = $idPoa ?? request()->route('idPoa') ?? request()->get('idPoa');
        $this->idUE = $idUE ?? request()->route('idUE') ?? request()->get('idUE');
        
        if (!$this->idPoa || !$this->idUE) {
            abort(404, 'Parámetros requeridos faltantes: idPoa, idUE');
        }
        
        // Cargar POA
        $this->poa = Poa::findOrFail($this->idPoa);
        
        // Cargar unidad ejecutora
        $this->unidadEjecutora = UnidadEjecutora::findOrFail($this->idUE);
        
        // Cargar techos
        $techos = TechoUe::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->with('fuente')
            ->get();
        
        if ($techos->isEmpty()) {
            abort(404, 'No se encontraron techos para esta unidad ejecutora.');
        }
        
        $this->techos = $techos->toArray();
        
        // Calcular presupuesto general
        $this->presupuestoGeneral = $techos->sum('monto');
        
        // Calcular todos los presupuestos
        $this->calculateBudgets();
    }

    private function calculateBudgets()
    {
        // Presupuesto Asignado: Total que la UE ha asignado a sus departamentos
        // (suma de todos los techos_deptos para esta UE)
        $this->presupuestoAsignado = TechoDepto::where('idUE', $this->idUE)
            ->where('idPoa', $this->idPoa)
            ->sum('monto');

        // Presupuesto Planificado: Total de presupuestos asignados en actividades/tareas
        // (suma de todos los presupuestos cuyas tareas pertenecen a departamentos de esta UE)
        $this->presupuestoPlanificado = Presupuesto::whereHas('tarea', function ($query) {
            $query->where('idUE', $this->idUE)
                  ->where('idPoa', $this->idPoa);
        })->sum('total');

        // Calcular presupuestos por fuente
        foreach ($this->techos as &$techo) {
            $techoId = $techo['id'] ?? null;
            
            if ($techoId) {
                // Presupuesto Asignado por Fuente
                // Se calcula sumando todos los techos_deptos que pertenecen a este techoUE
                $asignado = TechoDepto::where('idUE', $this->idUE)
                    ->where('idPoa', $this->idPoa)
                    ->where('idTechoUE', $techoId)
                    ->sum('monto');
                
                // Presupuesto Planificado por Fuente
                // Se calcula sumando presupuestos donde la tarea está asociada a esta fuente
                $fuenteId = $techo['idFuente'] ?? null;
                $planificado = 0;
                if ($fuenteId) {
                    $planificado = Presupuesto::whereHas('tarea', function ($query) {
                        $query->where('idUE', $this->idUE)
                              ->where('idPoa', $this->idPoa);
                    })->where('idFuente', $fuenteId)
                      ->sum('total');
                }
                
                $techo['presupuestoAsignado'] = $asignado;
                $techo['presupuestoPlanificado'] = $planificado;
                $techo['presupuestoRequerido'] = 0;
                $techo['presupuestoEjecutado'] = 0;
            }
        }
        
        // Los otros dos valores (requerido y ejecutado) se pueden agregar cuando haya 
        // datos reales en las tablas de requisiciones y ejecuciones presupuestarias
    }

    public function render()
    {
        return view('livewire.techo-ues.analysis-techo-ue', [
            'unidadEjecutora' => $this->unidadEjecutora,
            'poa' => $this->poa,
            'techos' => $this->techos,
            'presupuestoGeneral' => $this->presupuestoGeneral,
            'presupuestoAsignado' => $this->presupuestoAsignado,
            'presupuestoPlanificado' => $this->presupuestoPlanificado,
            'presupuestoRequerido' => $this->presupuestoRequerido,
            'presupuestoEjecutado' => $this->presupuestoEjecutado,
            'presupuestosPorFuente' => $this->presupuestosPorFuente,
        ]);
    }
}

