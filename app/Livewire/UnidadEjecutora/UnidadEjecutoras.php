<?php
namespace App\Livewire\UnidadEjecutora;

use App\Models\Instituciones\Institucion;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use Livewire\Component;
use Livewire\WithPagination;

class UnidadEjecutoras extends Component
{
    use WithPagination;

    public $unidadId;
    public $name = '';
    public $descripcion = '';
    public $estructura = '';
    public $idInstitucion = '';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $isModalOpen = false;
    public $showDeleteModal = false;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $unidadToDelete;

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'descripcion' => 'nullable|max:255',
        'estructura' => 'nullable|max:255',
        'idInstitucion' => 'required|exists:institucions,id',
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
        $this->unidadId = null;
        $this->name = '';
        $this->descripcion = '';
        $this->estructura = '';
        $this->idInstitucion = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        UnidadEjecutora::updateOrCreate(['id' => $this->unidadId], [
            'name' => $this->name,
            'descripcion' => $this->descripcion,
            'estructura' => $this->estructura,
            'idInstitucion' => $this->idInstitucion,
        ]);

        session()->flash('message', $this->unidadId 
            ? 'Unidad Ejecutora actualizada correctamente.' 
            : 'Unidad Ejecutora creada correctamente.');

        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $unidad = UnidadEjecutora::findOrFail($id);
        $this->unidadId = $id;
        $this->name = $unidad->name;
        $this->descripcion = $unidad->descripcion;
        $this->estructura = $unidad->estructura;
        $this->idInstitucion = $unidad->idInstitucion;
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->unidadToDelete = UnidadEjecutora::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            if ($this->unidadToDelete) {
                $this->unidadToDelete->delete();
                session()->flash('message', 'Unidad Ejecutora eliminada correctamente.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo eliminar la Unidad Ejecutora.');
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
        $this->unidadToDelete = null;
    }

    public function showError($message)
    {
        $this->errorMessage = $message;
        $this->showErrorModal = true;
    }

    public function hideError()
    {
        $this->showErrorModal = false;
    }

    public function render()
    {
        $unidades = UnidadEjecutora::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $instituciones = Institucion::all();

        return view('livewire.unidad-ejecutora.unidad-ejecutora', [
            'unidades' => $unidades,
            'instituciones' => $instituciones
        ])->layout('layouts.app');
    }
}