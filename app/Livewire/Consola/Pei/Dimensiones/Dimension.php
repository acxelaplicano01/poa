<?php

namespace App\Livewire\Consola\Pei\Dimensiones;

use App\Models\Dimension\Dimension as DimensionModel;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Pei;
use Illuminate\Support\Facades\DB;

class Dimension extends Component
{
    use WithPagination;

    protected string $layout = 'layouts.app';

    #[Url(as: 'pei')]
    public ?int $peiId = null;
    public $name;
    public $descripcion;
    public $dimensionId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $showDeleteModal = false;
    public $dimensionToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $isEditing = false;
    public $peiToDelete;

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'descripcion' => 'nullable|max:1000',
        'peiId' => 'required|exists:peis,id',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.min' => 'El nombre debe tener al menos 3 caracteres.',
        'name.max' => 'El nombre no puede exceder 255 caracteres.',
        'descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
        'peiId.required' => 'El PEI es obligatorio.',
        'peiId.exists' => 'El PEI seleccionado no existe.',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatedName($value)
    {
        $this->name = is_array($value) ? '' : $value;
    }

    public function updatedDescripcion($value)
    {
        $this->descripcion = is_array($value) ? '' : $value;
    }

    public function updatedPeiId($value)
    {
        $this->peiId = is_array($value) ? null : $value;
    }

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

    public function resetInputFields()
    {
        $this->name = '';
        $this->descripcion = '';
        $this->dimensionId = null;
        $this->resetValidation();
       
    }

    public function create()
    {
        \Log::debug('Método create ejecutado');
        $this->resetInputFields();
        $this->isEditing = false;
        $this->openModal();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function store()
    {
        \Log::debug('Datos recibidos en store:', [
            'nombre' => $this->name,
            'descripcion' => $this->descripcion,
            'peiId' => $this->peiId,
        ]);

        $this->validate();

        try {
            $dimension = DimensionModel::updateOrCreate(['id' => $this->dimensionId], [
                'nombre' => $this->name,
                'descripcion' => $this->descripcion,
                'idPei' => $this->peiId,
            ]);

            session()->flash('message', 
                $this->dimensionId 
                    ? 'Dimensión actualizada correctamente.' 
                    : 'Dimensión creada y asociada al PEI correctamente.'
            );

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('Error al guardar la Dimensión: ' . $e->getMessage(), [
                'peiId' => $this->peiId,
                'dimensionId' => $this->dimensionId,
            ]);
            $this->errorMessage = 'Error al guardar la Dimensión: ' . $e->getMessage();
            $this->showErrorModal = true;
        }
    }

    public function edit($id)
    {
        $dimension = DimensionModel::findOrFail($id);
        $this->dimensionId = $id;
        $this->name = $dimension->nombre;        
        $this->descripcion = $dimension->descripcion;
        $this->peiId = $dimension->idPei;
        $this->isEditing = true;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->dimensionToDelete = DimensionModel::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $dimensionId = $this->dimensionToDelete->id;
            $this->dimensionToDelete->delete();
            
            // Eliminar de pei_elementos
            DB::table('pei_elementos')
                ->where('elemento_id', $dimensionId)
                ->where('elemento_tipo', 'dimensiones')
                ->delete();

            session()->flash('message', 'Dimensión eliminada correctamente.');
            $this->showDeleteModal = false;
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('Error al eliminar dimensión: ' . $e->getMessage());
            $this->errorMessage = 'Error al eliminar la Dimensión: ' . $e->getMessage();
            $this->showDeleteModal = false;
            $this->showErrorModal = true;
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->dimensionToDelete = null;
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

 

    public function render()
{

    if ($this->peiId === null) {
        abort(400, 'PEI no especificado. Use ?pei=1 en la URL.');
    }

    if (!Pei::where('id', $this->peiId)->exists()) {
        abort(404, 'PEI no encontrado.');
    }

    $dimensions = DimensionModel::where('idPei', $this->peiId)
        ->when($this->search, function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->search . '%');
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);

    $peis = Pei::orderBy('name')->get();

    return view('livewire.consola.pei.Dimensiones.dimensiones', [
        'dimensions' => $dimensions,
        'peis' => $peis,
    ])->layout('layouts.app');
}
}