<?php

namespace App\Livewire\GrupoGastos;

use App\Models\GrupoGastos\Fuente;
use Livewire\Component;
use Livewire\WithPagination;

class Fuentes extends Component
{
    use WithPagination;

    public $nombre;
    public $identificador;
    public $fuenteId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $isModalOpen = false;
    public $showDeleteModal = false;
    public $fuenteToDelete;

    protected $rules = [
        'nombre' => 'required|min:3|max:100',
        'identificador' => 'required|min:2|max:20',
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
        $this->fuenteId = null;
        $this->nombre = '';
        $this->identificador = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        // Validar unicidad del identificador
        $query = Fuente::where('identificador', $this->identificador);
        if ($this->fuenteId) {
            $query->where('id', '!=', $this->fuenteId);
        }
        
        if ($query->exists()) {
            $this->addError('identificador', 'El identificador ya está en uso.');
            return;
        }

        Fuente::updateOrCreate(['id' => $this->fuenteId], [
            'nombre' => $this->nombre,
            'identificador' => $this->identificador
        ]);

        session()->flash('message', $this->fuenteId 
            ? 'Fuente actualizada correctamente.' 
            : 'Fuente creada correctamente.');

        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $fuente = Fuente::findOrFail($id);
        $this->fuenteId = $id;
        $this->nombre = $fuente->nombre;
        $this->identificador = $fuente->identificador;
        
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->fuenteToDelete = Fuente::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            if ($this->fuenteToDelete) {
                $this->fuenteToDelete->delete();
                session()->flash('message', 'Fuente eliminada correctamente.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo eliminar la fuente.');
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
        $this->fuenteToDelete = null;
    }

    public function render()
    {
        $fuentes = Fuente::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('identificador', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.grupo-gastos.fuentes.fuentes', [
            'fuentes' => $fuentes
        ])->layout('layouts.app');
    }
}