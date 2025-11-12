<?php

namespace App\Livewire\Consola\Dimensiones;

use App\Models\Dimension\Dimension as DimensionModel;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Pei;

class Dimension extends Component
{
    use WithPagination;

    protected string $layout = 'layouts.app';

    public $peiId; // Propiedad para almacenar el ID del PEI
    public $name;
    public $descripcion;
    public $dimensionId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $showModal = false; // Cambiar de isModalOpen a showModal
    public $showDeleteModal = false;
    public $dimensionToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $isEditing = false; // Variable para controlar si se está editando o creando
    public $peiToDelete; // Variable para almacenar el PEI a eliminar

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
        \Log::debug('Método create ejecutado'); // Registro de depuración
        $this->resetInputFields();
        $this->isEditing = false; // Crear nuevo
        $this->openModal();
    }

    public function openModal()
    {
        $this->showModal = true; // Actualizar la variable correcta
    }

    public function closeModal()
    {
        $this->showModal = false; // Actualizar la variable correcta
        $this->resetInputFields();
    }

    public function store()
    {
        \Log::debug('Datos recibidos en store:', [
            'nombre' => $this->name,
            'descripcion' => $this->descripcion,
            'idPei' => $this->peiId,
        ]);

        $this->validate();

        try {
            DimensionModel::updateOrCreate(['id' => $this->dimensionId], [
                'nombre' => $this->name,
                'descripcion' => $this->descripcion,
                'idPei' => $this->peiId, // Asegurar que se guarde el idPei
            ]);

            \Log::info('Dimensión guardada correctamente', [
                'nombre' => $this->name,
                'descripcion' => $this->descripcion,
                'idPei' => $this->peiId,
            ]);

            session()->flash('message', 
                $this->dimensionId ? 'Dimensión actualizada correctamente.' : 'Dimensión creada correctamente.'
            );

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('Error al guardar la Dimensión: ' . $e->getMessage());
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
        $this->peiToDelete = DimensionModel::findOrFail($id); // Cargar el PEI a eliminar
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $this->dimensionToDelete->delete();
            
            session()->flash('message', 'Dimensión eliminada correctamente.');
            $this->showDeleteModal = false;
            $this->resetPage();
        } catch (\Exception $e) {
            $this->errorMessage = 'Error al eliminar la Dimensión: ' . $e->getMessage();
            $this->showDeleteModal = false;
            $this->showErrorModal = true;
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->peiToDelete = null; // Limpiar la variable
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

 public function mount($peiId = null)
{
    $this->peiId = $peiId ?? request('pei') ?? null;

    if (!$this->peiId) {
        abort(400, 'El parámetro pei es obligatorio.');
    }

    // Opcional: validar que existe en BD
    if (!Pei::where('id', $this->peiId)->exists()) {
        abort(404, 'PEI no encontrado.');
    }

    \Log::debug('PEI ID montado:', ['peiId' => $this->peiId]);
}
    public function render()
    {
        $dimensions = DimensionModel::when($this->peiId, function ($query) {
                $query->where('idPei', $this->peiId); // Filtrar por el idPei actual
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $peis = Pei::orderBy('name')->get(); // Cambiar de 'nombre' a 'name'

        return view('livewire.consola.pei.Dimensiones.dimensiones', [
            'dimensions' => $dimensions,
            'peis' => $peis, // Pasar PEIs a la vista
        ])->layout('layouts.app');
    }
}