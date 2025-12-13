<?php

namespace App\Livewire\Revision;

use Livewire\Component;
use App\Models\Actividad\Actividad;


use App\Models\Poa\PoaDepto;
use App\Models\Tareas\Tarea;
use App\Models\Presupuestos\Presupuesto;

class ActividadesRevision extends Component
{
    public $departamentoId;
    public $actividades = [];
    public $resumen = [];

    public function mount($departamentoId)
    {
        $this->departamentoId = $departamentoId;
        $this->cargarActividadesRevision();
        $this->cargarResumen();
    }

    public function cargarActividadesRevision()
    {
        $this->actividades = Actividad::with(['tipo', 'categoria'])
            ->where('idDeptartamento', $this->departamentoId)
            ->where('estado', 'REVISION')
            ->get();
    }

    public function cargarResumen()
    {
        // Buscar el PoaDepto relacionado
        $poaDepto = PoaDepto::where('idDepartamento', $this->departamentoId)->first();
        $nombreDepartamento = $poaDepto && $poaDepto->departamento ? $poaDepto->departamento->name : '-';
        $presupuesto = 0;
        $planificado = 0;
        $numActividades = 0;
        $porcentaje = 0;

        if ($poaDepto) {
            // Obtener el techo total del departamento (sumar todos los techos asociados)
            $presupuesto = $poaDepto->techoDeptos->sum('monto');

            // Obtener actividades en REVISION, APROBADO, RECHAZADO
            $actividades = Actividad::where('idPoaDepto', $poaDepto->id)
                ->whereIn('estado', ['REVISION', 'APROBADO', 'RECHAZADO'])
                ->get();
            $numActividades = $actividades->count();

            // Obtener IDs de actividades
            $idActividades = $actividades->pluck('id');

            // Obtener tareas asociadas a esas actividades
            $tareas = Tarea::whereIn('idActividad', $idActividades)
                ->where('isPresupuesto', true)
                ->get();
            $idTareas = $tareas->pluck('id');

            // Obtener presupuestos planificados para esas tareas
            $presupuestos = Presupuesto::whereIn('idtarea', $idTareas)
                ->get();
            $planificado = $presupuestos->sum('total');

            // Calcular porcentaje
            $porcentaje = $presupuesto > 0 ? round(($planificado * 100) / $presupuesto, 1) : 0;
        }

        $this->resumen = [
            'nombreDepartamento' => $nombreDepartamento,
            'presupuesto' => $presupuesto,
            'planificado' => $planificado,
            'numActividades' => $numActividades,
            'porcentaje' => $porcentaje,
        ];
    }

    public function volverARevisiones()
    {
        $this->dispatch('volverARevisiones');
    }

    public function verDetalleActividad($id)
    {
        $this->dispatch('verDetalleActividad', id: $id);
    }

    public function render()
    {
        return view('livewire.Revision.actividades-revision', [
            'actividades' => $this->actividades,
            'resumen' => $this->resumen,
        ]);
    }
}
