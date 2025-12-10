<?php

namespace App\Livewire\Requisicion;

use App\Models\Requisicion\Requisicion as RequisicionModel;
use Illuminate\Support\Facades\Validator;
use App\Models\Requisicion\EstadoRequisicion;
use App\Models\Empleado\Empleado;
use App\Models\Empleado\EmpleadoDepto;
use App\Models\Poa\Poa;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Requisicion extends Component
{
    use WithPagination;

    protected string $layout = 'layouts.app';

    public $correlativo;
    public $descripcion;
    public $observacion;
    public $createdBy;
    public $approvedBy;
    public $idPoa;
    public $idDepartamento;
    public $idEstado;
    public $fechaSolicitud;
    public $fechaRequerido;
    public $requisicionId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $showDeleteModal = false;
    public $requisicionToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $isEditing = false;

    protected $rules = [
        'correlativo' => 'required|min:3',
        'descripcion' => 'required',
        'observacion' => 'nullable',
        'createdBy' => 'required|exists:users,id',
        'approvedBy' => 'nullable|exists:users,id',
        'idPoa' => 'required|exists:poas,id',
        'fechaSolicitud' => 'required|date',
        'fechaRequerido' => 'required|date',
    ];

    protected $messages = [
        'correlativo.required' => 'El correlativo es obligatorio.',
        'correlativo.min' => 'El correlativo debe tener al menos 3 caracteres.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'createdBy.required' => 'El usuario creador es obligatorio.',
        'idPoa.required' => 'El POA es obligatorio.',
        'fechaSolicitud.required' => 'La fecha de solicitud es obligatoria.',
        'fechaRequerido.required' => 'La fecha requerida es obligatoria.',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatedCorrelativo($value)
    {
        $this->correlativo = is_array($value) ? '' : $value;
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
        $this->correlativo = '';
        $this->descripcion = '';
        $this->observacion = '';
        $this->createdBy = Auth::id();
        $this->approvedBy = null;
        $this->idPoa = null;
        $this->idDepartamento = Auth::user()->idDepartamento ?? null;
        $this->idEstado = $this->getEstadoPresentadoId();
        $this->fechaSolicitud = now();
        $this->fechaRequerido = null;
        $this->requisicionId = null;
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
        // Asignar automáticamente el departamento y estado
        $empleadoDepto = \DB::table('empleado_deptos')
            ->where('idEmpleado', Auth::id())
            ->whereNull('deleted_at')
            ->first();
        $this->idDepartamento = $empleadoDepto ? $empleadoDepto->idDepto : null;
        $this->idEstado = $this->getEstadoPresentadoId();
        try {
            $data = [
                'correlativo' => $this->correlativo,
                'descripcion' => $this->descripcion,
                'observacion' => $this->observacion,
                'createdBy' => $this->createdBy,
                'approvedBy' => $this->approvedBy,
                'idPoa' => $this->idPoa,
                'idDepartamento' => $this->idDepartamento,
                'idEstado' => $this->idEstado,
                'fechaSolicitud' => $this->fechaSolicitud,
                'fechaRequerido' => $this->fechaRequerido,
            ];
            dd($data);
            $requisicion = RequisicionModel::updateOrCreate(
                ['id' => $this->requisicionId],
                $data
            );
            session()->flash('message',
                $this->requisicionId
                    ? 'Requisición actualizada correctamente.'
                    : 'Requisición creada correctamente.'
            );
            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->errorMessage = 'Error al guardar: ' . $e->getMessage();
            $this->showErrorModal = true;
        }
        }
    
        // ...existing code...
        protected function getEstadoPresentadoId()
        {
            $estado = \DB::table('estado_requisicion')->where('estado', 'Presentado')->first();
            return $estado ? $estado->id : null;
        }

    public function edit($id)
    {
        $requisicion = RequisicionModel::findOrFail($id);
        $this->requisicionId = $id;
        $this->correlativo = $requisicion->correlativo;
        $this->descripcion = $requisicion->descripcion;
        $this->observacion = $requisicion->observacion;
        $this->createdBy = $requisicion->createdBy;
        $this->approvedBy = $requisicion->approvedBy;
        $this->idPoa = $requisicion->idPoa;
        $this->idDepartamento = $requisicion->idDepartamento;
        $this->idEstado = $requisicion->idEstado;
        $this->fechaSolicitud = $requisicion->fechaSolicitud;
        $this->fechaRequerido = $requisicion->fechaRequerido;
        $this->isEditing = true;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->requisicionToDelete = RequisicionModel::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $requisicionId = $this->requisicionToDelete->id;
            $this->requisicionToDelete->delete();
            session()->flash('message', 'Requisición eliminada correctamente.');
            $this->showDeleteModal = false;
            $this->resetPage();
        } catch (\Exception $e) {
            $this->errorMessage = 'Error al eliminar la requisición: ' . $e->getMessage();
            $this->showDeleteModal = false;
            $this->showErrorModal = true;
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->requisicionToDelete = null;
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function render()
    {
        $requisiciones = RequisicionModel::when($this->search, function ($query) {
                $query->where('correlativo', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $poas = \App\Models\Poa\Poa::activo()->get();

        return view('livewire.seguimiento.Requisicion.requisicion', [
            'requisiciones' => $requisiciones,
            'poas' => $poas,
        ])->layout($this->layout);
    }

}