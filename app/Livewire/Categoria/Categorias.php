<?php

namespace App\Livewire\Categoria;

use App\Models\Categoria\Categoria;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Categorias extends Component
{
    use WithPagination;

    public $categoria;
    public $categoriaId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $isModalOpen = false;
    public $showDeleteModal = false;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $categoriaToDelete;

    protected $rules = [
        'categoria' => 'required|min:3|max:50',
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
        $this->categoriaId = null;
        $this->categoria = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        Categoria::updateOrCreate(['id' => $this->categoriaId], [
            'categoria' => $this->categoria
        ]);

        session()->flash('message', $this->categoriaId 
            ? 'Categoría actualizada correctamente.' 
            : 'Categoría creada correctamente.');

        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        $this->categoriaId = $id;
        $this->categoria = $categoria->categoria;
        
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->categoriaToDelete = Categoria::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            if ($this->categoriaToDelete) {
                $this->categoriaToDelete->delete();
                session()->flash('message', 'Categoría eliminada correctamente.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo eliminar la categoría.');
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
        $this->categoriaToDelete = null;
    }

    // Mostrar error en modal
    public function showError($message)
    {
        $this->errorMessage = $message;
        $this->showErrorModal = true;
    }

    // Ocultar modal de error
    public function hideError()
    {
        $this->showErrorModal = false;
    }

    public function render()
    {
        $categorias = Categoria::where('categoria', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.categoria.categorias', [
            'categorias' => $categorias
        ]);
    }
}