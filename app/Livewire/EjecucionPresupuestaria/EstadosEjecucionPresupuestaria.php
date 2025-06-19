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
    public $isDeleteModalOpen = false;
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

        EstadoEjecucionPresupuestaria::updateOrCreate(['id' => $this->estadoId], [
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
        $estado = EstadoEjecucionPresupuestaria::findOrFail($id);
        $this->estadoId = $id;
        $this->estado = $estado->estado;
        
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->estadoToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        try {
            EstadoEjecucionPresupuestaria::findOrFail($this->estadoToDelete)->delete();
            session()->flash('message', 'Estado eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo eliminar el estado.');
        }
        
        $this->isDeleteModalOpen = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
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