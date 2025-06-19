<?php

namespace App\Livewire\GrupoGastos;

use App\Models\GrupoGastos\GrupoGasto;
use Livewire\Component;
use Livewire\WithPagination;

class GrupoGastos extends Component
{
    use WithPagination;

    public $nombre;
    public $identificador;
    public $grupoGastoId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $grupoGastoToDelete;

    protected $rules = [
        'nombre' => 'required|min:3|max:100',
        'identificador' => 'required|numeric', // Cambiar de min:2|max:20 a numeric
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
        $this->grupoGastoId = null;
        $this->nombre = '';
        $this->identificador = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required|min:3|max:100',
            'identificador' => 'required|numeric', // Asegurarse de que sea un número
        ]);

        try {
            GrupoGasto::updateOrCreate(['id' => $this->grupoGastoId], [
                'nombre' => $this->nombre,
                'identificador' => (int)$this->identificador, // Convertir a entero
                'created_by' => auth()->id(),
            ]);

            session()->flash('message', $this->grupoGastoId 
                ? 'Grupo de gastos actualizado correctamente.' 
                : 'Grupo de gastos creado correctamente.');

            $this->isModalOpen = false;
            $this->resetInputFields();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $grupoGasto = GrupoGasto::findOrFail($id);
        $this->grupoGastoId = $id;
        $this->nombre = $grupoGasto->nombre;
        $this->identificador = $grupoGasto->identificador;
        
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->grupoGastoToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        try {
            GrupoGasto::findOrFail($this->grupoGastoToDelete)->delete();
            session()->flash('message', 'Grupo de gastos eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo eliminar el grupo de gastos.');
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
        $grupoGastos = GrupoGasto::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('identificador', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.grupo-gastos.grupo-gasto', [
            'grupoGastos' => $grupoGastos
        ])->layout('layouts.app');
    }
}
