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
    public $showDeleteModal = false;
    public $grupoGastoToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;

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
            $this->showError('Error al guardar: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $grupoGasto = GrupoGasto::findOrFail($id);
            $this->grupoGastoId = $id;
            $this->nombre = $grupoGasto->nombre;
            $this->identificador = $grupoGasto->identificador;
            
            $this->isModalOpen = true;
        } catch (\Exception $e) {
            $this->showError('Error al cargar el grupo de gastos: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        try {
            $this->grupoGastoToDelete = GrupoGasto::findOrFail($id);
            $this->showDeleteModal = true;
        } catch (\Exception $e) {
            $this->showError('Error al cargar el grupo de gastos: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            if ($this->grupoGastoToDelete) {
                $this->grupoGastoToDelete->delete();
                session()->flash('message', 'Grupo de gastos eliminado correctamente.');
            }
        } catch (\Exception $e) {
            $this->showError('No se pudo eliminar el grupo de gastos: ' . $e->getMessage());
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
        $this->grupoGastoToDelete = null;
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
        $grupoGastos = GrupoGasto::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('identificador', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.grupo-gastos.grupo-gasto', [
            'grupoGastos' => $grupoGastos
        ])->layout('layouts.app');
    }
}
