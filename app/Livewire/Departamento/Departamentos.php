<?php

namespace App\Livewire\Departamento;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Departamento\Departamento;
use App\Models\UnidadEjecutora\UnidadEjecutora;

class Departamentos extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $departamentoToDelete = null;
    public $isEditing = false;
    public $departamentoId;
    public $filtroTipo = 'todos'; // Filtro para tipo de departamento

    // Propiedades del formulario
    public $name = '';
    public $siglas = '';
    public $estructura = '';
    public $tipo = '';
    public $idUnidadEjecutora = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'siglas' => 'required|string|max:10',
        'estructura' => 'nullable|string|max:255',
        'tipo' => 'required|string|max:100',
        'idUnidadEjecutora' => 'required|exists:unidad_ejecutora,id',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.max' => 'El nombre no puede exceder 255 caracteres.',
        'siglas.required' => 'Las siglas son obligatorias.',
        'siglas.max' => 'Las siglas no pueden exceder 10 caracteres.',
        'estructura.max' => 'La estructura no puede exceder 255 caracteres.',
        'tipo.required' => 'El tipo es obligatorio.',
        'tipo.max' => 'El tipo no puede exceder 100 caracteres.',
        'idUnidadEjecutora.required' => 'La unidad ejecutora es obligatoria.',
        'idUnidadEjecutora.exists' => 'La unidad ejecutora seleccionada no existe.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFiltroTipo()
    {
        $this->resetPage();
    }    
    public function render()
    {
        $departamentos = Departamento::with(['unidadEjecutora'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('siglas', 'like', '%' . $this->search . '%')
                      ->orWhere('tipo', 'like', '%' . $this->search . '%')
                      ->orWhereHas('unidadEjecutora', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filtroTipo !== 'todos', function ($query) {
                $query->where('tipo', $this->filtroTipo);
            })
            ->withCount('empleados')
            ->orderBy('name')
            ->paginate(10);

        $unidadesEjecutoras = UnidadEjecutora::orderBy('name')->get();
        
        // Obtener tipos únicos para el filtro
        $tipos = Departamento::select('tipo')
            ->distinct()
            ->whereNotNull('tipo')
            ->pluck('tipo');

        return view('livewire.departamento.departamentos', [
            'departamentos' => $departamentos,
            'unidadesEjecutoras' => $unidadesEjecutoras,
            'tipos' => $tipos
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $departamento = Departamento::findOrFail($id);
        $this->departamentoId = $departamento->id;
        $this->name = $departamento->name;
        $this->siglas = $departamento->siglas;
        $this->estructura = $departamento->estructura;
        $this->tipo = $departamento->tipo;
        $this->idUnidadEjecutora = $departamento->idUnidadEjecutora;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $departamento = Departamento::findOrFail($this->departamentoId);
            $departamento->update([
                'name' => $this->name,
                'siglas' => $this->siglas,
                'estructura' => $this->estructura,
                'tipo' => $this->tipo,
                'idUnidadEjecutora' => $this->idUnidadEjecutora,
            ]);
            session()->flash('message', 'Departamento actualizado correctamente.');
        } else {
            Departamento::create([
                'name' => $this->name,
                'siglas' => $this->siglas,
                'estructura' => $this->estructura,
                'tipo' => $this->tipo,
                'idUnidadEjecutora' => $this->idUnidadEjecutora,
            ]);
            session()->flash('message', 'Departamento creado correctamente.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->departamentoToDelete = Departamento::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->departamentoToDelete) {
            // Verificar si tiene empleados asociados
            if ($this->departamentoToDelete->empleados()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el departamento porque tiene empleados asociados.');
                $this->closeDeleteModal();
                return;
            }

            $this->departamentoToDelete->delete();
            session()->flash('message', 'Departamento eliminado correctamente.');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->departamentoToDelete = null;
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->departamentoId = null;
        $this->name = '';
        $this->siglas = '';
        $this->estructura = '';
        $this->tipo = '';
        $this->idUnidadEjecutora = '';
        $this->resetValidation();
    }
}
