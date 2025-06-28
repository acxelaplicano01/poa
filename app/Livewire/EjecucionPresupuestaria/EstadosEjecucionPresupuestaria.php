<?php

namespace App\Livewire\EjecucionPresupuestaria;

use App\Models\EjecucionPresupuestaria\EstadoEjecucionPresupuestaria;
use Livewire\Component;
use Livewire\WithPagination;

class EstadosEjecucionPresupuestaria extends Component
{
    use WithPagination;

    public $estado;
    public $estadoId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $isModalOpen = false;
    public $showDeleteModal = false;
    public $estadoToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;

    protected $rules = [
        'estado' => 'required|min:3|max:100',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function resetInputFields()
    {
        $this->estadoId = null;
        $this->estado = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        try {
            EstadoEjecucionPresupuestaria::updateOrCreate(['id' => $this->estadoId], [
                'estado' => $this->estado
            ]);

            session()->flash('message', $this->estadoId 
                ? 'Estado actualizado correctamente.' 
                : 'Estado creado correctamente.');

            $this->isModalOpen = false;
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->showError('Error al guardar el estado: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $estado = EstadoEjecucionPresupuestaria::findOrFail($id);
            $this->estadoId = $id;
            $this->estado = $estado->estado;
            
            $this->isModalOpen = true;
        } catch (\Exception $e) {
            $this->showError('Error al cargar el estado: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        try {
            $this->estadoToDelete = EstadoEjecucionPresupuestaria::findOrFail($id);
            $this->showDeleteModal = true;
        } catch (\Exception $e) {
            $this->showError('Error al cargar el estado: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            if ($this->estadoToDelete) {
                $this->estadoToDelete->delete();
                session()->flash('message', 'Estado eliminado correctamente.');
            }
        } catch (\Exception $e) {
            $this->showError('No se pudo eliminar el estado: ' . $e->getMessage());
        }
        
        $this->closeDeleteModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->estadoToDelete = null;
    }

    public function showError($message)
    {
        $this->errorMessage = $message;
        $this->showErrorModal = true;
    }

    public function hideError()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function render()
    {
        $estados = EstadoEjecucionPresupuestaria::where('estado', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.ejecucion-presupuestaria.estado-ejecucion-presupuestaria', [
            'estados' => $estados
        ])->layout('layouts.app');
    }
}