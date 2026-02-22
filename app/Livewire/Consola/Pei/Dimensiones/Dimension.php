<?php

namespace App\Livewire\Consola\Pei\Dimensiones;

use App\Models\Dimension\Dimension as DimensionModel;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Pei;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
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
            // Crear o actualizar la dimensión
            $dimension = DimensionModel::updateOrCreate(['id' => $this->dimensionId], [
                'nombre' => $this->name,
                'descripcion' => $this->descripcion,
                'idPei' => $this->peiId, 
            ]);

            // Asociar la dimensión al PEI en la tabla pei_elementos
            if (!$this->dimensionId) {
                DB::table('pei_elementos')->insert([
                    'idPei' => $this->peiId,
                    'elemento_id' => $dimension->id,
                    'elemento_tipo' => 'dimensiones',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

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
        try {
            // Buscar la dimensión por su ID
            $dimension = DimensionModel::findOrFail($id);

            // Asignar los valores a las propiedades del componente
            $this->dimensionId = $dimension->id;
            $this->name = $dimension->nombre;
            $this->descripcion = $dimension->descripcion;
            $this->peiId = $dimension->idPei;

            $this->isEditing = true;
            $this->openModal();
        } catch (\Exception $e) {
            \Log::error('Error al intentar editar la dimensión: ' . $e->getMessage());
            $this->errorMessage = 'Error al intentar editar la dimensión.';
            $this->showErrorModal = true;
        }
    }

    public function confirmDelete($id)
    {
        try {
            // Buscar la dimensión a eliminar
            $this->dimensionToDelete = DimensionModel::findOrFail($id);
            $this->showDeleteModal = true;
        } catch (\Exception $e) {
            \Log::error('Error al intentar confirmar eliminación de dimensión: ' . $e->getMessage());
            $this->errorMessage = 'Error al intentar confirmar eliminación de la dimensión.';
            $this->showErrorModal = true;
        }
    }

    public function delete()
    {
        try {
            // Validar que exista una dimensión para eliminar
            if (!$this->dimensionToDelete) {
                throw new \Exception('No se encontró la dimensión para eliminar.');
            }

            $dimensionId = $this->dimensionToDelete->id;

            // Verificar si la dimensión tiene objetivos asociados
            $tieneObjetivos = DB::table('objetivos')
                ->where('idDimension', $dimensionId)
                ->exists();

            if ($tieneObjetivos) {
                session()->flash('error', 'No se puede eliminar esta dimensión porque tiene objetivos asociados. Elimine primero los objetivos.');
                $this->showDeleteModal = false;
                return;
            }

            // Eliminar la dimensión
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
            $this->errorMessage = 'Error al eliminar la dimensión: ' . $e->getMessage();
            $this->showDeleteModal = false;
            $this->showErrorModal = true;
        } finally {
            $this->dimensionToDelete = null;
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

    public function mount($pei = null)
    {
        try {
            $this->peiId = $pei ?? request()->query('pei');

            // Validar PEI
            if ($this->peiId === null) {
                throw new \Exception('PEI no especificado. Use ?pei=1 en la URL.');
            }

            // Verificar si el PEI existe en la tabla pei_elementos
            $peiExists = DB::table('pei_elementos')
                ->where('idPei', $this->peiId)
                ->where('elemento_tipo', 'dimensiones')
                ->exists();

            if (!$peiExists) {
                throw new \Exception('El PEI especificado no tiene dimensiones asociadas.');
            }
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->showErrorModal = true;
        }
    }

    public function render()
    {
        try {
            // Validar que el PEI esté especificado
            if ($this->peiId === null) {
                throw new \Exception('PEI no especificado. Use ?pei=1 en la URL.');
            }

            // Verificar que el PEI exista en la tabla peis
            if (!Pei::where('id', $this->peiId)->exists()) {
                throw new \Exception('El PEI especificado no existe.');
            }

            // Obtener las dimensiones asociadas al PEI
            $dimensions = DimensionModel::where('idPei', $this->peiId)
                ->when($this->search, function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                          ->orWhere('descripcion', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->showErrorModal = true;

            // Retornar un paginador vacío en caso de error
            $dimensions = DimensionModel::whereRaw('1 = 0')->paginate($this->perPage);
        }

        return view('livewire.consola.pei.Dimensiones.dimensiones', [
            'dimensions' => $dimensions,
        ]);
    }
}