<?php

namespace App\Livewire\Mes;

use App\Models\Mes\Trimestre;
use Livewire\Component;
use Livewire\WithPagination;

class Trimestres extends Component
{
    use WithPagination;

    // Propiedades para control de UI
    public $isOpen = false;
    public $isEditing = false;
    public $confirmingDelete = false;
    public $trimestreIdToDelete = null;
    public $perPage = 10;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    // Propiedades para datos del formulario
    public $trimestre_id;
    public $trimestre;

    // Reglas de validación
    protected $rules = [
        'trimestre' => 'required|string|max:100|unique:trimestres,trimestre',
    ];

    // Mensajes de validación personalizados
    protected $messages = [
        'trimestre.required' => 'El trimestre es obligatorio',
        'trimestre.unique' => 'Este trimestre ya existe',
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

    // Método para abrir el modal
    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = false;
        $this->isOpen = true;
    }

    // Método para cerrar el modal
    public function closeModal()
    {
        $this->isOpen = false;
    }

    // Método para limpiar los campos del formulario
    public function resetInputFields()
    {
        $this->trimestre_id = null;
        $this->trimestre = '';
        $this->resetValidation();
    }

    // Método para almacenar un nuevo trimestre o actualizar uno existente
    public function store()
    {
        // Actualizo las reglas de validación para el caso de edición
        if ($this->isEditing) {
            $this->rules['trimestre'] = 'required|string|max:100|unique:trimestres,trimestre,' . $this->trimestre_id;
        }

        $this->validate();

        try {
            Trimestre::updateOrCreate(
                ['id' => $this->trimestre_id],
                [
                    'trimestre' => $this->trimestre,
                ]
            );

            $this->closeModal();
            $this->resetInputFields();
            
            $message = $this->isEditing ? 'Trimestre actualizado exitosamente.' : 'Trimestre creado exitosamente.';
            $this->dispatch('alert', ['type' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Método para editar un trimestre existente
    public function edit($id)
    {
        $trimestreModel = Trimestre::findOrFail($id);
        $this->trimestre_id = $id;
        $this->trimestre = $trimestreModel->trimestre;
        $this->isEditing = true;
        $this->isOpen = true;
    }

    // Método para confirmar la eliminación de un trimestre
    public function confirmDelete($id)
    {
        $this->trimestreIdToDelete = $id;
        $this->confirmingDelete = true;
    }

    // Método para eliminar un trimestre
    public function delete()
    {
        try {
            Trimestre::find($this->trimestreIdToDelete)->delete();
            $this->confirmingDelete = false;
            $this->trimestreIdToDelete = null;
            session()->flash('message', 'Trimestre eliminado exitosamente.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el trimestre: ' . $e->getMessage());
        }
    }

    // Método para cancelar la eliminación
    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->trimestreIdToDelete = null;
    }

    public function render()
    {
        $trimestres = Trimestre::when($this->search, function($query) {
                $query->where('trimestre', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.mes.trimestres', [
            'trimestres' => $trimestres,
        ])->layout('layouts.app');
    }
}