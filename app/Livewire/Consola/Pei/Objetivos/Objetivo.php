<?php

namespace App\Livewire\Consola\Pei\Objetivos;

use App\Models\Objetivos\Objetivo as ObjetivoModel;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Pei;
use App\Models\Dimension\Dimension as DimensionModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Objetivo extends Component
{
    use WithPagination;

    protected string $layout = 'layouts.app';

    #[Url(as: 'dimension')]
    public ?int $idDimension = null; 

    #[Url(as: 'pei')] 
    public ?int $peiId = null;

    public $nombre;
    public $descripcion;
    public $objetivoId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $showDeleteModal = false;
    public $objetivoToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $isEditing = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'descripcion' => 'nullable',
        'idDimension' => 'required|exists:dimensions,id',
        'peiId' => 'required|exists:peis,id',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'idDimension.required' => 'La dimensión es obligatoria.',
        'idDimension.exists' => 'La dimensión seleccionada no existe.',
        'peiId.required' => 'El PEI es obligatorio.',
        'peiId.exists' => 'El PEI seleccionado no existe.',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatedNombre($value)
    {
        $this->nombre = is_array($value) ? '' : $value;
    }

    public function updatedDescripcion($value)
    {
        $this->descripcion = is_array($value) ? '' : $value;
    }

    public function updatedIdDimension($value)
    {
        $this->idDimension = is_array($value) ? null : $value;
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
        $this->nombre = '';
        $this->descripcion = '';
        $this->objetivoId = null;
        $this->resetValidation();
    }

    public function create()
    {
        \Log::debug('Método create ejecutado para Objetivo');
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
    \Log::debug('Datos recibidos en store (Objetivo):', [
        'nombre' => $this->nombre,
        'descripcion' => $this->descripcion,
        'idDimension' => $this->idDimension,
    ]);

    // Validar que la dimensión pertenece al PEI especificado
    $dimension = DimensionModel::where('id', $this->idDimension)
        ->first();

    if (!$dimension) {
        $this->errorMessage = 'Error: La dimensión seleccionada no existe.';
        $this->showErrorModal = true;
        return;
    }

    // VALIDACIÓN 3: reglas formales
    $this->validate();

    try {
        // Preparar los datos para crear o actualizar el objetivo
        $data = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'idDimension' => $this->idDimension,
        ];

        $objetivo = ObjetivoModel::updateOrCreate(
            ['id' => $this->objetivoId],
            $data
        );

        // Registrar en pei_elementos SOLO si es nuevo
        if (!$this->objetivoId) {
            $peiElementoData = [
                'idPei' => $dimension->idPei, // Cambiado a idPei según la estructura de la tabla
                'elemento_id' => $objetivo->id,
                'elemento_tipo' => 'objetivos', // ← plural, minúsculas
                'created_at' => now(),
                'updated_at' => now(),
            ];

            \Log::debug('Insertando en pei_elementos:', $peiElementoData);

            DB::table('pei_elementos')->insert($peiElementoData);
        }

        session()->flash('message', 
            $this->objetivoId 
                ? 'Objetivo actualizado correctamente.' 
                : 'Objetivo creado correctamente.'
        );

        $this->closeModal();
        $this->resetPage();
    } catch (\Exception $e) {
        \Log::error('Error en store() (Objetivo): ' . $e->getMessage(), [
            'idDimension' => $this->idDimension,
        ]);
        $this->errorMessage = 'Error al guardar: ' . str($e->getMessage())->before('(');
        $this->showErrorModal = true;
    }
}

    public function edit($id)
    {
        $objetivo = ObjetivoModel::with('dimension')->findOrFail($id);
        $this->objetivoId = $id;
        $this->nombre = $objetivo->nombre;
        $this->descripcion = $objetivo->descripcion;
        $this->idDimension = $objetivo->idDimension;
        
        //  Obtener peiId desde la dimensión (porque objetivos no lo tiene)
        $this->peiId = $objetivo->dimension?->idPei;
        
        $this->isEditing = true;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->objetivoToDelete = ObjetivoModel::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $objetivoId = $this->objetivoToDelete->id;
            $this->objetivoToDelete->delete();
            
            // Eliminar de pei_elementos
            DB::table('pei_elementos')
                ->where('elemento_id', $objetivoId)
                ->where('elemento_tipo', 'objetivos')
                ->delete();

            session()->flash('message', 'Objetivo eliminado correctamente.');
            $this->showDeleteModal = false;
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('Error al eliminar objetivo: ' . $e->getMessage());
            $this->errorMessage = 'Error al eliminar el objetivo: ' . $e->getMessage();
            $this->showDeleteModal = false;
            $this->showErrorModal = true;
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->objetivoToDelete = null;
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function render()
    {
        // Validación en cada render
        if ($this->peiId === null) {
            abort(400, 'PEI no especificado. Use ?pei=1 en la URL.');
        }

        if (!Pei::where('id', $this->peiId)->exists()) {
            abort(404, 'PEI no encontrado.');
        }

        // Validar que la dimensión esté especificada
        if ($this->idDimension === null) {
            abort(400, 'Dimensión no especificada. Use ?dimension=1 en la URL.');
        }

        if (!DimensionModel::where('id', $this->idDimension)->exists()) {
            abort(404, 'Dimensión no encontrada.');
        }

        // Filtrar objetivos por la dimensión actual e incluir áreas relacionadas
        $objetivos = ObjetivoModel::with('areas')
            ->where('idDimension', $this->idDimension)
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.consola.pei.Objetivos.objetivos', [
            'objetivos' => $objetivos,
        ]);
    }
}