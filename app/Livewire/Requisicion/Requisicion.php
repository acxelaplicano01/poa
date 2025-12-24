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
    public $mostrarSelector = false;
    public $departamentosUsuario = [];
    public $departamentoSeleccionado;
    public $detalleRequisiciones = [];
    public $presupuestosSeleccionados = [];
    use WithPagination;

    protected string $layout = 'layouts.app';

    public $correlativo;
    public $descripcion;
    public $observacion;
    public $created_by;
    public $approved_by;
    public $idPoa;
    public $idDepartamento;
    public $idEstado;
    public $fechaSolicitud;
    public $fechaRequerido;
    public $requisicionId;
    public $search = '';
    public $busqueda = '';
    public $estado = 0;
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $showDeleteModal = false;
    public $showSumarioModal = false;
    public $requisicionToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $isEditing = false;
    // Abrir el modal de sumario
    public function abrirSumario()
    {
        $this->showSumarioModal = true;
    }

    // Cerrar el modal de sumario
    public function cerrarSumario()
    {
        $this->showSumarioModal = false;
    }

    protected $rules = [
        'correlativo' => 'required|min:3',
        'descripcion' => 'required',
        'observacion' => 'nullable',
        'approved_by' => 'nullable|exists:users,id',
        'idPoa' => 'required|exists:poas,id',
        'fechaSolicitud' => 'required|date',
        'fechaRequerido' => 'required|date',
    ];

    protected $messages = [
        'correlativo.required' => 'El correlativo es obligatorio.',
        'correlativo.min' => 'El correlativo debe tener al menos 3 caracteres.',
        'descripcion.required' => 'La descripción es obligatoria.',
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

    public function buscar() {}

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

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function updatingEstado()
    {
        $this->resetPage();
    }

    public function resetInputFields()
    {
        $this->correlativo = '';
        $this->descripcion = '';
        $this->observacion = '';
        $this->created_by = Auth::id();
        $this->approved_by = null;
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
                'created_by' => $this->created_by,
                'approved_by' => $this->approved_by,
                'idPoa' => $this->idPoa,
                'idDepartamento' => $this->idDepartamento,
                'idEstado' => $this->idEstado,
                'fechaSolicitud' => $this->fechaSolicitud,
                'fechaRequerido' => $this->fechaRequerido,
            ];
           // dd($data);
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
        $this->created_by = $requisicion->created_by;
        $this->approved_by = $requisicion->approved_by;
        $this->idPoa = $requisicion->idPoa;
        $this->idDepartamento = $requisicion->idDepartamento;
        $this->idEstado = $requisicion->idEstado;
        $this->fechaSolicitud = $requisicion->fechaSolicitud;
        $this->fechaRequerido = $requisicion->fechaRequerido;
        $this->isEditing = true;
        // Cargar detalles de la requisición con relaciones
        $this->detalleRequisiciones = $requisicion->detalleRequisiciones()->with(['recurso', 'presupuesto'])->get();
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
        // Obtener departamentos del usuario (ejemplo: por relación EmpleadoDepto)
        $userId = Auth::id();
        $departamentosUsuario = \App\Models\Departamento\Departamento::whereHas('empleados', function($q) use ($userId) {
            $q->where('empleados.id', $userId);
        })->with('unidadEjecutora')->get();
        $mostrarSelector = $departamentosUsuario->count() > 1;
    {
        $requisiciones = RequisicionModel::with(['departamento', 'estadoRequisicion'])
            ->when($this->busqueda, function($q) {
                $q->where('correlativo', 'like', '%'.$this->busqueda.'%')
                  ->orWhereHas('departamento', fn($q) => $q->where('name', 'like', '%'.$this->busqueda.'%'));
            })
            ->when($this->estado, function($q) {
                if ($this->estado > 0) $q->where('idEstado', $this->estado);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $poas = \App\Models\Poa\Poa::activo()->get();
        // Obtener años únicos de los POA activos
        $poaYears = $poas->pluck('anio')->unique()->sort()->values();
        // Obtener departamentos
        $departamentos = \App\Models\Departamento\Departamento::all();

        // Si hay una requisición seleccionada, cargar detalles
        if ($this->requisicionId) {
            $requisicion = RequisicionModel::find($this->requisicionId);
            $this->detalleRequisiciones = $requisicion ? $requisicion->detalleRequisiciones()->with(['recurso', 'presupuesto'])->get() : collect();
        }

        // Consulta de actividades/tareas aprobadas con presupuestos disponibles
        $actividades_aprobadas = \App\Models\Tareas\Tarea::whereHas('presupuestos', function($q) {
                $q->where('cantidad', '>', 0); // Puedes ajustar el filtro según tu lógica de disponibilidad
            })
            ->where('estado', 'APROBADO')
            ->with(['presupuestos.objetoGasto', 'presupuestos.mes', 'presupuestos.unidadMedida', 'presupuestos.fuente'])
            ->get();

        // Reunir todos los presupuestos en una sola colección plana
        $allPresupuestos = collect();
        foreach ($actividades_aprobadas as $actividad) {
            foreach ($actividad->presupuestos as $presupuesto) {
                $allPresupuestos->push($presupuesto);
            }
        }

        return view('livewire.seguimiento.Requisicion.requisicion', [
            'mostrarSelector' => $mostrarSelector,
            'departamentosUsuario' => $departamentosUsuario,
            'departamentoSeleccionado' => $this->departamentoSeleccionado,
            'requisiciones' => $requisiciones,
            'poas' => $poas,
            'detalleRequisiciones' => $this->detalleRequisiciones,
            'actividades_aprobadas' => $actividades_aprobadas,
            'poaYears' => $poaYears,
            'departamentos' => $departamentos,
            'allPresupuestos' => $allPresupuestos,
        ])->layout($this->layout);
    }

}
}