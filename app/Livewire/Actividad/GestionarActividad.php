<?php

namespace App\Livewire\Actividad;

use Livewire\Component;
use App\Models\Actividad\Actividad;
use App\Models\Actividad\Indicador;
use App\Models\Planificacion\Planificacion;
use App\Models\Empleados\Empleado;
use App\Models\Tareas\Tarea;
use App\Models\Mes\Mes;
use App\Models\Mes\Trimestre;
use App\Models\Presupuestos\Presupuesto;
use App\Models\Cubs\Cub;
use App\Models\Tareas\TareaHistorico;
use App\Models\GrupoGastos\Fuente;
use App\Models\GrupoGastos\ObjetoGasto;
use App\Models\Requisicion\UnidadMedida;
use App\Models\TechoUes\TechoDepto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GestionarActividad extends Component
{
    // Control de pasos
    public $currentStep = 1;
    public $totalSteps = 5;
    
    // Actividad
    public $actividadId;
    public $actividad;
    
    // Paso 1: Indicadores
    public $indicadores = [];
    public $nuevoIndicador = [
        'id' => null,
        'nombre' => '',
        'descripcion' => '',
        'cantidadPlanificada' => '',
        'isCantidad' => true,
        'isPorcentaje' => false
    ];
    
    // Paso 2: Planificaciones
    public $planificaciones = [];
    public $nuevaPlanificacion = [
        'id' => null,
        'idIndicador' => '',
        'idMes' => '',
        'cantidad' => '',
        'fechaInicio' => '',
        'fechaFin' => ''
    ];
    public $trimestres = [];
    
    // Paso 3: Empleados Encargados
    public $empleadosAsignados = [];
    public $empleadosDisponibles = [];
    public $nuevoEmpleado = [
        'idEmpleado' => '',
        'descripcion' => ''
    ];
    
    // Paso 4: Tareas
    public $tareas = [];
    public $nuevaTarea = [
        'id' => null,
        'nombre' => '',
        'descripcion' => '',
        'estado' => 'REVISION',
        'isPresupuesto' => false
    ];
    
    // Control de modales
    public $showIndicadorModal = false;
    public $showDeleteIndicadorModal = false;
    public $indicadorToDelete = null;
    public $showPlanificacionModal = false;
    public $showEmpleadoModal = false;
    public $showDeleteEmpleadoModal = false;
    public $empleadoToRemove = null;
    public $showTareaModal = false;
    public $showDeleteTareaModal = false;
    public $tareaToDelete = null;
    public $showAsignarEmpleadoTareaModal = false;
    public $showDeleteEmpleadoTareaModal = false;
    public $empleadoTareaToRemove = null;
    public $showPresupuestoModal = false;
    public $showDeletePlanificacionModal = false;
    public $planificacionToDelete = null;
    
    // Asignación de empleados a tareas
    public $tareaSeleccionada = null;
    public $empleadosAsignadosTarea = [];
    public $empleadosDisponiblesTarea = [];
    
    // Presupuesto de tareas
    public $presupuestosTarea = [];
    public $recursosDisponibles = [];
    public $fuentesFinanciamiento = [];
    public $unidadesMedida = [];
    public $meses = [];
    public $presupuestoTechoInfo = [
        'techoTotal' => 0,
        'presupuestoAsignado' => 0,
        'presupuestoDisponible' => 0,
        'departamentoNombre' => '',
        'fuenteNombre' => ''
    ];
    public $nuevoPresupuesto = [
        'idRecurso' => '',
        'detalle_tecnico' => '',
        'idfuente' => '',
        'idunidad' => '',
        'costounitario' => '',
        'cantidad' => '',
        'idMes' => '',
        'total' => 0
    ];

    // URL parameter
    #[\Livewire\Attributes\Url]
    public $idActividad;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        if (!$this->idActividad) {
            abort(404, 'Actividad no encontrada');
        }
        
        $this->actividadId = $this->idActividad;
        $this->loadActividad();
        $this->loadTrimestres();
        
        // Asignar automáticamente al usuario actual si no está asignado
        $this->asignarUsuarioActual();
    }

    private function asignarUsuarioActual()
    {
        $user = Auth::user();
        
        // Verificar si el usuario tiene un empleado asociado
        if (!$user->idEmpleado) {
            return;
        }
        
        // Verificar si ya está asignado
        $yaAsignado = $this->actividad->empleados()
            ->where('empleados.id', $user->idEmpleado)
            ->exists();
        
        if (!$yaAsignado) {
            // Asignar el empleado a la actividad
            $this->actividad->empleados()->attach($user->idEmpleado, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Recargar empleados
            $this->loadEmpleados();
        }
    }

    public function loadActividad()
    {
        $this->actividad = Actividad::with([
            'indicadores',
            'empleados',
            'departamento',
            'poa'
        ])->findOrFail($this->actividadId);
        
        $this->loadIndicadores();
        $this->loadTodasPlanificaciones();
        $this->loadEmpleados();
        $this->loadTareas();
    }

    public function loadIndicadores()
    {
        $this->indicadores = $this->actividad->indicadores()
            ->with(['planificacions' => function($query) {
                $query->with('mes.trimestre');
            }])
            ->get()
            ->toArray();
    }

    public function loadPlanificaciones($indicadorId)
    {
        $this->planificaciones = Planificacion::where('idIndicador', $indicadorId)
            ->with('mes.trimestre')
            ->get()
            ->toArray();
    }

    public function loadEmpleados()
    {
        // Empleados ya asignados
        $this->empleadosAsignados = $this->actividad->empleados()
            ->with('user')
            ->get()
            ->toArray();
        
        // Empleados disponibles del departamento
        $empleadosAsignadosIds = collect($this->empleadosAsignados)->pluck('id')->toArray();
        
        $this->empleadosDisponibles = Empleado::whereHas('departamentos', function($query) {
            $query->where('departamentos.id', $this->actividad->idDeptartamento);
        })
        ->whereNotIn('id', $empleadosAsignadosIds)
        ->with('user')
        ->orderBy('nombre')
        ->get()
        ->toArray();
    }

    public function loadTodasPlanificaciones()
    {
        // Cargar todas las planificaciones de todos los indicadores
        $this->planificaciones = Planificacion::whereHas('indicador', function($query) {
            $query->where('idActividad', $this->actividadId);
        })
        ->with(['indicador', 'mes.trimestre'])
        ->get()
        ->toArray();
    }

    public function loadTareas()
    {
        $this->tareas = Tarea::where('idActividad', $this->actividadId)
            ->with('empleados')
            ->get()
            ->toArray();
    }

    public function loadTrimestres()
    {
        $this->trimestres = Trimestre::with('meses')->orderBy('id')->get()->toArray();
    }

    // ============= PASO 1: INDICADORES =============
    
    public function openIndicadorModal()
    {
        $this->resetNuevoIndicador();
        $this->showIndicadorModal = true;
    }

    public function saveIndicador()
    {
        $this->validate([
            'nuevoIndicador.nombre' => 'required|string|max:255',
            'nuevoIndicador.descripcion' => 'required|string',
            'nuevoIndicador.cantidadPlanificada' => 'required|numeric|min:1'
        ], [
            'nuevoIndicador.nombre.required' => 'El nombre del indicador es obligatorio',
            'nuevoIndicador.descripcion.required' => 'La descripción es obligatoria',
            'nuevoIndicador.cantidadPlanificada.required' => 'La cantidad planificada es obligatoria',
            'nuevoIndicador.cantidadPlanificada.min' => 'La cantidad debe ser mayor a 0'
        ]);

        try {
            DB::beginTransaction();
            
            if (!empty($this->nuevoIndicador['id'])) {
                // Editar indicador existente
                $indicador = Indicador::findOrFail($this->nuevoIndicador['id']);
                $indicador->update([
                    'nombre' => $this->nuevoIndicador['nombre'],
                    'descripcion' => $this->nuevoIndicador['descripcion'],
                    'cantidadPlanificada' => $this->nuevoIndicador['cantidadPlanificada'],
                    'isCantidad' => $this->nuevoIndicador['isCantidad'],
                    'isPorcentaje' => $this->nuevoIndicador['isPorcentaje'],
                    'updated_by' => Auth::id()
                ]);
                $mensaje = 'Indicador actualizado exitosamente';
            } else {
                // Crear nuevo indicador
                Indicador::create([
                    'nombre' => $this->nuevoIndicador['nombre'],
                    'descripcion' => $this->nuevoIndicador['descripcion'],
                    'cantidadPlanificada' => $this->nuevoIndicador['cantidadPlanificada'],
                    'isCantidad' => $this->nuevoIndicador['isCantidad'],
                    'isPorcentaje' => $this->nuevoIndicador['isPorcentaje'],
                    'idActividad' => $this->actividadId,
                    'created_by' => Auth::id()
                ]);
                $mensaje = 'Indicador creado exitosamente';
            }
            
            DB::commit();
            
            $this->loadIndicadores();
            $this->resetNuevoIndicador();
            $this->showIndicadorModal = false;
            session()->flash('message', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar indicador: ' . $e->getMessage());
        }
    }

    public function editIndicador($indicadorId)
    {
        $indicador = Indicador::findOrFail($indicadorId);
        
        $this->nuevoIndicador = [
            'id' => $indicador->id,
            'nombre' => $indicador->nombre,
            'descripcion' => $indicador->descripcion,
            'cantidadPlanificada' => $indicador->cantidadPlanificada,
            'isCantidad' => $indicador->isCantidad,
            'isPorcentaje' => $indicador->isPorcentaje
        ];
        
        $this->showIndicadorModal = true;
    }

    public function openDeleteIndicadorModal($indicadorId)
    {
        $this->indicadorToDelete = Indicador::findOrFail($indicadorId);
        $this->showDeleteIndicadorModal = true;
    }

    public function closeDeleteIndicadorModal()
    {
        $this->showDeleteIndicadorModal = false;
        $this->indicadorToDelete = null;
    }

    public function confirmDeleteIndicador()
    {
        try {
            if ($this->indicadorToDelete) {
                $this->indicadorToDelete->delete();
                $this->loadIndicadores();
                session()->flash('message', 'Indicador eliminado exitosamente');
            }
            $this->closeDeleteIndicadorModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar indicador: ' . $e->getMessage());
            $this->closeDeleteIndicadorModal();
        }
    }

    public function deleteIndicador($indicadorId)
    {
        // Este método se mantiene para compatibilidad
        $this->openDeleteIndicadorModal($indicadorId);
    }

    private function resetNuevoIndicador()
    {
        $this->nuevoIndicador = [
            'id' => null,
            'nombre' => '',
            'descripcion' => '',
            'cantidadPlanificada' => '',
            'isCantidad' => true,
            'isPorcentaje' => false
        ];
    }

    // ============= PASO 2: PLANIFICACIONES =============
    
    public function openPlanificacionModal($indicadorId = null)
    {
        if ($indicadorId) {
            $this->nuevaPlanificacion['idIndicador'] = $indicadorId;
            $this->loadPlanificaciones($indicadorId);
        }
        $this->resetNuevaPlanificacion();
        $this->showPlanificacionModal = true;
    }

    public function calcularFechasPorTrimestre($trimestreId = null)
    {
        // Si no se pasa el ID, usar el del formulario
        if (!$trimestreId && isset($this->nuevaPlanificacion['idTrimestre'])) {
            $trimestreId = $this->nuevaPlanificacion['idTrimestre'];
        }
        
        if (!$trimestreId) {
            return;
        }
        
        // Obtener el trimestre de la base de datos
        $trimestre = Trimestre::find($trimestreId);
        
        if (!$trimestre) {
            return;
        }
        
        // Obtener el año del POA de la actividad
        $year = $this->actividad->poa->year;
        
        // Calcular fechas según el número de trimestre
        switch ($trimestre->trimestre) {
            case 1: // Primer trimestre: Enero-Marzo
                $this->nuevaPlanificacion['fechaInicio'] = "{$year}-01-01";
                $this->nuevaPlanificacion['fechaFin'] = "{$year}-03-31";
                break;
            case 2: // Segundo trimestre: Abril-Junio
                $this->nuevaPlanificacion['fechaInicio'] = "{$year}-04-01";
                $this->nuevaPlanificacion['fechaFin'] = "{$year}-06-30";
                break;
            case 3: // Tercer trimestre: Julio-Septiembre
                $this->nuevaPlanificacion['fechaInicio'] = "{$year}-07-01";
                $this->nuevaPlanificacion['fechaFin'] = "{$year}-09-30";
                break;
            case 4: // Cuarto trimestre: Octubre-Diciembre
                $this->nuevaPlanificacion['fechaInicio'] = "{$year}-10-01";
                $this->nuevaPlanificacion['fechaFin'] = "{$year}-12-31";
                break;
        }
    }

    public function savePlanificacion()
    {
        $this->validate([
            'nuevaPlanificacion.idIndicador' => 'required|exists:indicadores,id',
            'nuevaPlanificacion.idTrimestre' => 'required|exists:trimestres,id',
            'nuevaPlanificacion.cantidad' => 'required|numeric|min:0',
            'nuevaPlanificacion.fechaInicio' => 'required|date',
            'nuevaPlanificacion.fechaFin' => 'required|date|after_or_equal:nuevaPlanificacion.fechaInicio'
        ], [
            'nuevaPlanificacion.idIndicador.required' => 'Debe seleccionar un indicador',
            'nuevaPlanificacion.idTrimestre.required' => 'Debe seleccionar un trimestre',
            'nuevaPlanificacion.cantidad.required' => 'La cantidad es obligatoria',
            'nuevaPlanificacion.fechaInicio.required' => 'La fecha de inicio es obligatoria',
            'nuevaPlanificacion.fechaFin.required' => 'La fecha de fin es obligatoria',
            'nuevaPlanificacion.fechaFin.after_or_equal' => 'La fecha fin debe ser posterior o igual a la fecha de inicio'
        ]);

        try {
            DB::beginTransaction();
            
            // Obtener el indicador seleccionado
            $indicador = Indicador::findOrFail($this->nuevaPlanificacion['idIndicador']);
            
            // Si es edición, excluir la planificación actual del total
            $query = Planificacion::where('idIndicador', $this->nuevaPlanificacion['idIndicador']);
            if (!empty($this->nuevaPlanificacion['id'])) {
                $query->where('id', '!=', $this->nuevaPlanificacion['id']);
            }
            $totalPlanificado = $query->sum('cantidad');
            
            // Verificar que no se exceda la cantidad planificada del indicador
            $nuevoTotal = $totalPlanificado + $this->nuevaPlanificacion['cantidad'];
            
            if ($nuevoTotal > $indicador->cantidadPlanificada) {
                $disponible = $indicador->cantidadPlanificada - $totalPlanificado;
                session()->flash('error', "La cantidad excede la meta del indicador. Disponible: {$disponible} de {$indicador->cantidadPlanificada}");
                DB::rollBack();
                return;
            }
            
            // Obtener el primer mes del trimestre seleccionado
            $primerMes = Mes::where('idTrimestre', $this->nuevaPlanificacion['idTrimestre'])
                ->orderBy('id')
                ->first();
            
            if (!$primerMes) {
                session()->flash('error', 'No se encontraron meses para el trimestre seleccionado');
                DB::rollBack();
                return;
            }
            
            $planificacionData = [
                'cantidad' => $this->nuevaPlanificacion['cantidad'],
                'fechaInicio' => $this->nuevaPlanificacion['fechaInicio'],
                'fechaFin' => $this->nuevaPlanificacion['fechaFin'],
                'idActividad' => $this->actividadId,
                'idIndicador' => $this->nuevaPlanificacion['idIndicador'],
                'idMes' => $primerMes->id
            ];
            
            if (!empty($this->nuevaPlanificacion['id'])) {
                // Actualizar planificación existente
                $planificacion = Planificacion::findOrFail($this->nuevaPlanificacion['id']);
                $planificacion->update($planificacionData + ['updated_by' => Auth::id()]);
                $mensaje = 'Planificación actualizada exitosamente';
            } else {
                // Crear nueva planificación
                Planificacion::create($planificacionData + ['created_by' => Auth::id()]);
                $mensaje = 'Planificación creada exitosamente';
            }
            
            DB::commit();
            
            $this->loadIndicadores();
            $this->resetNuevaPlanificacion();
            $this->showPlanificacionModal = false;
            session()->flash('message', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar planificación: ' . $e->getMessage());
        }
    }

    public function editPlanificacion($planificacionId)
    {
        $planificacion = Planificacion::with(['indicador', 'mes.trimestre'])->findOrFail($planificacionId);
        
        $this->nuevaPlanificacion = [
            'id' => $planificacion->id,
            'idIndicador' => $planificacion->idIndicador,
            'idMes' => $planificacion->idMes,
            'cantidad' => $planificacion->cantidad,
            'fechaInicio' => $planificacion->fechaInicio,
            'fechaFin' => $planificacion->fechaFin
        ];
        
        $this->showPlanificacionModal = true;
    }

    public function openDeletePlanificacionModal($planificacionId)
    {
        $this->planificacionToDelete = Planificacion::with(['indicador', 'mes.trimestre'])->findOrFail($planificacionId);
        $this->showDeletePlanificacionModal = true;
    }

    public function closeDeletePlanificacionModal()
    {
        $this->showDeletePlanificacionModal = false;
        $this->planificacionToDelete = null;
    }

    public function confirmDeletePlanificacion()
    {
        try {
            if ($this->planificacionToDelete) {
                $this->planificacionToDelete->delete();
                $this->loadIndicadores();
                session()->flash('message', 'Planificación eliminada exitosamente');
            }
            $this->closeDeletePlanificacionModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar planificación: ' . $e->getMessage());
            $this->closeDeletePlanificacionModal();
        }
    }

    private function resetNuevaPlanificacion()
    {
        $this->nuevaPlanificacion = [
            'id' => null,
            'idIndicador' => $this->nuevaPlanificacion['idIndicador'] ?? '',
            'idMes' => '',
            'cantidad' => '',
            'fechaInicio' => '',
            'fechaFin' => ''
        ];
    }

    // ============= PASO 3: EMPLEADOS ENCARGADOS =============
    
    public function openEmpleadoModal()
    {
        $this->resetNuevoEmpleado();
        $this->showEmpleadoModal = true;
    }

    public function assignEmpleado()
    {
        $this->validate([
            'nuevoEmpleado.idEmpleado' => 'required|exists:empleados,id',
            'nuevoEmpleado.descripcion' => 'nullable|string|max:500'
        ], [
            'nuevoEmpleado.idEmpleado.required' => 'Debe seleccionar un empleado'
        ]);

        try {
            DB::beginTransaction();
            
            $this->actividad->empleados()->attach($this->nuevoEmpleado['idEmpleado'], [
                'descripcion' => $this->nuevoEmpleado['descripcion'],
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            $this->loadEmpleados();
            $this->showEmpleadoModal = false;
            session()->flash('message', 'Empleado asignado exitosamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al asignar empleado: ' . $e->getMessage());
        }
    }

    public function openDeleteEmpleadoModal($empleadoId)
    {
        $empleado = Empleado::findOrFail($empleadoId);
        $this->empleadoToRemove = [
            'id' => $empleado->id,
            'nombre' => $empleado->user->name ?? $empleado->nombre,
            'num_empleado' => $empleado->num_empleado
        ];
        $this->showDeleteEmpleadoModal = true;
    }

    public function closeDeleteEmpleadoModal()
    {
        $this->showDeleteEmpleadoModal = false;
        $this->empleadoToRemove = null;
    }

    public function confirmRemoveEmpleado()
    {
        try {
            if ($this->empleadoToRemove) {
                $this->actividad->empleados()->detach($this->empleadoToRemove['id']);
                $this->loadEmpleados();
                session()->flash('message', 'Empleado removido exitosamente');
            }
            $this->closeDeleteEmpleadoModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al remover empleado: ' . $e->getMessage());
            $this->closeDeleteEmpleadoModal();
        }
    }

    public function removeEmpleado($empleadoId)
    {
        try {
            $this->actividad->empleados()->detach($empleadoId);
            
            $this->loadEmpleados();
            session()->flash('message', 'Empleado removido exitosamente');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al remover empleado: ' . $e->getMessage());
        }
    }

    private function resetNuevoEmpleado()
    {
        $this->nuevoEmpleado = [
            'idEmpleado' => '',
            'descripcion' => ''
        ];
    }

    // ============= PASO 4: TAREAS =============
    
    public function openTareaModal()
    {
        $this->resetNuevaTarea();
        $this->showTareaModal = true;
    }

    public function saveTarea()
    {
        $this->validate([
            'nuevaTarea.nombre' => 'required|string|max:255',
            'nuevaTarea.descripcion' => 'required|string'
        ], [
            'nuevaTarea.nombre.required' => 'El nombre de la tarea es obligatorio',
            'nuevaTarea.descripcion.required' => 'La descripción es obligatoria'
        ]);

        try {
            DB::beginTransaction();
            
            if (!empty($this->nuevaTarea['id'])) {
                // Actualizar tarea existente
                $tarea = Tarea::findOrFail($this->nuevaTarea['id']);
                $tarea->update([
                    'nombre' => $this->nuevaTarea['nombre'],
                    'descripcion' => $this->nuevaTarea['descripcion'],
                    'estado' => $this->nuevaTarea['estado'],
                    'isPresupuesto' => $this->nuevaTarea['isPresupuesto'],
                    'updated_by' => Auth::id()
                ]);
                $mensaje = 'Tarea actualizada exitosamente';
            } else {
                // Crear nueva tarea
                // Generar correlativo
                $ultimaTarea = Tarea::where('idActividad', $this->actividadId)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $correlativo = $ultimaTarea 
                    ? (intval($ultimaTarea->correlativo) + 1) 
                    : 1;
                
                Tarea::create([
                    'nombre' => $this->nuevaTarea['nombre'],
                    'descripcion' => $this->nuevaTarea['descripcion'],
                    'correlativo' => str_pad($correlativo, 3, '0', STR_PAD_LEFT),
                    'estado' => $this->nuevaTarea['estado'],
                    'isPresupuesto' => $this->nuevaTarea['isPresupuesto'],
                    'idActividad' => $this->actividadId,
                    'idPoa' => $this->actividad->idPoa,
                    'idDeptartamento' => $this->actividad->idDeptartamento,
                    'idUE' => $this->actividad->idUE,
                    'created_by' => Auth::id()
                ]);
                $mensaje = 'Tarea creada exitosamente';
            }
            
            DB::commit();
            
            $this->loadTareas();
            $this->resetNuevaTarea();
            $this->showTareaModal = false;
            session()->flash('message', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar tarea: ' . $e->getMessage());
        }
    }

    public function editTarea($tareaId)
    {
        $tarea = Tarea::findOrFail($tareaId);
        
        $this->nuevaTarea = [
            'id' => $tarea->id,
            'nombre' => $tarea->nombre,
            'descripcion' => $tarea->descripcion,
            'estado' => $tarea->estado,
            'isPresupuesto' => $tarea->isPresupuesto
        ];
        
        $this->showTareaModal = true;
    }

    public function openDeleteTareaModal($tareaId)
    {
        $this->tareaToDelete = Tarea::findOrFail($tareaId);
        $this->showDeleteTareaModal = true;
    }

    public function closeDeleteTareaModal()
    {
        $this->showDeleteTareaModal = false;
        $this->tareaToDelete = null;
    }

    public function confirmDeleteTarea()
    {
        try {
            if ($this->tareaToDelete) {
                $this->tareaToDelete->delete();
                $this->loadTareas();
                session()->flash('message', 'Tarea eliminada exitosamente');
            }
            $this->closeDeleteTareaModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar tarea: ' . $e->getMessage());
            $this->closeDeleteTareaModal();
        }
    }

    public function deleteTarea($tareaId)
    {
        // Este método se mantiene para compatibilidad
        $this->openDeleteTareaModal($tareaId);
    }

    private function resetNuevaTarea()
    {
        $this->nuevaTarea = [
            'id' => null,
            'nombre' => '',
            'descripcion' => '',
            'estado' => 'REVISION',
            'isPresupuesto' => false
        ];
    }

    // ============= ASIGNACIÓN DE EMPLEADOS A TAREAS =============
    
    public function openAsignarEmpleadoTareaModal($tareaId)
    {
        $this->tareaSeleccionada = $tareaId;
        $this->loadEmpleadosTarea($tareaId);
        $this->showAsignarEmpleadoTareaModal = true;
    }

    private function loadEmpleadosTarea($tareaId)
    {
        $tarea = Tarea::with('empleados')->findOrFail($tareaId);
        
        // Empleados ya asignados a la tarea
        $this->empleadosAsignadosTarea = $tarea->empleados->toArray();
        
        // Empleados asignados a la actividad pero no a la tarea
        $empleadosAsignadosIds = collect($this->empleadosAsignadosTarea)->pluck('id')->toArray();
        
        $this->empleadosDisponiblesTarea = collect($this->empleadosAsignados)
            ->whereNotIn('id', $empleadosAsignadosIds)
            ->values()
            ->toArray();
    }

    public function asignarEmpleadoATarea($empleadoId)
    {
        try {
            $tarea = Tarea::findOrFail($this->tareaSeleccionada);
            
            // Verificar que el empleado esté asignado a la actividad
            $empleadoEnActividad = $this->actividad->empleados()
                ->where('empleados.id', $empleadoId)
                ->exists();
            
            if (!$empleadoEnActividad) {
                session()->flash('error', 'El empleado debe estar asignado a la actividad primero');
                return;
            }
            
            $tarea->empleados()->attach($empleadoId, [
                'idActividad' => $this->actividadId,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => Auth::id()
            ]);
            
            $this->loadEmpleadosTarea($this->tareaSeleccionada);
            $this->loadTareas();
            session()->flash('message', 'Empleado asignado a la tarea exitosamente');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al asignar empleado: ' . $e->getMessage());
        }
    }

    public function openDeleteEmpleadoTareaModal($empleadoId)
    {
        $empleado = Empleado::findOrFail($empleadoId);
        $this->empleadoTareaToRemove = [
            'id' => $empleado->id,
            'nombre' => $empleado->user->name ?? $empleado->nombre,
            'num_empleado' => $empleado->num_empleado
        ];
        $this->showDeleteEmpleadoTareaModal = true;
    }

    public function closeDeleteEmpleadoTareaModal()
    {
        $this->showDeleteEmpleadoTareaModal = false;
        $this->empleadoTareaToRemove = null;
    }

    public function confirmRemoveEmpleadoDeTarea()
    {
        try {
            if ($this->empleadoTareaToRemove && $this->tareaSeleccionada) {
                $tarea = Tarea::findOrFail($this->tareaSeleccionada);
                $tarea->empleados()->detach($this->empleadoTareaToRemove['id']);
                $this->loadEmpleadosTarea($this->tareaSeleccionada);
                $this->loadTareas();
                session()->flash('message', 'Empleado removido de la tarea exitosamente');
            }
            $this->closeDeleteEmpleadoTareaModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al remover empleado: ' . $e->getMessage());
            $this->closeDeleteEmpleadoTareaModal();
        }
    }

    public function removerEmpleadoDeTarea($empleadoId)
    {
        try {
            $tarea = Tarea::findOrFail($this->tareaSeleccionada);
            $tarea->empleados()->detach($empleadoId);
            
            $this->loadEmpleadosTarea($this->tareaSeleccionada);
            $this->loadTareas();
            session()->flash('message', 'Empleado removido de la tarea exitosamente');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al remover empleado: ' . $e->getMessage());
        }
    }

    // ============= PRESUPUESTO DE TAREAS =============
    
    public function openPresupuestoModal($tareaId)
    {
        $this->tareaSeleccionada = $tareaId;
        \Log::debug("Abriendo modal presupuesto para tarea: {$tareaId}");
        
        // Obtener la tarea para debug
        $tarea = Tarea::find($tareaId);
        if ($tarea) {
            \Log::debug("Tarea encontrada: {$tarea->nombre}, Departamento: {$tarea->idDeptartamento}");
            
            // Cargar información del techo del departamento (sin fuente específica por ahora)
            $this->loadTechoDepartamento($tarea);
        } else {
            \Log::error("Tarea NO ENCONTRADA con ID: {$tareaId}");
        }
        
        $this->loadPresupuestosTarea($tareaId);
        $this->loadRecursosYCatalogos();
        $this->resetNuevoPresupuesto();
        $this->showPresupuestoModal = true;
    }

    private function loadTechoDepartamento($tarea, $idFuente = null)
    {
        try {
            // Cargar la relación departamento si no está cargada
            if (!$tarea->departamento) {
                $tarea->load('departamento');
            }
            
            // Construir consulta para obtener techos del departamento
            $query = TechoDepto::where('idDepartamento', $tarea->idDeptartamento);
            
            // Si se proporciona una fuente, filtrar por ella a través de techo_ues
            if ($idFuente) {
                // Obtener los techo_ues que corresponden a esta fuente
                $techoUesIds = \App\Models\TechoUes\TechoUe::where('idFuente', $idFuente)
                    ->pluck('id')
                    ->toArray();
                
                if (!empty($techoUesIds)) {
                    $query->whereIn('idTechoUE', $techoUesIds);
                } else {
                    // Si no hay techo_ues para esta fuente, devolver vacío
                    $this->presupuestoTechoInfo = [
                        'techoTotal' => 0,
                        'presupuestoAsignado' => 0,
                        'presupuestoDisponible' => 0,
                        'departamentoNombre' => $tarea->departamento->name ?? 'N/A',
                        'fuenteNombre' => Fuente::find($idFuente)->nombre ?? 'Desconocida'
                    ];
                    return;
                }
            }
            
            $techosDepartamento = $query->get();
            
            if ($techosDepartamento->isNotEmpty()) {
                // Sumar todos los techos disponibles para esta fuente (o todas si no hay fuente específica)
                $techoTotalDisponible = $techosDepartamento->sum('monto');
                
                // Calcular presupuesto ya asignado a esta tarea PARA LA FUENTE SELECCIONADA
                $queryPresupuesto = Presupuesto::where('idtarea', $tarea->id)
                    ->whereNull('deleted_at');
                if ($idFuente) {
                    $queryPresupuesto->where('idfuente', $idFuente);
                }
                $presupuestoTareaActual = $queryPresupuesto->sum('total');
                
                // Obtener nombre de la fuente si está disponible
                $fuenteNombre = 'General';
                if ($idFuente) {
                    $fuente = Fuente::find($idFuente);
                    $fuenteNombre = $fuente->nombre ?? 'Fuente ' . $idFuente;
                }
                
                $this->presupuestoTechoInfo = [
                    'techoTotal' => $techoTotalDisponible,
                    'presupuestoAsignado' => $presupuestoTareaActual,
                    'presupuestoDisponible' => $techoTotalDisponible - $presupuestoTareaActual,
                    'departamentoNombre' => $tarea->departamento->name ?? 'N/A',
                    'fuenteNombre' => $fuenteNombre
                ];
                
                \Log::debug("Techo cargado - Fuente: {$fuenteNombre}, Total: {$techoTotalDisponible}, Asignado: {$presupuestoTareaActual}, Disponible: " . ($techoTotalDisponible - $presupuestoTareaActual));
            } else {
                \Log::warning("No hay techo asignado al departamento: {$tarea->idDeptartamento}");
                $fuenteNombre = $idFuente ? (Fuente::find($idFuente)->nombre ?? 'Desconocida') : 'General';
                $this->presupuestoTechoInfo = [
                    'techoTotal' => 0,
                    'presupuestoAsignado' => 0,
                    'presupuestoDisponible' => 0,
                    'departamentoNombre' => $tarea->departamento->name ?? 'N/A',
                    'fuenteNombre' => $fuenteNombre
                ];
            }
        } catch (\Exception $e) {
            \Log::error("Error cargando techo: " . $e->getMessage());
            $this->presupuestoTechoInfo = [
                'techoTotal' => 0,
                'presupuestoAsignado' => 0,
                'presupuestoDisponible' => 0,
                'departamentoNombre' => $tarea->departamento->name ?? 'Desconocido',
                'fuenteNombre' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    private function loadPresupuestosTarea($tareaId)
    {
        // Usar whereNull para excluir soft deletes explícitamente
        $this->presupuestosTarea = Presupuesto::where('idtarea', $tareaId)
            ->whereNull('deleted_at')
            ->with(['fuente', 'unidadMedida', 'mes'])
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        
        // Debug: Log si no encuentra presupuestos
        if (empty($this->presupuestosTarea)) {
            \Log::debug("No presupuestos encontrados para tarea ID: {$tareaId}");
            
            // Verificar si la tarea existe
            $tarea = Tarea::find($tareaId);
            if ($tarea) {
                \Log::debug("Tarea existe: {$tarea->id}, idActividad: {$tarea->idActividad}");
            } else {
                \Log::debug("Tarea NO existe con ID: {$tareaId}");
            }
            
            // Ver todos los presupuestos sin filtrar
            $todosPresupuestos = Presupuesto::count();
            \Log::debug("Total presupuestos en base de datos: {$todosPresupuestos}");
        }
    }

    private function loadRecursosYCatalogos()
    {
        // Cargar recursos (Tareas Historicos)
        $this->recursosDisponibles = TareaHistorico::with('objeto')
            ->orderBy('nombre')
            ->get()
            ->toArray();
        
        // Cargar fuentes de financiamiento
        $this->fuentesFinanciamiento = Fuente::orderBy('nombre')->get()->toArray();
        
        // Cargar unidades de medida
        $this->unidadesMedida = UnidadMedida::orderBy('nombre')->get()->toArray();
        
        // Cargar meses del año del POA
        $this->meses = Mes::whereHas('trimestre', function($query) {
            // Aquí podrías filtrar por año si es necesario
        })->orderBy('mes')->get()->toArray();
    }

    public function updatedNuevoPresupuestoCostounitario()
    {
        $this->calcularTotal();
    }

    public function updatedNuevoPresupuestoCantidad()
    {
        $this->calcularTotal();
    }

    public function updatedNuevoPresupuestoIdfuente($value)
    {
        // Cuando cambia la fuente, recalcular el presupuesto disponible
        if ($this->tareaSeleccionada) {
            $tarea = Tarea::find($this->tareaSeleccionada);
            if ($tarea) {
                $this->loadTechoDepartamento($tarea, $value);
            }
        }
    }

    private function calcularTotal()
    {
        $costounitario = floatval($this->nuevoPresupuesto['costounitario'] ?? 0);
        $cantidad = floatval($this->nuevoPresupuesto['cantidad'] ?? 0);
        $this->nuevoPresupuesto['total'] = $costounitario * $cantidad;
    }

    public function savePresupuesto()
    {
        $this->validate([
            'nuevoPresupuesto.idRecurso' => 'required|exists:tareas_historicos,id',
            'nuevoPresupuesto.detalle_tecnico' => 'required|string',
            'nuevoPresupuesto.idfuente' => 'required|exists:fuente,id',
            'nuevoPresupuesto.idunidad' => 'required|exists:unidadmedidas,id',
            'nuevoPresupuesto.costounitario' => 'required|numeric|min:0',
            'nuevoPresupuesto.cantidad' => 'required|numeric|min:0.01',
            'nuevoPresupuesto.idMes' => 'required|exists:mes,id'
        ], [
            'nuevoPresupuesto.idRecurso.required' => 'Debe seleccionar un recurso',
            'nuevoPresupuesto.detalle_tecnico.required' => 'El detalle técnico es obligatorio',
            'nuevoPresupuesto.idfuente.required' => 'Debe seleccionar una fuente de financiamiento',
            'nuevoPresupuesto.idunidad.required' => 'Debe seleccionar una unidad de medida',
            'nuevoPresupuesto.costounitario.required' => 'El costo unitario es obligatorio',
            'nuevoPresupuesto.cantidad.required' => 'La cantidad es obligatoria',
            'nuevoPresupuesto.idMes.required' => 'Debe seleccionar el mes de ejecución'
        ]);

        try {
            DB::beginTransaction();
            
            // Obtener el recurso seleccionado para obtener los datos del objeto de gasto
            $recurso = TareaHistorico::with('objeto')->findOrFail($this->nuevoPresupuesto['idRecurso']);
            
            // Obtener idgrupo del objeto de gasto
            $idgrupo = null;
            if ($recurso->idobjeto) {
                $objetoGasto = ObjetoGasto::find($recurso->idobjeto);
                $idgrupo = $objetoGasto?->idgrupo;
            }
            
            // Obtener la tarea y el departamento
            $tarea = Tarea::findOrFail($this->tareaSeleccionada);
            $idDepartamento = $tarea->idDeptartamento;
            $idFuente = $this->nuevoPresupuesto['idfuente'];
            
            // Obtener los techo_ues que corresponden a esta fuente
            $techoUesIds = \App\Models\TechoUes\TechoUe::where('idFuente', $idFuente)
                ->pluck('id')
                ->toArray();
            
            if (empty($techoUesIds)) {
                throw new \Exception('No hay techo presupuestario para la fuente de financiamiento seleccionada');
            }
            
            // Obtener el techo del departamento para esta fuente
            $techoDepto = TechoDepto::where('idDepartamento', $idDepartamento)
                ->whereIn('idTechoUE', $techoUesIds)
                ->first();
            
            if (!$techoDepto) {
                throw new \Exception('No se encontró techo presupuestario para el departamento y fuente de financiamiento seleccionada');
            }
            
            // Verificar que haya suficiente presupuesto disponible
            $presupuestoTotal = $this->nuevoPresupuesto['total'];
            if ($techoDepto->monto < $presupuestoTotal) {
                throw new \Exception('Presupuesto insuficiente. Disponible: L ' . number_format($techoDepto->monto, 2) . ', Requerido: L ' . number_format($presupuestoTotal, 2));
            }
            
            // Crear el presupuesto (registrar la asignación, sin modificar el techo_depto)
            // Esto es similar a cómo techo_deptos registra asignaciones del techo_ues sin modificarlo
            Presupuesto::create([
                'cantidad' => $this->nuevoPresupuesto['cantidad'],
                'costounitario' => $this->nuevoPresupuesto['costounitario'],
                'total' => $this->nuevoPresupuesto['total'],
                'detalle_tecnico' => $this->nuevoPresupuesto['detalle_tecnico'],
                'recurso' => $recurso->nombre,
                'idgrupo' => $idgrupo ?? 1,
                'idobjeto' => $recurso->idobjeto,
                'idtarea' => $this->tareaSeleccionada,
                'idfuente' => $this->nuevoPresupuesto['idfuente'],
                'idunidad' => $this->nuevoPresupuesto['idunidad'],
                'idMes' => $this->nuevoPresupuesto['idMes'],
                'created_by' => Auth::id()
            ]);
            
            DB::commit();
            
            $this->loadPresupuestosTarea($this->tareaSeleccionada);
            $this->loadTareas();
            $this->resetNuevoPresupuesto();
            $this->loadTechoDepartamento($tarea, $idFuente);
            session()->flash('message', 'Recurso presupuestario agregado exitosamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al agregar presupuesto: ' . $e->getMessage());
        }
    }

    public function deletePresupuesto($presupuestoId)
    {
        try {
            DB::beginTransaction();
            
            $presupuesto = Presupuesto::findOrFail($presupuestoId);
            $idFuente = $presupuesto->idfuente;
            
            // Obtener la tarea
            $tarea = Tarea::findOrFail($presupuesto->idtarea);
            
            // Eliminar el presupuesto (sin modificar el techo_depto)
            $presupuesto->delete();
            
            DB::commit();
            
            $this->loadPresupuestosTarea($this->tareaSeleccionada);
            $this->loadTareas();
            $tarea->load('departamento');
            $this->loadTechoDepartamento($tarea, $idFuente);
            session()->flash('message', 'Presupuesto eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al eliminar presupuesto: ' . $e->getMessage());
        }
    }

    private function resetNuevoPresupuesto()
    {
        $this->nuevoPresupuesto = [
            'idRecurso' => '',
            'detalle_tecnico' => '',
            'idfuente' => '',
            'idunidad' => '',
            'costounitario' => '',
            'cantidad' => '',
            'idMes' => '',
            'total' => 0
        ];
    }

    // ============= NAVEGACIÓN ENTRE PASOS =============
    
    public function nextStep()
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            $this->currentStep = $step;
        }
    }

    public function finalizarGestion()
    {
        return redirect()->route('actividades', [
            'idPoa' => $this->actividad->idPoa,
            'departamento' => $this->actividad->idDeptartamento
        ])->with('message', 'Gestión de actividad completada exitosamente');
    }

    public function enviarARevision()
    {
        try {
            DB::beginTransaction();
            
            // Actualizar estado de la actividad a REVISION
            $this->actividad->update([
                'estado' => 'REVISION',
                'updated_by' => Auth::id()
            ]);
            
            DB::commit();
            
            return redirect()->route('actividades', [
                'idPoa' => $this->actividad->idPoa,
                'departamento' => $this->actividad->idDeptartamento
            ])->with('message', 'Actividad enviada a revisión exitosamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al enviar a revisión: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.actividad.gestionar-actividad')->layout('layouts.app');
    }
}
