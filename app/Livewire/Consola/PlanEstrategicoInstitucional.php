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
    public $isEditing = false;
    public $peiId;

    // Propiedades del formulario
    public $name = '';
    public $initialYear = '';
    public $finalYear = '';
    public $idInstitucion = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'initialYear' => 'required|integer|min:2000|max:2050',
        'finalYear' => 'required|integer|min:2000|max:2050|gte:initialYear',
        'idInstitucion' => 'required|exists:instituciones,id',
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

    public function render()
    {
        $peis = Pei::with('institucion')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('institucion', function ($q) {
                          $q->where('nombre', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $instituciones = Institucion::orderBy('nombre')->get();

        return view('livewire.consola.plan-estrategico-institucional', [
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

        if ($this->isEditing) {
            $pei = Pei::findOrFail($this->peiId);
            $pei->update([
                'name' => $this->name,
                'initialYear' => $this->initialYear,
                'finalYear' => $this->finalYear,
                'idInstitucion' => $this->idInstitucion,
            ]);
            session()->flash('message', 'PEI actualizado correctamente.');
        } else {
            Pei::create([
                'name' => $this->name,
                'initialYear' => $this->initialYear,
                'finalYear' => $this->finalYear,
                'idInstitucion' => $this->idInstitucion,
            ]);
            session()->flash('message', 'PEI creado correctamente.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $pei = Pei::findOrFail($id);
        
        // Verificar si tiene dimensiones asociadas
        if ($pei->dimensions()->count() > 0) {
            session()->flash('error', 'No se puede eliminar el PEI porque tiene dimensiones asociadas.');
            return;
        }

        $pei->delete();
        session()->flash('message', 'PEI eliminado correctamente.');
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
}