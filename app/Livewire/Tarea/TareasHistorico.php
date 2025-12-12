<?php

    namespace App\Livewire\Tarea;

    use Livewire\Component;
    use Livewire\WithPagination;
    use App\Models\Tareas\TareaHistorico;
    use App\Models\GrupoGastos\ObjetoGasto;
    use App\Models\Requisicion\UnidadMedida;
    use App\Models\ProcesoCompras\ProcesoCompra;
    use App\Models\Cubs\Cub;
    use Illuminate\Support\Facades\Auth;

    class TareasHistorico extends Component
    {
        use WithPagination;

        protected string $layout = 'layouts.app';

        public $nombre;
        public $idobjeto;
        public $idunidad;
        public $idProcesoCompra;
        public $idCubs;
        public $tareaId;
        public $search = '';
        public $perPage = 10;
        public $sortField = 'id';
        public $sortDirection = 'desc';
        public $showModal = false;
        public $showDeleteModal = false;
        public $recursoToDelete;
        public $errorMessage = '';
        public $showErrorModal = false;
        public $isEditing = false;

        protected $rules = [
            'nombre' => 'required|min:3',
            'idobjeto' => 'required|exists:objetogastos,id',
            'idunidad' => 'required|exists:unidadmedidas,id',
            'idProcesoCompra' => 'required|exists:procesos_compras,id',
            'idCubs' => 'nullable|exists:cubs,id',
        ];

        protected $messages = [
            'nombre.required' => 'El nombre del recurso es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'idobjeto.required' => 'El objeto de gasto es obligatorio.',
            'idobjeto.exists' => 'El objeto de gasto seleccionado no existe.',
            'idunidad.required' => 'La unidad de medida es obligatoria.',
            'idunidad.exists' => 'La unidad de medida seleccionada no existe.',
            'idProcesoCompra.required' => 'El proceso de compra es obligatorio.',
            'idProcesoCompra.exists' => 'El proceso de compra seleccionado no existe.',
            'idCubs.exists' => 'El CUBS seleccionado no existe.',
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
            $this->idobjeto = null;
            $this->idunidad = null;
            $this->idProcesoCompra = null;
            $this->idCubs = null;
            $this->tareaId = null;
            $this->resetValidation();
        }

        public function create()
        {
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

        public function closeDeleteModal()
        {
            $this->showDeleteModal = false;
            $this->recursoToDelete = null;
        }

        public function closeErrorModal()
        {
            $this->showErrorModal = false;
            $this->errorMessage = '';
        }

        public function store()
        {
            $this->validate();

            try {
                $user = Auth::user();
                $data = [
                    'nombre' => $this->nombre,
                    'idobjeto' => $this->idobjeto,
                    'idunidad' => $this->idunidad,
                    'idProcesoCompra' => $this->idProcesoCompra,
                    'idCubs' => $this->idCubs,
                    'created_by' => $user->id,
                ];

                $tarea = TareaHistorico::updateOrCreate(
                    ['id' => $this->tareaId],
                    $data
                );

                session()->flash('message',
                    $this->tareaId
                        ? 'Recurso actualizado correctamente.'
                        : 'Recurso creado correctamente.'
                );

                $this->closeModal();
                $this->resetPage();
            } catch (\Exception $e) {
                $this->errorMessage = 'Error al guardar: ' . $e->getMessage();
                $this->showErrorModal = true;
            }
        }

        public function edit($id)
        {
            $tarea = TareaHistorico::findOrFail($id);
            $this->tareaId = $id;
            $this->nombre = $tarea->nombre;
            $this->idobjeto = $tarea->idobjeto;
            $this->idunidad = $tarea->idunidad;
            $this->idProcesoCompra = $tarea->idProcesoCompra;
            $this->idCubs = $tarea->idCubs;
            $this->isEditing = true;
            $this->openModal();
        }

        public function confirmDelete($id)
        {
            $this->recursoToDelete = TareaHistorico::findOrFail($id);
            $this->showDeleteModal = true;
        }

        public function delete()
        {
            try {
                $this->recursoToDelete->delete();
                session()->flash('message', 'Recurso eliminado correctamente.');
                $this->resetPage();
            } catch (\Exception $e) {
                $this->errorMessage = 'Error al eliminar el recurso: ' . $e->getMessage();
                $this->showDeleteModal = false;
                $this->showErrorModal = true;
            }
        }

        public function render()
        {
            $recursos = TareaHistorico::with(['objeto', 'unidadMedida', 'procesoCompra', 'cub'])
                ->where(function ($query) {
                    if ($this->search) {
                        $query->where('nombre', 'like', '%' . $this->search . '%');
                    }
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);

            $objetosGasto = ObjetoGasto::all()->map(function($obj) {
                return ['value' => $obj->id, 'text' => $obj->nombre];
            })->toArray();
            $unidadesMedida = UnidadMedida::all()->map(function($u) {
                return ['value' => $u->id, 'text' => $u->nombre];
            })->toArray();
            $procesosCompra = ProcesoCompra::all()->map(function($p) {
                return ['value' => $p->id, 'text' => $p->nombre_proceso];
            })->toArray();
            $cubs = Cub::all()->map(function($c) {
                return ['value' => $c->id, 'text' => $c->descripcion_esp ?? $c->codigo];
            })->toArray();

            return view('livewire.Tareas.Tarea-historico', [
                'recursos' => $recursos,
                'objetosGasto' => $objetosGasto,
                'unidadesMedida' => $unidadesMedida,
                'procesosCompra' => $procesosCompra,
                'cubs' => $cubs,
            ])->layout('layouts.app');
        }
    }
