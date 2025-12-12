<?php

namespace App\Livewire\ProcesCompra;

use Livewire\Component;
    
    use App\Models\ProcesoCompras\ProcesoCompra as ProcesCompraModel;
     use App\Models\Empleados\Empleado;
    use Livewire\WithPagination;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    
    class ProcesCompras extends Component
    {
        use WithPagination;
        
        protected string $layout = 'layouts.app';
        
        public $nombre_proceso;
        public $procesoId;
        public $search = '';
        public $perPage = 10;
        public $sortField = 'id';
        public $sortDirection = 'desc';
        public $showModal = false;
        public $showDeleteModal = false;
        public $procesoToDelete;
        public $errorMessage = '';
        public $showErrorModal = false;
        public $isEditing = false;
        
        protected $rules = [
            'nombre_proceso' => 'required|min:3',
        ];
        
        protected $messages = [
            'nombre_proceso.required' => 'El nombre del proceso es obligatorio.',
            'nombre_proceso.min' => 'El nombre debe tener al menos 3 caracteres.',
        ];
        
        protected $queryString = [
            'search' => ['except' => ''],
            'sortField' => ['except' => 'id'],
            'sortDirection' => ['except' => 'desc'],
        ];
        
        public function updatedNombreProceso($value)
        {
            $this->nombre_proceso = is_array($value) ? '' : $value;
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
            $this->nombre_proceso = '';
            $this->procesoId = null;
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
        
        public function store()
        {
            $this->validate();
            
            try {
                $user = Auth::user();
                $empleado = Empleado::find($user->idEmpleado);
                if (!$empleado) {
                    $this->errorMessage = 'No se encontró el empleado asociado al usuario.';
                    $this->showErrorModal = true;
                    return;
                }
                $idUE = $empleado?->idUnidadEjecutora;
                $data = [
                    'nombre_proceso' => $this->nombre_proceso,
                    'idUE' => $idUE,
                    'created_by' => $user->id,
                ];
                
                $proceso = ProcesCompraModel::updateOrCreate(
                    ['id' => $this->procesoId],
                    $data
                );
                
                session()->flash('message',
                    $this->procesoId
                        ? 'Proceso actualizado correctamente.'
                        : 'Proceso creado correctamente.'
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
            $proceso = ProcesCompraModel::findOrFail($id);
            $this->procesoId = $id;
            $this->nombre_proceso = $proceso->nombre_proceso;
            $this->isEditing = true;
            $this->openModal();
        }
        
        public function confirmDelete($id)
        {
            $this->procesoToDelete = ProcesCompraModel::findOrFail($id);
            $this->showDeleteModal = true;
        }
        
        public function delete()
        {
            try {
                $this->procesoToDelete->delete();
                session()->flash('message', 'Proceso eliminado correctamente.');
                $this->showDeleteModal = false;
                $this->resetPage();
            } catch (\Exception $e) {
                $this->errorMessage = 'Error al eliminar el proceso: ' . $e->getMessage();
                $this->showDeleteModal = false;
                $this->showErrorModal = true;
            }
        }
        
        public function closeDeleteModal()
        {
            $this->showDeleteModal = false;
            $this->procesoToDelete = null;
        }
        
        public function closeErrorModal()
        {
            $this->showErrorModal = false;
            $this->errorMessage = '';
        }
        
        public function render()
        {
            $procesos = ProcesCompraModel::where(function ($query) {
                    if ($this->search) {
                        $query->where('nombre_proceso', 'like', '%' . $this->search . '%');
                    }
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
            
            return view('livewire.proces-compra.proces-compras', [
                'procesos' => $procesos,
            ])->layout('layouts.app');
        }
    }
