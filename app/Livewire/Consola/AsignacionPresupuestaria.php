<?php

namespace App\Livewire\Consola;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Poa;
use App\Models\Instituciones\Institucion;
use App\Models\UnidadEjecutora\UnidadEjecutora;

class AsignacionPresupuestaria extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $poaToDelete = null;
    public $isEditing = false;
    public $poaId;
    public $filtroAnio = 'todos';
    public $sortField = 'anio';
    public $sortDirection = 'desc';

    // Propiedades del formulario
    public $name = '';
    public $anio = '';
    public $idInstitucion = '';
    public $idUE = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'anio' => 'required|integer|min:2020|max:2050',
        'idInstitucion' => 'required|exists:institucions,id',
        'idUE' => 'required|exists:unidad_ejecutora,id',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.max' => 'El nombre no puede exceder 255 caracteres.',
        'anio.required' => 'El año es obligatorio.',
        'anio.integer' => 'El año debe ser un número.',
        'anio.min' => 'El año debe ser mayor a 2020.',
        'anio.max' => 'El año debe ser menor a 2050.',
        'idInstitucion.required' => 'La institución es obligatoria.',
        'idInstitucion.exists' => 'La institución seleccionada no existe.',
        'idUE.required' => 'La unidad ejecutora es obligatoria.',
        'idUE.exists' => 'La unidad ejecutora seleccionada no existe.',
    ];

    public function mount()
    {
        $this->anio = date('Y');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFiltroAnio()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function render()
    {
        $poas = Poa::with(['institucion', 'unidadEjecutora'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('anio', 'like', '%' . $this->search . '%')
                      ->orWhereHas('institucion', function ($q) {
                          $q->where('nombre', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('unidadEjecutora', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filtroAnio !== 'todos', function ($query) {
                $query->where('anio', $this->filtroAnio);
            })
            ->withCount('poaDeptos')
            ->orderBy($this->sortField, $this->sortDirection)
             ->paginate(12);

        $instituciones = Institucion::orderBy('nombre')->get();
        $unidadesEjecutoras = UnidadEjecutora::orderBy('name')->get();
        
        // Obtener años únicos para el filtro
        $anios = Poa::select('anio')
            ->distinct()
            ->whereNotNull('anio')
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        return view('livewire.consola.asignacion-presupuestaria', [
            'poas' => $poas,
            'instituciones' => $instituciones,
            'unidadesEjecutoras' => $unidadesEjecutoras,
            'anios' => $anios
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $poa = Poa::findOrFail($id);
        $this->poaId = $poa->id;
        $this->name = $poa->name;
        $this->anio = $poa->anio;
        $this->idInstitucion = $poa->idInstitucion;
        $this->idUE = $poa->idUE;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $poa = Poa::findOrFail($this->poaId);
            $poa->update([
                'name' => $this->name,
                'anio' => $this->anio,
                'idInstitucion' => $this->idInstitucion,
                'idUE' => $this->idUE,
            ]);
            session()->flash('message', 'POA actualizado correctamente.');
        } else {
            Poa::create([
                'name' => $this->name,
                'anio' => $this->anio,
                'idInstitucion' => $this->idInstitucion,
                'idUE' => $this->idUE,
            ]);
            session()->flash('message', 'POA creado correctamente.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->poaToDelete = Poa::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->poaToDelete) {
            // Verificar si tiene departamentos asociados
            if ($this->poaToDelete->poaDeptos()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el POA porque tiene departamentos asociados.');
                $this->closeDeleteModal();
                return;
            }

            $this->poaToDelete->delete();
            session()->flash('message', 'POA eliminado correctamente.');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->poaToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function resetForm()
    {
        $this->poaId = null;
        $this->name = '';
        $this->anio = date('Y');
        $this->idInstitucion = '';
        $this->idUE = '';
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filtroAnio = 'todos';
        $this->resetPage();
    }
}