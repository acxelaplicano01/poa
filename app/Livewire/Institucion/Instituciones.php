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
    public $isDeleteModalOpen = false;
    public $institucionToDelete;

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

        Institucion::updateOrCreate(['id' => $this->institucionId], [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion
        ]);

        session()->flash('message', $this->institucionId 
            ? 'Institución actualizada correctamente.' 
            : 'Institución creada correctamente.');

        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $institucion = Institucion::findOrFail($id);
        $this->institucionId = $id;
        $this->nombre = $institucion->nombre;
        $this->descripcion = $institucion->descripcion;
        
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->institucionToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        try {
            Institucion::findOrFail($this->institucionToDelete)->delete();
            session()->flash('message', 'Institución eliminada correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo eliminar la institución.');
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
        $instituciones = Institucion::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('descripcion', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.institucion.instituciones', [
            'instituciones' => $instituciones
        ])->layout('layouts.app');
    }
}