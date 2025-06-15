<?php

namespace App\Livewire\Actas;

use App\Models\Actas\TipoActaEntrega;
use Livewire\Component;
use Livewire\WithPagination;

class TipoActaEntregas extends Component
{
    use WithPagination;

    // Propiedades para la tabla
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'asc';

    // Propiedades para el modal
    public $isOpen = false;
    public $confirmingDelete = false;
    public $tipoActaEntrega_id;
    public $tipo;
    public $isEditing = false;
    public $tipoAEliminar;

    // Escuchar eventos
    protected $listeners = ['refresh' => '$refresh'];

    // Reglas de validación
    protected $rules = [
        'tipo' => 'required|string|max:100|unique:tipo_acta_entrega,tipo'
    ];

    protected $validationAttributes = [
        'tipo' => 'tipo de acta',
    ];

    // Método para ordenar columnas
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = false;
        $this->openModal();
    }

    public function edit($id)
    {
        $tipoActaEntrega = TipoActaEntrega::findOrFail($id);
        $this->tipoActaEntrega_id = $id;
        $this->tipo = $tipoActaEntrega->tipo;
        $this->isEditing = true;
        
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function store()
    {
        // Si estamos editando, ignorar la validación unique para el propio registro
        if ($this->isEditing) {
            $this->rules['tipo'] = 'required|string|max:100|unique:tipo_acta_entrega,tipo,'.$this->tipoActaEntrega_id;
        }

        $this->validate();

        TipoActaEntrega::updateOrCreate(['id' => $this->tipoActaEntrega_id], [
            'tipo' => $this->tipo,
        ]);

        session()->flash('message', $this->isEditing ? 
            'Tipo de acta de entrega actualizado correctamente.' : 
            'Tipo de acta de entrega creado correctamente.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->tipoActaEntrega_id = null;
        $this->tipo = '';
        $this->resetValidation();
    }

    public function confirmDelete($id)
    {
        $tipoActaEntrega = TipoActaEntrega::findOrFail($id);
        $this->tipoActaEntrega_id = $id;
        $this->tipoAEliminar = $tipoActaEntrega->tipo;
        $this->confirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->tipoActaEntrega_id = null;
        $this->tipoAEliminar = '';
    }

    public function delete()
    {
        try {
            $tipoActaEntrega = TipoActaEntrega::findOrFail($this->tipoActaEntrega_id);
            
            // Verificar si tiene actas asociadas
            if ($tipoActaEntrega->actas()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el tipo de acta de entrega porque tiene actas asociadas.');
                $this->confirmingDelete = false;
                return;
            }
            
            $tipoActaEntrega->delete();
            session()->flash('message', 'Tipo de acta de entrega eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al eliminar el tipo de acta de entrega.');
        }
        
        $this->confirmingDelete = false;
        $this->tipoActaEntrega_id = null;
    }

    public function render()
    {
        $tipoActaEntregas = TipoActaEntrega::query()
            ->when($this->search, function($query) {
                $query->where('tipo', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.actas.tipo-acta-entregas', [
            'tipoActaEntregas' => $tipoActaEntregas
        ])->layout('layouts.app');
    }
}