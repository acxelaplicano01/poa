<?php

namespace App\Livewire\Requisicion;

use App\Models\Requisicion\Requisicion as RequisicionModel;
use Illuminate\Support\Facades\Validator;
use App\Models\Requisicion\EstadoRequisicion;
use App\Models\Empleados\Empleado;
use App\Models\Empleados\EmpleadoDepto;
use App\Models\Tareas\TareaHistorico;
use App\Models\Presupuestos\Presupuesto;
use App\Models\Departamento\Departamento;
use App\Models\Requisicion\DetalleRequisicion;
use App\Models\Tareas\Tarea;
use App\Models\ProcesoCompras\ProcesoCompra;
use App\Models\Poa\Poa;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Requisicion extends Component
{
  
    // Búsqueda por nombre de actividad o tarea
    public $buscarActividad = '';

    public function crearRequisicionDesdeSumario()
    {
        
        $this->validate([
            'descripcion' => 'required',
            'fechaRequerido' => 'required|date',
        ]);

        $user = Auth::user();
        if (!$this->idPoa && !empty($this->recursosSeleccionados)) {
            
            $primerRecurso = $this->recursosSeleccionados[0];
            $presupuesto = Presupuesto::find($primerRecurso['id']);
            if ($presupuesto && $presupuesto->idtarea) {
                $tarea = Tarea::find($presupuesto->idtarea);
                if ($tarea && $tarea->idPoa) {
                    $this->idPoa = $tarea->idPoa;
                }
            }
        }
        
        $poa = $this->idPoa ? Poa::find($this->idPoa) : null;
        // Asignar departamento y estado primero
        $empleadoDepto = \DB::table('empleado_deptos')
            ->where('idEmpleado', $user->id)
            ->whereNull('deleted_at')
            ->first();
        $this->idDepartamento = $empleadoDepto ? $empleadoDepto->idDepto : ($user->idDepartamento ?? null);
        $this->idEstado = $this->getEstadoPresentadoId();

        // obtener el departamento real de la requisición
        $departamento = $this->idDepartamento ? Departamento::find($this->idDepartamento) : null;
        $ultimo = Requisicion::orderByDesc('id')->first();
        $numero = $ultimo ? $ultimo->id + 1 : 1;
        $tipoDepto = $departamento->tipo ?? '';
        $nombreDepto = $departamento->name ?? '';
        $anio = $poa ? $poa->anio : date('Y');
        $correlativo = \App\Helpers\CorrelativoHelper::generarCorrelativo($tipoDepto, $nombreDepto, $anio, $numero);

        // Asignar departamento y estado
        $empleadoDepto = \DB::table('empleado_deptos')
            ->where('idEmpleado', $user->id)
            ->whereNull('deleted_at')
            ->first();
        $this->idDepartamento = $empleadoDepto ? $empleadoDepto->idDepto : ($user->idDepartamento ?? null);
        $this->idEstado = $this->getEstadoPresentadoId();

        try {
            $data = [
                'correlativo' => $correlativo,
                'descripcion' => $this->descripcion,
                'observacion' => $this->observacion,
                'created_by' => $user->id,
                'approved_by' => null,
                'idPoa' => $this->idPoa,
                'idDepartamento' => $this->idDepartamento,
                'idEstado' => $this->idEstado,
                'fechaSolicitud' => now(),
                'fechaRequerido' => $this->fechaRequerido,
            ];
            //dd($data);
            $requisicion = Requisicion::create($data);
            
            foreach ($this->recursosSeleccionados as $recurso) {
                $presupuesto = Presupuesto::find($recurso['id']);
                if ($presupuesto) {
                    DetalleRequisicion::create([
                        'idRequisicion' => $requisicion->id,
                        'idPoa' => $this->idPoa,
                        'idPresupuesto' => $presupuesto->id,
                        'idRecurso' => $presupuesto->idHistorico,
                        'cantidad' => $recurso['cantidad_seleccionada'],
                        'idUnidadMedida' => $presupuesto->idunidad,
                        'entregado' => false,
                        'created_by' => $user->id,
                    ]);
                }
            }
            $this->showSumarioModal = false;
            $this->resetInputFields();
            session()->flash('message', 'Requisición creada correctamente.');
        } catch (\Exception $e) {
            $this->errorMessage = 'Error al guardar: ' . $e->getMessage();
            $this->showErrorModal = true;
        }
    }
    public $mostrarSelector = false;
    public $departamentosUsuario = [];
    public $departamentoSeleccionado;
    public $detalleRequisiciones = [];
    public $presupuestosSeleccionados = [];
    public $recursosSeleccionados = [];

    public function agregarRecursoAlSumario($recurso)
    {
        if (!collect($this->recursosSeleccionados)->contains('id', $recurso['id'])) {
            $this->recursosSeleccionados[] = $recurso;
        }
    }

    // Quitar recurso del sumario
    public function quitarRecursoDelSumario($recursoId)
    {
        $this->recursosSeleccionados = collect($this->recursosSeleccionados)
            ->reject(fn($item) => $item['id'] == $recursoId)
            ->values()
            ->toArray();
    }
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
        $this->recursosSeleccionados = [];
        // Obtener actividades y presupuestos aprobados 
        $actividades_aprobadas = Tarea::whereHas('presupuestos', function($q) {
            $q->where('cantidad', '>', 0);
        })
        ->where('estado', 'APROBADO')
        ->with(['presupuestos.objetoGasto', 'presupuestos.mes', 'presupuestos.unidadMedida', 'presupuestos.fuente', 'actividad'])
        ->get();

        foreach ($this->presupuestosSeleccionados as $presupuestoId => $cantidad) {
            if ($cantidad > 0) {
                foreach ($actividades_aprobadas as $actividad) {
                    $presupuesto = $actividad->presupuestos->where('id', $presupuestoId)->first();
                    if ($presupuesto) {
                        $this->recursosSeleccionados[] = [
                            'id' => $presupuesto->id,
                            'nombre' => $presupuesto->recurso,
                            'actividad' => ($actividad->actividad->nombre ?? '-') . ' / ' . ($actividad->nombre ?? '-'),
                            'proceso_compra' => $presupuesto->tareaHistorico && $presupuesto->tareaHistorico->procesoCompra ? $presupuesto->tareaHistorico->procesoCompra->nombre_proceso : '-',
                            'cantidad_seleccionada' => $cantidad,
                            'unidad_medida' => $presupuesto->unidadMedida->nombre ?? '-',
                            'precio_unitario' => $presupuesto->costounitario ?? 0,
                            'total' => $cantidad * ($presupuesto->costounitario ?? 0),
                        ];
                        break;
                    }
                }
            }
        }
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
        $userId = Auth::id();
        $departamentosUsuario = Departamento::whereHas('empleados', function($q) use ($userId) {
            $q->where('empleados.id', $userId);
        })->with('unidadEjecutora')->get();
        $mostrarSelector = $departamentosUsuario->count() > 1;

        $requisiciones = RequisicionModel::with(['departamento', 'estado'])
            ->when($this->busqueda, function($q) {
                $q->where('correlativo', 'like', '%'.$this->busqueda.'%')
                  ->orWhereHas('departamento', fn($q) => $q->where('name', 'like', '%'.$this->busqueda.'%'));
            })
            ->when($this->estado, function($q) {
                if ($this->estado > 0) $q->where('idEstado', $this->estado);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $poas = Poa::activo()->get();
        // Obtener años únicos de los POA activos
        $poaYears = $poas->pluck('anio')->unique()->sort()->values();
        // Obtener departamentos
        $departamentos = Departamento::all();

        if ($this->requisicionId) {
            $requisicion = RequisicionModel::find($this->requisicionId);
            $this->detalleRequisiciones = $requisicion ? $requisicion->detalleRequisiciones()->with(['recurso', 'presupuesto'])->get() : collect();
        }
        $actividades_aprobadas = Tarea::whereHas('presupuestos', function($q) {
                $q->where('cantidad', '>', 0); 
            })
            ->where('estado', 'APROBADO')
            ->when($this->buscarActividad, function($q) {
                $q->where(function($subq) {
                    $subq->where('nombre', 'like', '%'.$this->buscarActividad.'%');
                    // Si hay relación actividad, buscar también por ese nombre
                    $subq->orWhereHas('actividad', function($q2) {
                        $q2->where('nombre', 'like', '%'.$this->buscarActividad.'%');
                    });
                });
            })
            ->with(['presupuestos.objetoGasto', 'presupuestos.mes', 'presupuestos.unidadMedida', 'presupuestos.fuente', 'actividad'])
            ->get();

        $allPresupuestos = collect();
        foreach ($actividades_aprobadas as $actividad) {
            foreach ($actividad->presupuestos as $presupuesto) {
                $allPresupuestos->push($presupuesto);
            }
        }

        $valoresPlanificados = [];
        foreach ($allPresupuestos as $presupuesto) {
            $cantidadPlanificada = DetalleRequisicion::where('idPresupuesto', $presupuesto->id)
                ->whereHas('requisicion', function($q) {
                    $q->whereHas('estado', function($q2) {
                        $q2->whereIn('estado', ['Presentado', 'Recibido', 'En Proceso de Compra']);
                    });
                })
                ->sum('cantidad');
            $cantidadDisponible = ($presupuesto->cantidad ?? 0) - $cantidadPlanificada;
            $costoUnitario = $presupuesto->costounitario ?? 0;
            $costoDisponible = $cantidadDisponible * $costoUnitario;
            $costoPlanificado = $cantidadPlanificada * $costoUnitario;
            $valoresPlanificados[$presupuesto->id] = [
                'cantidad_disponible' => $cantidadDisponible,
                'cantidad_planificada' => $cantidadPlanificada,
                'costo_disponible' => $costoDisponible,
                'costo_planificado' => $costoPlanificado,
            ];
        }
        return view('livewire.seguimiento.Requisicion.create-requisiciones', [
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
            'recursosSeleccionados' => $this->recursosSeleccionados,
            'valoresPlanificados' => $valoresPlanificados,
        ])->layout($this->layout);
    }

    public $showOrdenCombustibleModal = false;
    public $ordenCombustibleRecursoId;
    public $ordenCombustibleRecursoNombre;
    public $ordenCombustibleData = [
        'modelo_vehiculo' => '',
        'placa' => '',
        'lugar_salida' => '',
        'lugar_destino' => '',
        'recorrido_km' => 0,
        'fecha_actividad' => '',
        'responsable' => '',
        'actividades_realizar' => '',
    ];
    public $empleados = [];

    public function mount()
    {
        $this->empleados = Empleado::all();
    }

    public function abrirOrdenCombustibleModal($recursoId)
    {
        $recurso = collect($this->recursosSeleccionados)->firstWhere('id', $recursoId);
        $this->ordenCombustibleRecursoId = $recursoId;
        $this->ordenCombustibleRecursoNombre = $recurso['nombre'] ?? '';
        $this->ordenCombustibleData = [
            'modelo_vehiculo' => '',
            'placa' => '',
            'lugar_salida' => '',
            'lugar_destino' => '',
            'recorrido_km' => 0,
            'fecha_actividad' => '',
            'responsable' => '',
            'actividades_realizar' => '',
        ];
        $this->showOrdenCombustibleModal = true;
    }

    public function cerrarOrdenCombustibleModal()
    {
        $this->showOrdenCombustibleModal = false;
        $this->ordenCombustibleRecursoId = null;
        $this->ordenCombustibleRecursoNombre = '';
        $this->ordenCombustibleData = [
            'modelo_vehiculo' => '',
            'placa' => '',
            'lugar_salida' => '',
            'lugar_destino' => '',
            'recorrido_km' => 0,
            'fecha_actividad' => '',
            'responsable' => '',
            'actividades_realizar' => '',
        ];
    }

    public function guardarOrdenCombustible()
    {
        $this->validate([
            'ordenCombustibleData.modelo_vehiculo' => 'required',
            'ordenCombustibleData.placa' => 'required',
            'ordenCombustibleData.lugar_salida' => 'required',
            'ordenCombustibleData.lugar_destino' => 'required',
            'ordenCombustibleData.recorrido_km' => 'required|numeric',
            'ordenCombustibleData.fecha_actividad' => 'required|date',
            'ordenCombustibleData.responsable' => 'required|exists:empleados,id',
            'ordenCombustibleData.actividades_realizar' => 'required',
        ], [
            'ordenCombustibleData.*.required' => 'Este campo es obligatorio.',
        ]);

        // Obtener idPoa del recurso seleccionado si no está definido
        if (empty($this->idPoa) && $this->ordenCombustibleRecursoId) {
            $presupuesto = Presupuesto::find($this->ordenCombustibleRecursoId);
            if ($presupuesto && $presupuesto->idtarea) {
                $tarea = Tarea::find($presupuesto->idtarea);
                if ($tarea && $tarea->idPoa) {
                    $this->idPoa = $tarea->idPoa;
                }
            }
        }

        $idDetalleRequisicion = null;

        $requisicion = Requisicion::where('idPoa', $this->idPoa)
            ->where('created_by', \Auth::id())
            ->orderByDesc('id')
            ->first();

        if ($requisicion) {
            $detalle = DetalleRequisicion::where('idRequisicion', $requisicion->id)
                ->where('idPresupuesto', $this->ordenCombustibleRecursoId)
                ->orderByDesc('id')
                ->first();
            if ($detalle) {
                $idDetalleRequisicion = $detalle->id;
            }
        }
        if (!$idDetalleRequisicion) {
            $detalle = DetalleRequisicion::where('idPoa', $this->idPoa)
                ->where('idPresupuesto', $this->ordenCombustibleRecursoId)
                ->orderByDesc('id')
                ->first();
            if ($detalle) {
                $idDetalleRequisicion = $detalle->id;
            }
        }
        if (!$idDetalleRequisicion) {
            $presupuesto = Presupuesto::find($this->ordenCombustibleRecursoId);
            $idRecurso = $presupuesto ? ($presupuesto->idHistorico ?? $presupuesto->idrecurso ?? null) : null;
            $idUnidadMedida = $presupuesto ? ($presupuesto->idunidad ?? $presupuesto->idUnidadMedida ?? null) : null;

            if ($requisicion && $idRecurso && $idUnidadMedida) {
                $detalleNuevo = \App\Models\Requisicion\DetalleRequisicion::create([
                    'idRequisicion' => $requisicion->id,
                    'idPoa' => $this->idPoa,
                    'idPresupuesto' => $this->ordenCombustibleRecursoId,
                    'idRecurso' => $idRecurso,
                    'cantidad' => 1,
                    'idUnidadMedida' => $idUnidadMedida,
                    'entregado' => false,
                    'created_by' => \Auth::id(),
                ]);
                $idDetalleRequisicion = $detalleNuevo->id;

        }

        $this->ordenCombustibleData['idDetalleRequisicion'] = $idDetalleRequisicion;

        $ultimo = \DB::table('orden_combustible')->orderByDesc('id')->first();
        $numero = $ultimo ? ($ultimo->id + 1) : 1;
        $anio = now()->format('Y');
        $correlativo = $numero . '-' . $anio;

        if (empty($this->ordenCombustibleData['idDetalleRequisicion'])) {
            throw new \Exception('idDetalleRequisicion null');
        }

        \DB::table('orden_combustible')->insert([
            'correlativo' => $correlativo,
            //'monto' => 0,
            //'monto_en_letras' => '',
            'monto' => $this->ordenCombustibleData['monto'],
            'monto_en_letras' => $this->ordenCombustibleData['monto_en_letras'],
            'modelo_vehiculo' => $this->ordenCombustibleData['modelo_vehiculo'],
            'lugar_salida' => $this->ordenCombustibleData['lugar_salida'],
            'lugar_destino' => $this->ordenCombustibleData['lugar_destino'],
            'placa' => $this->ordenCombustibleData['placa'],
            'recorrido_km' => $this->ordenCombustibleData['recorrido_km'],
            'fecha_actividad' => $this->ordenCombustibleData['fecha_actividad'],
            'actividades_realizar' => $this->ordenCombustibleData['actividades_realizar'],
            'idPoa' => $this->idPoa,
            'idDetalleRequisicion' => $this->ordenCombustibleData['idDetalleRequisicion'],
            'idRecurso' => $this->ordenCombustibleRecursoId,
            'responsable' => $this->ordenCombustibleData['responsable'],
            'created_by' => \Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Marcar el recurso como que ya tiene orden de combustible
        foreach ($this->recursosSeleccionados as &$recurso) {
            if ($recurso['id'] == $this->ordenCombustibleRecursoId) {
                $recurso['orden_combustible_creada'] = true;
            }
        }
        unset($recurso);

        $this->cerrarOrdenCombustibleModal();
        $this->showSumarioModal = true;
        session()->flash('message', 'Orden de combustible creada correctamente.');
    }
}
}