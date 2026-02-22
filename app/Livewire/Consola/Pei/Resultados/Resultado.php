<?php

namespace App\Livewire\Consola\Pei\Resultados;

use App\Models\Resultados\Resultado as ResultadoModel;
use App\Models\Areas\Area as AreaModel;
use App\Models\Objetivos\Objetivo as ObjetivoModel;
use App\Models\Dimension\Dimension as DimensionModel;
use App\Models\Poa\Pei;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Resultado extends Component
{
    use WithPagination;

    protected string $layout = 'layouts.app';

    #[Url(as: 'area')]
    public ?int $idArea = null;

    #[Url(as: 'pei')]
    public ?int $peiId = null;

    public $nombre;
    public $descripcion;
    public $resultadoId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $showDeleteModal = false;
    public $resultadoToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $isEditing = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'descripcion' => 'nullable',
        'idArea' => 'required|exists:areas,id',
        'peiId' => 'required|exists:peis,id',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'idArea.required' => 'El área es obligatoria.',
        'idArea.exists' => 'El área seleccionada no existe.',
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

    public function updatedIdArea($value)
    {
        $this->idArea = is_array($value) ? null : $value;
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
        $this->resultadoId = null;
        $this->resetValidation();
    }

    public function create()
    {
        \Log::debug('Método create ejecutado para Resultado');
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
        \Log::debug('Datos recibidos en store (Resultado):', [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'idArea' => $this->idArea,
            'peiId' => $this->peiId,
        ]);

        $this->validate();

        try {
            $data = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'idArea' => $this->idArea,
            ];

            $resultado = ResultadoModel::updateOrCreate(
                ['id' => $this->resultadoId],
                $data
            );

            // Registrar en pei_elementos SOLO si es nuevo
            if (!$this->resultadoId) {
                // Validar que tenemos peiId
                if (!$this->peiId) {
                    // Intentar obtenerlo de la cadena de relaciones
                    $area = AreaModel::with('objetivo.dimension')->find($this->idArea);
                    $this->peiId = $area?->objetivo?->dimension?->idPei;
                    
                    if (!$this->peiId) {
                        throw new \Exception('No se pudo determinar el PEI para este resultado.');
                    }
                }

                $peiElementoData = [
                    'idPei' => $this->peiId,
                    'elemento_id' => $resultado->id,
                    'elemento_tipo' => 'resultados',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                \Log::debug('Insertando en pei_elementos:', $peiElementoData);
                DB::table('pei_elementos')->insert($peiElementoData);
            }

            session()->flash('message', 
                $this->resultadoId 
                    ? 'Resultado actualizado correctamente.' 
                    : 'Resultado creado y asociado al PEI correctamente.'
            );

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('Error en store() (Resultado): ' . $e->getMessage(), [
                'idArea' => $this->idArea,
                'peiId' => $this->peiId,
            ]);
            $this->errorMessage = 'Error al guardar: ' . str($e->getMessage())->before('(');
            $this->showErrorModal = true;
        }
    }

    public function edit($id)
    {
        $resultado = ResultadoModel::with('area.objetivo.dimension')->findOrFail($id);
        $this->resultadoId = $id;
        $this->nombre = $resultado->nombre;
        $this->descripcion = $resultado->descripcion;
        $this->idArea = $resultado->idArea;
        $this->peiId = $resultado->area?->objetivo?->dimension?->idPei;
        
        $this->isEditing = true;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->resultadoToDelete = ResultadoModel::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $resultadoId = $this->resultadoToDelete->id;
            $this->resultadoToDelete->delete();

            // Eliminar de pei_elementos
            DB::table('pei_elementos')
                ->where('elemento_id', $resultadoId)
                ->where('elemento_tipo', 'resultados')
                ->delete();

            session()->flash('message', 'Resultado eliminado correctamente.');
            $this->showDeleteModal = false;
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('Error al eliminar resultado: ' . $e->getMessage());
            $this->errorMessage = 'Error al eliminar el resultado: ' . $e->getMessage();
            $this->showDeleteModal = false;
            $this->showErrorModal = true;
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->resultadoToDelete = null;
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function mount($pei = null, $area = null)
    {
        try {
            $this->peiId = $pei ?? request()->query('pei');
            $this->idArea = $area ?? request()->query('area');

            // Validar PEI
            if ($this->peiId === null) {
                throw new \Exception('PEI no especificado. Use ?pei=1 en la URL.');
            }

            if (!Pei::where('id', $this->peiId)->exists()) {
                throw new \Exception('PEI no encontrado.');
            }

            // Validar Área
            if ($this->idArea === null) {
                throw new \Exception('Área no especificada. Use ?area=1 en la URL.');
            }

            $areaModel = AreaModel::with('objetivo.dimension')->find($this->idArea);

            if (!$areaModel) {
                throw new \Exception('Área no encontrada.');
            }

            // Validar que el área pertenece al PEI especificado
            $areaPeiId = $areaModel->objetivo?->dimension?->idPei;
            if ($areaPeiId !== $this->peiId) {
                throw new \Exception('El área no pertenece al PEI especificado.');
            }

            // Establecer idObjetivo desde el área
            $this->idObjetivo = $areaModel->idObjetivo;
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

            if (!Pei::where('id', $this->peiId)->exists()) {
                throw new \Exception('PEI no encontrado.');
            }

            // Validar que el área esté especificada
            if ($this->idArea === null) {
                throw new \Exception('Área no especificada. Use ?area=1 en la URL.');
            }

            $area = AreaModel::with('objetivo')->find($this->idArea);

            if (!$area) {
                throw new \Exception('Área no encontrada.');
            }

            // Establecer el idObjetivo desde el área
            $this->idObjetivo = $area->idObjetivo;

            $resultados = ResultadoModel::where('idArea', $this->idArea)
                ->when($this->search, function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                          ->orWhere('descripcion', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);

            return view('livewire.consola.pei.resultados.resultados', [
                'resultados' => $resultados,
                'idObjetivo' => $this->idObjetivo,
                'peiId' => $this->peiId,
            ]);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->showErrorModal = true;

            return view('livewire.consola.pei.resultados.resultados', [
                'resultados' => [],
                'idObjetivo' => null,
                'peiId' => null,
            ]);
        }
    }
}