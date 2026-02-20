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
    use WithPagination;
    protected string $layout = 'layouts.app';
    protected $paginationTheme = 'tailwind';
    public $buscarActividad = '';
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
    public $successMessage = '';
    public $diasRestantes = null;

    public $mostrarSelector = false;
    public $departamentosUsuario = [];
    public $departamentoSeleccionado;
    public $detalleRequisiciones = [];
    public $presupuestosSeleccionados = [];
    public $recursosSeleccionados = [];

    public $poaYear = null;
	public $poaYears = [];
    public $detalleRecursos = [];
    public $showDetalleRecursosModal = false;

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

    public $estadoRequisicion;
    public $montoTotalRequisicion = 0;
    public $montoEjecutadoRequisicion = 0;

    public $puedeCrearRequisicion = false;
    public $mensajePlazoRequisicion = '';

    protected $rules = [
        'correlativo' => 'required|min:3',
        'descripcion' => 'required',
        'observacion' => 'required',
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
        'observacion.required' => 'La observación es obligatoria.',
    ];


    public function crearRequisicionDesdeSumario()
    {

        if (!$this->puedeCrearRequisicion) {
            session()->flash('error', $this->mensajePlazoRequisicion);
            return;
        }

        $this->validate([
            'descripcion' => 'required',
            'fechaRequerido' => 'required|date',
            'departamentoSeleccionado' => 'required|exists:departamentos,id', // Validar que el departamento seleccionado sea válido
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

        // Usar el departamento seleccionado
        $this->idDepartamento = $this->departamentoSeleccionado;

        $departamento = $this->idDepartamento ? Departamento::find($this->idDepartamento) : null;
        $ultimo = \App\Models\Requisicion\Requisicion::orderBy('id', 'desc')->first();
        $numero = $ultimo ? $ultimo->id + 1 : 1;
        $tipoDepto = $departamento->tipo ?? '';
        $nombreDepto = $departamento->name ?? '';
        $anio = $poa ? $poa->anio : date('Y');
        $correlativo = \App\Helpers\CorrelativoHelper::generarCorrelativo($tipoDepto, $nombreDepto, $anio, $numero);

        $this->idEstado = $this->getEstadoPresentadoId();

        try {
            $data = [
                'correlativo' => $correlativo,
                'descripcion' => $this->descripcion,
                'observacion' => $this->observacion,
                'created_by' => $user->id,
                'approved_by' => null,
                'idPoa' => $this->idPoa,
                'idDepartamento' => $this->idDepartamento, // Asociar el departamento seleccionado
                'idEstado' => $this->idEstado,
                'fechaSolicitud' => now(),
                'fechaRequerido' => $this->fechaRequerido,
            ];

            $requisicion = \App\Models\Requisicion\Requisicion::create($data);

            if (!$requisicion) {
                throw new \Exception('No se pudo crear la requisición.');
            }

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

        session()->forget('recursosSeleccionados');
        $this->recursosSeleccionados = [];
        $this->presupuestosSeleccionados = [];
    }

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

        if (isset($this->presupuestosSeleccionados[$recursoId])) {
            unset($this->presupuestosSeleccionados[$recursoId]);
        }

        // Sincronizar con sesión
        session(['recursosSeleccionados' => $this->recursosSeleccionados]);
    }


    // Detectar cambios en las cantidades solicitadas
    public function updated($propertyName)
    {
        // Si se actualiza una cantidad en presupuestosSeleccionados, actualiza el sumario
        if (str_starts_with($propertyName, 'presupuestosSeleccionados')) {
            $this->actualizarSumario();
        }
    }

    // Actualizar el sumario de recursos seleccionados
   public function actualizarSumario()
{
    $this->recursosSeleccionados = [];

    foreach ($this->presupuestosSeleccionados as $presupuestoId => $cantidad) {
        if ($cantidad !== null && $cantidad !== '' && (int)$cantidad > 0) {
            $presupuesto = Presupuesto::with(['unidadMedida'])->find($presupuestoId);
            if ($presupuesto) {
                $tarea = $presupuesto->idtarea ? Tarea::with('actividad')->find($presupuesto->idtarea) : null;

                if ($this->departamentoSeleccionado && $tarea) {
                    if ($tarea->idDeptartamento != $this->departamentoSeleccionado) {
                        continue; // Saltar recursos de otro departamento
                    }
                }

                if ($this->poaYear && $tarea && $tarea->poa) {
                    if ($tarea->poa->anio != $this->poaYear) {
                        continue; // Saltar recursos de otros POA
                    }
                }

                $nombreRecurso = strtoupper($presupuesto->recurso ?? '');
                $esCombustible = str_contains($nombreRecurso, 'GASOLINA') || str_contains($nombreRecurso, 'DIESEL');

                $this->recursosSeleccionados[] = [
                    'id'                    => $presupuesto->id,
                    'nombre'                => $presupuesto->recurso,
                    'actividad'             => $tarea
                        ? (($tarea->actividad->nombre ?? '-') . ' / ' . ($tarea->nombre ?? '-'))
                        : '-',
                    'proceso_compra'        => $presupuesto->tareaHistorico && $presupuesto->tareaHistorico->procesoCompra
                        ? $presupuesto->tareaHistorico->procesoCompra->nombre_proceso
                        : '-',
                    'cantidad_seleccionada' => (int)$cantidad,
                    'unidad_medida'         => $presupuesto->unidadMedida->nombre ?? '-',
                    'precio_unitario'       => $presupuesto->costounitario ?? 0,
                    'total'                 => (int)$cantidad * ($presupuesto->costounitario ?? 0),
                    'es_combustible'        => $esCombustible,
                    'idPoa'                 => $tarea?->idPoa, // ✅ guardar el POA
                ];
            }
        }
    }
}
    // Abrir el modal de sumario
    public function abrirSumario()
    {

        if (!$this->puedeCrearRequisicion) {
            session()->flash('error', $this->mensajePlazoRequisicion);
            return;
        }
        $this->recursosSeleccionados = [];
        // Obtener actividades y presupuestos aprobados 
        $actividades_aprobadas = Tarea::whereHas('presupuestos', function($q) {
            $q->where('cantidad', '>', 0);
        })
        ->where('estado', 'APROBADO')
        ->when($this->buscarActividad, function($q) {
            $q->where(function($subq) {
                $subq->where('nombre', 'like', '%'.$this->buscarActividad.'%');
                $subq->orWhereHas('actividad', function($q2) {
                    $q2->where('nombre', 'like', '%'.$this->buscarActividad.'%');
                });
            });
        })
        ->when($this->departamentoSeleccionado, function($q) {
            // Filtrar por el departamento seleccionado a través de tareas relacionadas con presupuestos
            $q->where('idDeptartamento', $this->departamentoSeleccionado);
        })
        ->with(['presupuestos' => function($query) {
            $query->where('cantidad', '>', 0); // Filtrar presupuestos con cantidad mayor a 0
        }, 'presupuestos.objetoGasto', 'presupuestos.mes', 'presupuestos.unidadMedida', 'presupuestos.fuente', 'actividad'])
        ->paginate($this->perPage);

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

    /*protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
    ];*/

    public function updatedCorrelativo($value)
    {
        $this->correlativo = is_array($value) ? '' : $value;
    }

    public function buscar() {}

    public function sortBy($field)
    {
        // Validate the sort field against valid columns in the requisicion table
        $validColumns = ['id', 'correlativo', 'descripcion', 'fechaSolicitud', 'fechaRequerido']; // Add other valid columns here
        if (!in_array($field, $validColumns)) {
            $field = 'id'; // Default to a valid column
        }

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
        $this->successMessage = ''; // Resetea el mensaje de éxito
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
            $requisicion = RequisicionModel::updateOrCreate(
                ['id' => $this->requisicionId],
                $data
            );
            $this->successMessage = $this->requisicionId
                ? 'Requisición actualizada correctamente.'
                : 'Requisición creada correctamente.';
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

   public function mount()
    {
        $this->empleados = Empleado::all();

        $recursosGuardados = session('recursosSeleccionados', []);
        if (!empty($recursosGuardados)) {
            $this->recursosSeleccionados = $recursosGuardados;
            foreach ($recursosGuardados as $recurso) {
                $this->presupuestosSeleccionados[$recurso['id']] = $recurso['cantidad_seleccionada'];
            }
        }

        $this->departamentoSeleccionado = session('departamentoSeleccionado');
        
        $userId = Auth::id();
        $this->departamentosUsuario = Departamento::whereHas('empleados', function($q) use ($userId) {
            $q->where('empleados.id', $userId);
        })->with('unidadEjecutora')->get();

        $this->mostrarSelector = $this->departamentosUsuario->count() > 1;

        if ($this->departamentosUsuario->count() == 1) {
            $this->departamentoSeleccionado = $this->departamentosUsuario->first()->id;
        }
        
        $poaYearGuardado = session('poaYearSeleccionado');
        
        if ($poaYearGuardado) {
            $this->poaYear = $poaYearGuardado;
        } else {
            $poa = Poa::activo()->latest()->first();
            $this->poaYear = $poa?->anio;
        }

        $this->verificarPlazoRequisicion();
    }

    public function updatingBuscarActividad()
    {
        $this->resetPage();
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

    public function irAlSumario()
    {

        if (!$this->puedeCrearRequisicion) {
            session()->flash('error', $this->mensajePlazoRequisicion);
            return;
        }

        //dd($this->departamentoSeleccionado); 
        session([
            'recursosSeleccionados' => $this->recursosSeleccionados,
            'departamentoSeleccionado' => $this->departamentoSeleccionado,
            'poaYearSeleccionado'    => $this->poaYear,
        ]);
        return redirect()->route('requisiciones-sumario');
    }

    public function sincronizarDepartamento($id)
    {
        if ($this->departamentoSeleccionado != $id) {
            $this->recursosSeleccionados = [];
            $this->presupuestosSeleccionados = [];
            session()->forget('recursosSeleccionados');
        }
        
        $this->departamentoSeleccionado = $id;
        $this->resetPage();
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

        // Buscar o crear requisición
        $requisicion = RequisicionModel::where('idPoa', $this->idPoa)
            ->where('created_by', \Auth::id())
            ->orderByDesc('id')
            ->first();

        // Si no existe requisición, crear una nueva
        if (!$requisicion) {
            $poa = Poa::find($this->idPoa);
            if ($poa) {
                // Obtener el departamento del usuario actual
                $empleadoDepto = \DB::table('empleado_deptos')
                    ->where('idEmpleado', \Auth::id())
                    ->whereNull('deleted_at')
                    ->first();

                $idDepartamento = $empleadoDepto ? $empleadoDepto->idDepto : (\Auth::user()->idDepartamento ?? null);

                if (!$idDepartamento) {
                    throw new \Exception('No se pudo determinar el departamento del usuario.');
                }

                // Generar correlativo siguiendo la misma lógica del módulo de requisiciones
                $departamento = \App\Models\Departamento\Departamento::find($idDepartamento);
                $tipoDepto = $departamento->tipo ?? '';
                $nombreDepto = $departamento->name ?? '';
                $ultimo = RequisicionModel::orderBy('id', 'desc')->first();
                $numero = $ultimo ? ($ultimo->id + 1) : 1;
                $anio = $poa->anio ?? now()->format('Y');
                $correlativo = \App\Helpers\CorrelativoHelper::generarCorrelativo($tipoDepto, $nombreDepto, $anio, $numero);

                // Estado inicial
                $estadoInicial = $this->getEstadoPresentadoId();

                $requisicion = RequisicionModel::create([
                    'correlativo' => $correlativo,
                    'descripcion' => $this->descripcion,
                    'observacion' => $this->observacion,
                    'idPoa' => $this->idPoa,
                    'idDepartamento' => $idDepartamento,
                    'idEstado' => $estadoInicial,
                    'fechaSolicitud' => now(),
                    'fechaRequerido' => now(),
                    'created_by' => \Auth::id(),
                ]);
            }
        }

        if ($requisicion) {
            // Buscar detalle existente
            $detalle = DetalleRequisicion::where('idRequisicion', $requisicion->id)
                ->where('idPresupuesto', $this->ordenCombustibleRecursoId)
                ->orderByDesc('id')
                ->first();
            
            if ($detalle) {
                $idDetalleRequisicion = $detalle->id;
            }
        }

        // Si no se encontró detalle, intentar buscar por POA y Presupuesto
        if (!$idDetalleRequisicion) {
            $detalle = DetalleRequisicion::where('idPoa', $this->idPoa)
                ->where('idPresupuesto', $this->ordenCombustibleRecursoId)
                ->orderByDesc('id')
                ->first();
            if ($detalle) {
                $idDetalleRequisicion = $detalle->id;
            }
        }

        // Si aún no existe detalle, crear uno nuevo
        if (!$idDetalleRequisicion && $requisicion) {
            $presupuesto = Presupuesto::find($this->ordenCombustibleRecursoId);
            $idRecurso = $presupuesto ? ($presupuesto->idHistorico ?? $presupuesto->idrecurso ?? null) : null;
            $idUnidadMedida = $presupuesto ? ($presupuesto->idunidad ?? $presupuesto->idUnidadMedida ?? null) : null;

            if ($idRecurso && $idUnidadMedida) {
                $detalleNuevo = DetalleRequisicion::create([
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
        }

        $this->ordenCombustibleData['idDetalleRequisicion'] = $idDetalleRequisicion;

        $ultimo = RequisicionModel::orderBy('id', 'desc')->first();
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

    public function limpiarSumario()
    {
        $this->recursosSeleccionados = [];
        $this->presupuestosSeleccionados = [];
        session()->forget('recursosSeleccionados');
    }

    private function verificarPlazoRequisicion()
    {
        // Obtener el POA correspondiente al año seleccionado
        $poa = Poa::activo()
            ->when($this->poaYear, function ($query) {
                $query->where('anio', $this->poaYear);
            })
            ->first();

        if (!$poa) {
            $this->puedeCrearRequisicion = false;
            $this->mensajePlazoRequisicion = 'No hay un POA activo para el año seleccionado.';
            $this->diasRestantes = null;
            return;
        }

        // Obtener el plazo activo para el POA seleccionado
        $plazo = $poa->plazos()
            ->where('tipo_plazo', 'requerimientos')
            ->where('activo', true)
            ->first();

        if (!$plazo) {
            $this->puedeCrearRequisicion = false;
            $this->mensajePlazoRequisicion = 'No hay un plazo configurado para esta acción.';
            $this->diasRestantes = null;
            return;
        }

        // Validar si el plazo aún no ha iniciado
        if (now()->lt($plazo->fecha_inicio)) {
            $this->puedeCrearRequisicion = false;
            $this->mensajePlazoRequisicion = 'El plazo para esta acción aún no ha iniciado. Inicia el ' . $plazo->fecha_inicio->format('d/m/Y') . '.';
            $this->diasRestantes = null;
            return;
        }

        // Validar si el plazo ya pasó
        if (now()->gt($plazo->fecha_fin)) {
            $this->puedeCrearRequisicion = false;
            $this->mensajePlazoRequisicion = 'El plazo para esta acción ya pasó.';
            $this->diasRestantes = null;
            return;
        }

        // Calcular días restantes como un número entero
        $this->diasRestantes = floor(now()->diffInDays($plazo->fecha_fin, false));
        $this->puedeCrearRequisicion = $this->diasRestantes >= 0;
        $this->diasRestantes = $poa->getDiasRestantes('requerimientos'); 

        if (!$this->puedeCrearRequisicion) {
            $this->mensajePlazoRequisicion = 'El plazo para gestionar requisiciones ha finalizado.';
        }
    }

    public function updatedPoaYear()
    {
        // Actualizar el cálculo del plazo cuando se cambia el POA en el select
        $this->verificarPlazoRequisicion();
        $this->resetPage();
        session(['poaYearSeleccionado' => $this->poaYear]);

        // Limpiar recursos seleccionados al cambiar de POA
        $this->recursosSeleccionados = [];
        $this->presupuestosSeleccionados = [];
        session()->forget('recursosSeleccionados');
    }
     public function render()
    {
        $userId = Auth::id();
        $this->departamentosUsuario = Departamento::whereHas('empleados', function($q) use ($userId) {
            $q->where('empleados.id', $userId);
        })->with('unidadEjecutora')->get();
        $this->mostrarSelector = $this->departamentosUsuario->count() > 1;

        // Obtener el POA activo o el correspondiente al año seleccionado
        $poa = Poa::activo()
            ->when($this->poaYear, function($q) {
                $q->where('anio', $this->poaYear);
            })
            ->first();

        if ($poa) {
            $this->idPoa = $poa->id; // Asignar el idPoa del POA encontrado
        }

        $actividades_aprobadas = Tarea::whereHas('presupuestos', function($q) {
            $q->where('cantidad', '>', 0);
        })
        ->where('estado', 'APROBADO')
        ->when($this->poaYear, function($q) {
            $q->whereHas('poa', function($q2) {
                $q2->where('anio', $this->poaYear);
            });
        })
        ->when($this->buscarActividad, function($q) {
            $q->where(function($subq) {
                $subq->where('nombre', 'like', '%'.$this->buscarActividad.'%');
                $subq->orWhereHas('actividad', function($q2) {
                    $q2->where('nombre', 'like', '%'.$this->buscarActividad.'%');
                });
            });
        })
        ->when($this->departamentoSeleccionado, function($q) {
            // Filtrar por el departamento seleccionado a través de tareas relacionadas con presupuestos
            $q->where('idDeptartamento', $this->departamentoSeleccionado);
        })
        ->with(['presupuestos' => function($query) {
            $query->where('cantidad', '>', 0); // Filtrar presupuestos con cantidad mayor a 0
        }, 'presupuestos.objetoGasto', 'presupuestos.mes', 'presupuestos.unidadMedida', 'presupuestos.fuente', 'actividad'])
        ->paginate($this->perPage);

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

    $poas = Poa::activo()->get();
    $this->poaYears = $poas->pluck('anio')->unique()->sort()->values(); // Obtener años únicos de los POA activos

    return view('livewire.seguimiento.Requisicion.create-requisiciones', [
        'mostrarSelector' => $this->mostrarSelector,
        'departamentosUsuario' => $this->departamentosUsuario,
        'departamentoSeleccionado' => $this->departamentoSeleccionado,
        'actividades_aprobadas' => $actividades_aprobadas, // Pasar las actividades filtradas a la vista
        'valoresPlanificados' => $valoresPlanificados,
        'poaYears' => $this->poaYears, // Pasar los años únicos a la vista
        'puedeCrearRequisicion' => $this->puedeCrearRequisicion,
        'mensajePlazoRequisicion' => $this->mensajePlazoRequisicion,
        'diasRestantes' => $this->diasRestantes, // Pass remaining days to the view
    ])->layout($this->layout);
    }
}