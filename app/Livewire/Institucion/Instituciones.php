<?php

namespace App\Livewire\Institucion;

use App\Models\Instituciones\Institucion;
use Livewire\Component;
use Livewire\WithPagination;

class Instituciones extends Component
{
    use WithPagination;

    public $nombre;
    public $descripcion;
    public $institucionId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $isModalOpen = false;
    public $showDeleteModal = false;
    public $institucionToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;

    protected $rules = [
        'nombre' => 'required|min:3|max:255',
        'descripcion' => 'nullable|max:1000',
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
        $this->institucionId = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        try {
            Institucion::updateOrCreate(['id' => $this->institucionId], [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion
            ]);

            session()->flash('message', $this->institucionId 
                ? 'Institución actualizada correctamente.' 
                : 'Institución creada correctamente.');

            $this->isModalOpen = false;
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->showError('Error al guardar la institución: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $institucion = Institucion::findOrFail($id);
            $this->institucionId = $id;
            $this->nombre = $institucion->nombre;
            $this->descripcion = $institucion->descripcion;
            
            $this->isModalOpen = true;
        } catch (\Exception $e) {
            $this->showError('Error al cargar la institución: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        try {
            $this->institucionToDelete = Institucion::findOrFail($id);
            $this->showDeleteModal = true;
        } catch (\Exception $e) {
            $this->showError('Error al cargar la institución: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            if ($this->institucionToDelete) {
                $this->institucionToDelete->delete();
                session()->flash('message', 'Institución eliminada correctamente.');
            }
        } catch (\Exception $e) {
            $this->showError('No se pudo eliminar la institución: ' . $e->getMessage());
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
        $this->institucionToDelete = null;
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
        $instituciones = Institucion::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('descripcion', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.institucion.instituciones', [
            'instituciones' => $instituciones
        ])->layout('layouts.app');
    }
}