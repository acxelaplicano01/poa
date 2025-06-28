<?php

namespace App\Livewire\Consola;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Pei;
use App\Models\Instituciones\Institucion;

class PlanEstrategicoInstitucional extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $peiToDelete = null;
    public $isEditing = false;
    public $peiId;
    public $sortField = 'id';
    public $sortDirection = 'asc';

    // Propiedades del formulario
    public $name = '';
    public $initialYear = '';
    public $finalYear = '';
    public $idInstitucion = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'initialYear' => 'required|integer|min:2000|max:2050',
        'finalYear' => 'required|integer|min:2000|max:2050|gte:initialYear',
        'idInstitucion' => 'required|exists:institucions,id',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'initialYear.required' => 'El año inicial es obligatorio.',
        'initialYear.integer' => 'El año inicial debe ser un número.',
        'initialYear.min' => 'El año inicial debe ser mayor a 2000.',
        'initialYear.max' => 'El año inicial debe ser menor a 2050.',
        'finalYear.required' => 'El año final es obligatorio.',
        'finalYear.integer' => 'El año final debe ser un número.',
        'finalYear.min' => 'El año final debe ser mayor a 2000.',
        'finalYear.max' => 'El año final debe ser menor a 2050.',
        'finalYear.gte' => 'El año final debe ser mayor o igual al año inicial.',
        'idInstitucion.required' => 'La institución es obligatoria.',
        'idInstitucion.exists' => 'La institución seleccionada no existe.',
    ];

    public function updatingSearch()
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
        $peis = Pei::with('institucion')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('institucion', function ($q) {
                          $q->where('nombre', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $instituciones = Institucion::orderBy('nombre')->get();

        return view('livewire.consola.pei.plan-estrategico-institucional', [
            'peis' => $peis,
            'instituciones' => $instituciones
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
        $pei = Pei::findOrFail($id);
        $this->peiId = $pei->id;
        $this->name = $pei->name;
        $this->initialYear = $pei->initialYear;
        $this->finalYear = $pei->finalYear;
        $this->idInstitucion = $pei->idInstitucion;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Validación adicional para años
        if ($this->initialYear && $this->finalYear) {
            if ($this->finalYear < $this->initialYear) {
                $this->addError('finalYear', 'El año final debe ser mayor o igual al año inicial.');
                return;
            }
        }

        if ($this->isEditing) {
            $pei = Pei::findOrFail($this->peiId);
            $pei->update([
                'name' => $this->name,
                'initialYear' => (int) $this->initialYear,
                'finalYear' => (int) $this->finalYear,
                'idInstitucion' => $this->idInstitucion,
            ]);
            session()->flash('message', 'PEI actualizado correctamente.');
        } else {
            Pei::create([
                'name' => $this->name,
                'initialYear' => (int) $this->initialYear,
                'finalYear' => (int) $this->finalYear,
                'idInstitucion' => $this->idInstitucion,
            ]);
            session()->flash('message', 'PEI creado correctamente.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $this->peiToDelete = Pei::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if (!$this->peiToDelete) {
            return;
        }

        // Verificar si tiene dimensiones asociadas
        if ($this->peiToDelete->dimensions()->count() > 0) {
            session()->flash('error', 'No se puede eliminar el PEI porque tiene dimensiones asociadas.');
            $this->closeDeleteModal();
            return;
        }

        $this->peiToDelete->delete();
        session()->flash('message', 'PEI eliminado correctamente.');
        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->peiToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function resetForm()
    {
        $this->peiId = null;
        $this->name = '';
        $this->initialYear = '';
        $this->finalYear = '';
        $this->idInstitucion = '';
    }

    // Validación en tiempo real para el año inicial
    public function updatedInitialYear()
    {
        $this->validateOnly('initialYear');
        
        // Si hay año final, validar que sea coherente
        if ($this->finalYear && $this->initialYear && $this->finalYear < $this->initialYear) {
            $this->addError('finalYear', 'El año final debe ser mayor o igual al año inicial.');
        }
    }

    // Validación en tiempo real para el año final
    public function updatedFinalYear()
    {
        $this->validateOnly('finalYear');
        
        // Validar que sea mayor o igual al año inicial
        if ($this->initialYear && $this->finalYear && $this->finalYear < $this->initialYear) {
            $this->addError('finalYear', 'El año final debe ser mayor o igual al año inicial.');
        }
    }
}