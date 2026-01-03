<?php

namespace App\Livewire\Consolidado;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Actividad\Actividad;
use App\Models\Dimension\Dimension;
use App\Models\Departamento\Departamento;
use App\Models\Poa\Poa;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Consolidado extends Component
{
    use WithPagination;

    public $anio;
    public $dimensionId;
    public $departamentoId;
    public $expandedRow = null;
    
    public $anios = [];
    public $dimensiones = [];
    public $departamentos = [];

    public function mount()
    {
        // Obtener años disponibles
        $this->anios = Poa::distinct()->orderBy('anio', 'desc')->pluck('anio')->toArray();
        $this->anio = count($this->anios) > 0 ? $this->anios[0] : date('Y');
        
        // Obtener dimensiones
        $this->dimensiones = Dimension::orderBy('nombre')->get();
        
        // Obtener departamentos como array para Alpine.js
        $this->departamentos = Departamento::orderBy('name')
            ->get(['id', 'name'])
            ->toArray();
    }

    public function updatingDepartamentoId()
    {
        $this->resetPage();
    }

    public function updatingAnio()
    {
        $this->resetPage();
    }

    public function updatingDimensionId()
    {
        $this->resetPage();
    }

    public function toggleExpand($actividadId)
    {
        if ($this->expandedRow === $actividadId) {
            $this->expandedRow = null;
        } else {
            $this->expandedRow = $actividadId;
        }
    }

    public function toggleSPI($actividadId)
    {
        $actividad = Actividad::find($actividadId);
        if ($actividad) {
            $actividad->uploadedIntoSPI = !$actividad->uploadedIntoSPI;
            $actividad->save();
            
            $this->dispatch('spi-updated', ['message' => 'Estado SPI actualizado correctamente']);
        }
    }

    public function getActividadesProperty()
    {
        $query = Actividad::with([
            'departamento',
            'poa',
            'unidadEjecutora',
            'categoria',
            'resultado.area.objetivo.dimension',
            'indicadores.planificacions',
            'tareas.presupuestos'
        ]);

        // Filtrar por año
        if ($this->anio) {
            $query->whereHas('poa', function ($q) {
                $q->where('anio', $this->anio);
            });
        }

        // Filtrar por dimensión
        if ($this->dimensionId) {
            $query->whereHas('resultado.area.objetivo.dimension', function ($q) {
                $q->where('id', $this->dimensionId);
            });
        }

        // Filtrar por departamento
        if ($this->departamentoId) {
            $query->where('idDeptartamento', $this->departamentoId);
        }

        return $query->orderBy('correlativo')->paginate(20);
    }

    public function getActividadDetalleProperty()
    {
        if (!$this->expandedRow) {
            return null;
        }

        return Actividad::with([
            'departamento',
            'poa',
            'unidadEjecutora.institucion',
            'categoria',
            'tipo',
            'resultado.area.objetivo.dimension',
            'indicadores.planificacions.mes',
            'tareas.presupuestos.mes',
            'tareas.presupuestos.grupoGasto',
            'tareas.presupuestos.objetoGasto',
            'tareas.presupuestos.fuente',
            'empleados'
        ])->find($this->expandedRow);
    }

    public function render()
    {
        return view('livewire.consolidado.consolidado', [
            'actividades' => $this->actividades,
            'actividadDetalle' => $this->actividadDetalle,
            'departamentos' => $this->departamentos,
            'dimensiones' => $this->dimensiones,
            'anios' => $this->anios
        ]);
    }
}
