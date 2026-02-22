<?php

namespace App\Livewire\Consola\Pei\Areas;

use App\Models\Areas\Area as AreaModel;
use App\Models\Objetivos\Objetivo as ObjetivoModel;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Pei;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Area extends Component
{
    use WithPagination;

    protected string $layout = 'layouts.app';

    #[Url(as: 'objetivo')]
    public ?int $idObjetivo = null;

    #[Url(as: 'pei')]
    public ?int $peiId = 1;

    public $idDimension;
    public $nombre;
    public $areaId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $showDeleteModal = false;
    public $areaToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $isEditing = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'idObjetivo' => 'required|exists:objetivos,id',
        'peiId' => 'required|exists:peis,id',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'idObjetivo.required' => 'El objetivo es obligatorio.',
        'idObjetivo.exists' => 'El objetivo seleccionado no existe.',
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

    public function updatedIdObjetivo($value)
    {
        $this->idObjetivo = is_array($value) ? null : $value;
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
        $this->areaId = null;
        $this->resetValidation();
    }

    public function create()
    {
        \Log::debug('Método create ejecutado para Área');
        $this->resetInputFields();
        $this->isEditing = false;
        $this->openModal();
    }

    public function hideError()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
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
        \Log::debug('Datos recibidos en store (Área):', [
            'nombre' => $this->nombre,
            'idObjetivo' => $this->idObjetivo,
            'peiId' => $this->peiId,
        ]);

        // Validar que el objetivo existe y obtener su información
        $objetivo = ObjetivoModel::with('dimension')->where('id', $this->idObjetivo)->first();

        if (!$objetivo) {
            $this->errorMessage = 'Error: El objetivo seleccionado no existe.';
            $this->showErrorModal = true;
            return;
        }

        $this->validate();

        try {
            // Obtener idPei del objetivo a través de su dimensión
            $idPei = $objetivo->dimension?->idPei;
            
            $data = [
                'nombre' => $this->nombre,
                'idObjetivo' => $this->idObjetivo,
                'idPei' => $idPei, // Agregar idPei si la tabla lo requiere
            ];

            $area = AreaModel::updateOrCreate(
                ['id' => $this->areaId],
                $data
            );

            // Registrar en pei_elementos SOLO si es nuevo
            if (!$this->areaId) {
                $peiElementoData = [
                    'idPei' => $objetivo->dimension?->idPei,
                    'elemento_id' => $area->id,
                    'elemento_tipo' => 'areas',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                \Log::debug('Insertando en pei_elementos:', $peiElementoData);
                DB::table('pei_elementos')->insert($peiElementoData);
            }

            session()->flash('message', 
                $this->areaId 
                    ? 'Área actualizada correctamente.' 
                    : 'Área creada correctamente.'
            );

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('Error en store() (Área): ' . $e->getMessage(), [
                'idObjetivo' => $this->idObjetivo,
            ]);
            $this->errorMessage = 'Error al guardar: ' . str($e->getMessage())->before('(');
            $this->showErrorModal = true;
        }
    }

    public function edit($id)
    {
        $area = AreaModel::with('objetivo.dimension')->findOrFail($id);
        $this->areaId = $id;
        $this->nombre = $area->nombre;
        $this->idObjetivo = $area->idObjetivo; 
        
        // Obtener peiId desde el objetivo → dimensión → PEI
        $this->peiId = $area->objetivo?->dimension?->idPei;
        
        $this->isEditing = true;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->areaToDelete = AreaModel::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $areaId = $this->areaToDelete->id;

            // Verificar si tiene resultados hijos
            $tieneHijos = DB::table('pei_elementos')
                ->where('elemento_tipo', 'resultados')
                ->whereExists(function($query) use ($areaId) {
                    $query->select(DB::raw(1))
                        ->from('resultados')
                        ->whereColumn('resultados.id', 'pei_elementos.elemento_id')
                        ->where('resultados.idArea', $areaId);
                })
                ->exists();

            if ($tieneHijos) {
                session()->flash('error', 'No se puede eliminar esta área porque tiene resultados asociados. Elimine primero los resultados.');
                $this->showDeleteModal = false;
                return;
            }

            $this->areaToDelete->delete();
            DB::table('pei_elementos')
                ->where('elemento_id', $areaId)
                ->where('elemento_tipo', 'areas')
                ->delete();

            session()->flash('message', 'Área eliminada correctamente.');
            $this->showDeleteModal = false;
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('Error al eliminar área: ' . $e->getMessage());
            $this->errorMessage = 'Error al eliminar el área: ' . $e->getMessage();
            $this->showDeleteModal = false;
            $this->showErrorModal = true;
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->areaToDelete = null;
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function mount($pei = null, $objetivo = null)
    {
        try {
            $this->peiId = $pei ?? request()->query('pei');
            $this->idObjetivo = $objetivo ?? request()->query('objetivo');

            // Validar PEI
            if ($this->peiId === null) {
                throw new \Exception('PEI no especificado. Use ?pei=1 en la URL.');
            }

            if (!Pei::where('id', $this->peiId)->exists()) {
                throw new \Exception('PEI no encontrado.');
            }

            // Validar Objetivo
            if ($this->idObjetivo === null) {
                throw new \Exception('Objetivo no especificado. Use ?objetivo=1 en la URL.');
            }

            $objetivo = ObjetivoModel::with('dimension')->find($this->idObjetivo);

            if (!$objetivo) {
                throw new \Exception('Objetivo no encontrado.');
            }

            // Establecer idDimension desde la dimensión del objetivo
            $this->idDimension = $objetivo->dimension?->id;

            if ($this->idDimension === null) {
                throw new \Exception('Dimensión no especificada para el objetivo.');
            }
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->showErrorModal = true;
        }
    }

    public function render()
    {
        try {
            // Validar que el objetivo esté especificado
            if ($this->idObjetivo === null) {
                throw new \Exception('Objetivo no especificado. Use ?objetivo=1 en la URL.');
            }

            // Verificar que el objetivo exista en la tabla objetivos
            if (!ObjetivoModel::where('id', $this->idObjetivo)->exists()) {
                throw new \Exception('El objetivo especificado no existe.');
            }

            // Obtener las áreas asociadas al objetivo
            $areas = AreaModel::where('idObjetivo', $this->idObjetivo)
                ->when($this->search, function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->showErrorModal = true;

            // Retornar un paginador vacío en caso de error
            $areas = AreaModel::whereRaw('1 = 0')->paginate($this->perPage);
        }

        return view('livewire.consola.pei.Areas.areas', [
            'areas' => $areas,
        ]);
    }
}