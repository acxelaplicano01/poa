<?php

namespace App\Livewire\Requisicion;

use App\Models\Requisicion\EstadoRequisicion;
use Livewire\Component;
use Livewire\WithPagination;

class EstadosRequisicion extends Component
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

        EstadoRequisicion::updateOrCreate(['id' => $this->estadoId], [
            'estado' => $this->estado
        ]);

        session()->flash('message', $this->estadoId 
            ? 'Estado actualizado correctamente.' 
            : 'Estado creado correctamente.');

        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $estado = EstadoRequisicion::findOrFail($id);
        $this->estadoId = $id;
        $this->estado = $estado->estado;
        
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->estadoToDelete = EstadoRequisicion::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            if ($this->estadoToDelete) {
                $this->estadoToDelete->delete();
                session()->flash('message', 'Estado eliminado correctamente.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo eliminar el estado.');
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

    public function render()
    {
        $estados = EstadoRequisicion::where('estado', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.requisicion.estados-requisicion', [
            'estados' => $estados
        ])->layout('layouts.app');
    }
}