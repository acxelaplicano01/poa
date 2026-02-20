<?php

namespace App\Livewire\Revision;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Actividad\Actividad;
use App\Models\Poa\PoaDepto;
use App\Models\Tareas\Tarea;
use App\Models\Presupuestos\Presupuesto;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ActividadesRevision extends Component
{
    use WithPagination;

    public $departamentoId;
    public $resumen = [];
    public $poaYears = [];
    public $buscarActividad = '';
    public $poaYear = '';
    public $perPage = 10;
    

    public function mount($departamentoId, $poaYear = null)
    {
        $this->departamentoId = $departamentoId;
        $this->cargarResumen();

        $this->poaYears = PoaDepto::where('idDepartamento', $departamentoId)
            ->join('poas', 'poa_deptos.idPoa', '=', 'poas.id')
            ->pluck('poas.anio')
            ->unique()
            ->sortDesc()
            ->toArray();
        
        $this->poaYear = $poaYear ?? ($this->poaYears[0] ?? '');$this->poaYear = $poaYear ?? ($this->poaYears[0] ?? '');
        $this->cargarResumen();
        }

    public function updatingBuscarActividad()
    {
        $this->resetPage();
    }

    public function updatedPoaYear()
    {
        $this->resetPage();
        $this->cargarResumen();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function cargarResumen()
    {
        $poaDepto = PoaDepto::where('idDepartamento', $this->departamentoId)->first();
        $nombreDepartamento = $poaDepto?->departamento?->name ?? '-';
        $presupuesto = $planificado = $numActividades = $porcentaje = 0;

        if ($poaDepto) {
            $presupuesto = $poaDepto->techoDeptos->sum('monto');

            $actividades = Actividad::where('idPoaDepto', $poaDepto->id)
                ->whereIn('estado', ['REVISION', 'APROBADO', 'RECHAZADO'])
                ->get();

            $numActividades = $actividades->count();

            $idTareas = Tarea::whereIn('idActividad', $actividades->pluck('id'))
                ->where('isPresupuesto', true)
                ->pluck('id');

            $planificado = Presupuesto::whereIn('idtarea', $idTareas)->sum('total');
            $porcentaje = $presupuesto > 0 ? round(($planificado * 100) / $presupuesto, 1) : 0;
        }

        $this->resumen = compact('nombreDepartamento', 'presupuesto', 'planificado', 'numActividades', 'porcentaje');
    }

  public function render()
    {
        $actividades = Actividad::with(['tipo', 'categoria'])
            ->where('idDeptartamento', $this->departamentoId)
            ->whereIn('estado', ['REVISION', 'APROBADO', 'RECHAZADO'])
            ->when($this->buscarActividad, fn($q) =>
                $q->where('nombre', 'like', '%' . $this->buscarActividad . '%')
            )
            ->when($this->poaYear, function($q) {  
                $q->whereHas('poa', function($q2) {
                    $q2->where('anio', $this->poaYear);
                });
            })
            ->orderBy('nombre')
            ->paginate($this->perPage);

        return view('livewire.Revision.actividades-revision', [
            'actividades' => $actividades,
            'resumen' => $this->resumen,
            'poaYears' => $this->poaYears,
        ]);
    }
}