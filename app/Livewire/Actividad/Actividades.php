<?php

namespace App\Livewire\Actividad;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Actividad\Actividad;
use App\Models\Actividad\TipoActividad;
use App\Models\Categoria\Categoria;
use App\Models\Dimension\Dimension;
use App\Models\Resultados\Resultado;
use App\Models\Departamento\Departamento;
use App\Models\Empleados\Empleado;
use App\Models\Poa\Poa;
use App\Models\Poa\PoaDepto;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Instituciones\Institucions;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Actividades extends Component
{
    use WithPagination;

    // Control de pasos del formulario
    public $currentStep = 1;
    public $totalSteps = 2;

    // Propiedades del modelo
    public $actividadId;
    public $nombre;
    public $descripcion;
    public $correlativo;
    public $resultadoActividad;
    public $poblacion_objetivo;
    public $medio_verificacion;
    public $estado = 'planificada';
    
    // Parámetros de URL
    #[Url]
    public $departamento = null;
    
    #[Url]
    public $idPoa = null; // POA desde URL, si no viene se usa el activo
    
    // Campos que se toman por defecto del contexto del usuario
    public $idPoaDepto;
    public $idInstitucion;
    public $idDeptartamento;
    public $idUE;
    
    // Campos editables
    public $idTipo;
    public $idResultado;
    public $idCategoria;
    
    // Campos del paso 2 (PEI)
    public $idDimension;
    public $resultadosPorDimension = [];

    // Filtros y búsqueda
    public $search = '';
    public $filtroEstado = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $activeTab = 'actividades'; // Tab activo: 'actividades' o 'resumen'

    // Control de modales
    public $modalOpen = false;
    public $modalDelete = false;
    public $actividadToDelete = null;

    // Listas para selects
    public $tiposActividad = [];
    public $categorias = [];
    public $dimensiones = [];
    public $empleados = [];

    // Contexto del usuario
    public $userContext = [];

    // Estado del plazo de planificación
    public $puedeCrearActividades = false;
    public $mensajePlazo = '';
    public $diasRestantes = null;
    public $esPoaHistorico = false; // Nueva propiedad para POAs históricos

    // Propiedades para IA
    public $usarIA = false;
    public $generandoConIA = false;
    public $nombreParaIA = '';
    public $indicadoresGenerados = [];

    protected function rules()
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'idTipo' => 'required|exists:tipo_actividads,id',
            'idCategoria' => 'nullable|exists:categorias,id',
        ];

        if ($this->currentStep == 2) {
            $rules['idDimension'] = 'required|exists:dimensions,id';
            $rules['idResultado'] = 'required|exists:resultados,id';
        }

        return $rules;
    }

    protected $messages = [
        'nombre.required' => 'El nombre de la actividad es obligatorio',
        'descripcion.required' => 'La descripción es obligatoria',
        'idTipo.required' => 'El tipo de actividad es obligatorio',
        'idDimension.required' => 'La dimensión es obligatoria',
        'idResultado.required' => 'El resultado es obligatorio',
    ];

    public function mount($departamento = null, $idPoa = null)
    {
        // El atributo #[Url] ya maneja la sincronización automática
        // Asegurarnos de que idPoa esté sincronizado desde los parámetros
        if ($idPoa && !$this->idPoa) {
            $this->idPoa = $idPoa;
        }
        
        $this->loadUserContext($this->departamento, $this->idPoa);
        $this->loadSelectData();
        $this->verificarPlazo();
    }
    
    // Watchers para recargar contexto cuando cambian los parámetros URL
    public function updatedDepartamento($value)
    {
        $this->loadUserContext($value, $this->idPoa);
        $this->loadSelectData();
        $this->verificarPlazo();
    }
    
    public function updatedIdPoa($value)
    {
        $this->loadUserContext($this->departamento, $value);
        $this->loadSelectData();
        $this->verificarPlazo();
    }

    public function verificarPlazo()
    {
        if (isset($this->userContext['poa'])) {
            $poa = $this->userContext['poa'];
            
            // Verificar si el POA es histórico (año ya pasó)
            $anioActual = (int) date('Y');
            $this->esPoaHistorico = $poa->anio < $anioActual;
            
            // Si es histórico, no se puede crear actividades
            if ($this->esPoaHistorico) {
                $this->puedeCrearActividades = false;
                $this->mensajePlazo = 'Este POA es histórico (año ' . $poa->anio . '). Solo puedes consultar la información, no realizar modificaciones.';
            } else {
                $this->puedeCrearActividades = $poa->puedePlanificar();
                $this->diasRestantes = $poa->getDiasRestantesPlanificacion();
                
                if (!$this->puedeCrearActividades) {
                    $this->mensajePlazo = $poa->getMensajeErrorPlazo('planificacion');
                }
            }
        }
    }

    public function loadUserContext($departamentoId = null, $poaId = null)
    {
        $user = Auth::user();
        
        if (!$user->idEmpleado) {
            session()->flash('error', 'No se encontró información de empleado para el usuario actual');
            return;
        }

        $empleado = Empleado::with(['departamentos', 'unidadEjecutora'])->find($user->idEmpleado);
        
        if (!$empleado) {
            session()->flash('error', 'No se encontró el registro de empleado');
            return;
        }

        // Si viene idPoa desde URL, usar ese; si no, usar el POA activo
        if ($poaId) {
            $poaActivo = Poa::find($poaId);
            
            if (!$poaActivo) {
                session()->flash('error', 'No se encontró el POA especificado');
                return;
            }
        } else {
            // Obtener POA activo
            $poaActivo = Poa::where('activo', true)->first();
            
            if (!$poaActivo) {
                session()->flash('error', 'No hay un POA activo');
                return;
            }
        }

        // Si viene el parámetro departamento desde la URL, usarlo
        $departamento = null;
        
        if ($departamentoId) {
            // Verificar que el empleado tenga acceso a este departamento
            $departamento = $empleado->departamentos()->where('departamentos.id', $departamentoId)->first();
            
            if (!$departamento) {
                session()->flash('warning', 'No tiene acceso al departamento solicitado. Mostrando su departamento principal.');
                // Si no tiene acceso, tomar el primero
                $departamento = $empleado->departamentos()->first();
            }
        } else {
            // Si no viene parámetro, tomar el primer departamento
            $departamento = $empleado->departamentos()->first();
        }
        
        if (!$departamento) {
            session()->flash('error', 'El empleado no tiene un departamento asignado');
            return;
        }

        // Obtener PoaDepto (puede no existir para POAs futuros o históricos sin techos)
        $poaDepto = PoaDepto::where('idPoa', $poaActivo->id)
            ->where('idDepartamento', $departamento->id)
            ->first();

        // Establecer valores por defecto
        $this->idDeptartamento = $departamento->id;
        $this->idUE = $empleado->idUnidadEjecutora;
        // Solo actualizar idPoa si no viene desde URL (para mantener sincronización)
        if (!$poaId) {
            $this->idPoa = $poaActivo->id;
        }
        $this->idPoaDepto = $poaDepto ? $poaDepto->id : null;
        $this->idInstitucion = $empleado->unidadEjecutora->idInstitucion ?? null;

        $this->userContext = [
            'empleado' => $empleado,
            'departamento' => $departamento,
            'poa' => $poaActivo,
            'unidadEjecutora' => $empleado->unidadEjecutora
        ];
    }

    public function loadSelectData()
    {
        $this->tiposActividad = TipoActividad::orderBy('tipo')->get();
        $this->categorias = Categoria::orderBy('categoria')->get();
        $this->dimensiones = Dimension::orderBy('nombre')->get();
        
        if ($this->idDeptartamento) {
            $this->empleados = Empleado::whereHas('departamentos', function($query) {
                $query->where('departamentos.id', $this->idDeptartamento);
            })->get();
        }
    }

    public function updatedIdDimension($value)
    {
        if ($value) {
            // Los resultados están relacionados con áreas, y las áreas con objetivos que tienen dimensiones
            $this->resultadosPorDimension = Resultado::whereHas('area.objetivo', function($query) use ($value) {
                $query->where('idDimension', $value);
            })
            ->with('area.objetivo.dimension')
            ->orderBy('nombre')
            ->get();
            $this->idResultado = null;
        } else {
            $this->resultadosPorDimension = [];
            $this->idResultado = null;
        }
    }

    public function crear()
    {
        // Verificar que se pueda planificar
        if (!$this->puedeCrearActividades) {
            session()->flash('error', $this->mensajePlazo);
            return;
        }

        $this->reset(['actividadId', 'nombre', 'descripcion', 'correlativo', 'resultadoActividad', 
                      'poblacion_objetivo', 'medio_verificacion', 'idTipo', 'idResultado', 
                      'idCategoria', 'idDimension']);
        
        $this->estado = 'FORMULACION';
        $this->currentStep = 1;
        $this->resultadosPorDimension = [];
        $this->modalOpen = true;
    }

    public function toggleIA()
    {
        $this->usarIA = !$this->usarIA;
        if (!$this->usarIA) {
            $this->nombreParaIA = '';
        }
    }

    public function generarConIA()
    {
        $this->validate([
              'nombre' => 'required|min:10|max:255',
             // 'nombreParaIA' => 'required|min:10|max:255'
        ], [
            'nombre.required' => 'Ingrese el nombre de la actividad',
            'nombre.min' => 'El nombre debe tener al menos 10 caracteres para generar con IA',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres'

            //'nombreParaIA.required' => 'Ingrese el nombre de la actividad',
          //  'nombreParaIA.min' => 'El nombre debe tener al menos 10 caracteres para generar con IA',
           // 'nombreParaIA.max' => 'El nombre no puede exceder 255 caracteres'
       
        ]);

        // Verificar throttling
        $throttleSeconds = config('ia.throttle_seconds', 30);
        $throttleKey = 'ia_actividad_' . auth()->id();
        $lastRequest = \Cache::get($throttleKey);
        
        if ($lastRequest && now()->diffInSeconds($lastRequest) < $throttleSeconds) {
            $segundosRestantes = $throttleSeconds - now()->diffInSeconds($lastRequest);
            session()->flash('error', "Por favor espera {$segundosRestantes} segundos antes de generar otra actividad con IA.");
            return;
        }

        $this->generandoConIA = true;
        $this->dispatch('ia-generando');

        try {
            //\Log::info('Iniciando generación con IA', ['nombre' => $this->nombreParaIA]);
            \Log::info('Iniciando generación con IA', ['nombre' => $this->nombre]);

            // Usar el servicio de IA
            $iaService = new \App\Services\IAService();
            $providerName = $iaService->getProviderName();
            
            \Log::info("Generando con {$providerName}");

            $contextoInstitucion = isset($this->userContext['institucion']) 
                ? $this->userContext['institucion']->nombre 
                : 'institución educativa';

            // Intentar con reintentos en caso de rate limit
            $maxIntentos = 3;
            $intentoActual = 0;
            $data = null;

            while ($intentoActual < $maxIntentos) {
                try {
                  //  $data = $iaService->generarActividad($this->nombreParaIA, $contextoInstitucion);
                     $data = $iaService->generarActividad($this->nombre, $contextoInstitucion);
                    \Log::info("Respuesta de {$providerName} recibida exitosamente");
                    break; // Si fue exitoso, salir del bucle
                    
                } catch (\Exception $apiException) {
                    $intentoActual++;
                    
                    // Verificar si es error de rate limit
                    if (str_contains($apiException->getMessage(), 'rate limit') || 
                        str_contains($apiException->getMessage(), 'Rate limit') ||
                        str_contains($apiException->getMessage(), 'quota')) {
                        
                        \Log::warning("Rate limit alcanzado en {$providerName}, intento {$intentoActual} de {$maxIntentos}");
                        
                        if ($intentoActual < $maxIntentos) {
                            // Esperar antes de reintentar (2 segundos por cada intento)
                            sleep(2 * $intentoActual);
                        } else {
                            throw new \Exception("Has alcanzado el límite de solicitudes de {$providerName}. Por favor espera 30 segundos e intenta nuevamente.");
                        }
                    } else {
                        // Si no es rate limit, lanzar la excepción original
                        throw $apiException;
                    }
                }
            }

            if (!$data) {
                throw new \Exception("No se pudo obtener respuesta de {$providerName} después de múltiples intentos.");
            }

            \Log::info('Datos procesados correctamente', ['data' => $data, 'provider' => $providerName]);

            // Asignar los valores generados
           // $this->nombre = $this->nombreParaIA;
            $this->descripcion = $data['descripcion'] ?? '';
            $this->resultadoActividad = $data['resultadoActividad'] ?? '';
            $this->poblacion_objetivo = $data['poblacion_objetivo'] ?? '';
            $this->medio_verificacion = $data['medio_verificacion'] ?? '';
            $this->indicadoresGenerados = $data['indicadores'] ?? [];

            // Cerrar el panel de IA
           // $this->usarIA = false;
          // $this->nombreParaIA = '';
            
            // Registrar el timestamp de esta solicitud para throttling
            \Cache::put($throttleKey, now(), 60); // Guardar por 60 segundos
            
            \Log::info('Actividad generada exitosamente');
            session()->flash('ia_success', '¡Actividad generada con IA! Revisa y ajusta los campos antes de continuar.');

        } catch (\Exception $e) {
            \Log::error('Error en generarConIA: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error al generar con IA: ' . $e->getMessage());
        } finally {
            $this->generandoConIA = false;
        }
    }

    public function cancelarIA()
    {
        $this->usarIA = false;
        $this->nombreParaIA = '';
        $this->generandoConIA = false;
    }

    public function editar($id)
    {
        $actividad = Actividad::with('resultado.area.objetivo')->findOrFail($id);
        
        // Verificar que pertenece al departamento del usuario
        if ($actividad->idDeptartamento != $this->idDeptartamento) {
            session()->flash('error', 'No tiene permisos para editar esta actividad');
            return;
        }

        $this->actividadId = $actividad->id;
        $this->nombre = $actividad->nombre;
        $this->descripcion = $actividad->descripcion;
        $this->correlativo = $actividad->correlativo;
        $this->resultadoActividad = $actividad->resultadoActividad;
        $this->poblacion_objetivo = $actividad->poblacion_objetivo;
        $this->medio_verificacion = $actividad->medio_verificacion;
        $this->estado = $actividad->estado;
        $this->idTipo = $actividad->idTipo;
        $this->idResultado = $actividad->idResultado;
        $this->idCategoria = $actividad->idCategoria;
        $this->idDimension = $actividad->resultado && $actividad->resultado->area && $actividad->resultado->area->objetivo 
            ? $actividad->resultado->area->objetivo->idDimension 
            : null;

        // Cargar resultados de la dimensión
        if ($this->idDimension) {
            $this->updatedIdDimension($this->idDimension);
        }

        $this->currentStep = 1;
        $this->modalOpen = true;
    }

    public function nextStep()
    {
        $this->validateCurrentStep();

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

    public function validateCurrentStep()
    {
        if ($this->currentStep == 1) {
            $this->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'idTipo' => 'required|exists:tipo_actividads,id',
            ]);
        } elseif ($this->currentStep == 2) {
            $this->validate([
                'idDimension' => 'required|exists:dimensions,id',
                'idResultado' => 'required|exists:resultados,id',
            ]);
        }
    }

    /**
     * Genera el correlativo con el formato:
     * ANIO-CATEGORIA-SIGLAS_DEPTO-R-ID_DIMENSION-ID_RESULTADO-NUM_ACTIVIDAD
     * Ejemplo: 2024-CA-INTER-R-15-112-2
     */
    private function generarCorrelativo()
    {
        // Obtener el contador de actividades para este POA y departamento
        $cantidadActividades = Actividad::where('idPoa', $this->idPoa)
            ->where('idDeptartamento', $this->idDeptartamento)
            ->count();
        
        $numeroActividad = $cantidadActividades + 1;
        
        // Obtener datos necesarios
        $poa = Poa::find($this->idPoa);
        $departamento = Departamento::find($this->idDeptartamento);
        $resultado = Resultado::with('area.objetivo.dimension')->find($this->idResultado);
        
        if (!$poa || !$departamento || !$resultado) {
            return $numeroActividad; // Fallback al número simple
        }
        
        $correlativo = '';
        
        // 1. Año del POA
        $correlativo .= $poa->anio . '-';
        
        // 2. Categoría (1=CA, 2=JF, 3=AD, otro=CR)
        $categoriaId = $this->idCategoria;
        if ($categoriaId == 1) {
            $correlativo .= 'CA-';
        } elseif ($categoriaId == 2) {
            $correlativo .= 'JF-';
        } elseif ($categoriaId == 3) {
            $correlativo .= 'AD-';
        } else {
            $correlativo .= 'CR-'; // default
        }
        
        // 3. Siglas del departamento
        $correlativo .= ($departamento->siglas ?? 'DEPTO') . '-';
        
        // 4. Literal "R" (Resultado)
        $correlativo .= 'R-';
        
        // 5. ID de la dimensión del resultado
        $dimensionId = $resultado->area?->objetivo?->dimension?->id ?? '0';
        $correlativo .= $dimensionId . '-';
        
        // 6. ID del resultado
        $correlativo .= $resultado->id . '-';
        
        // 7. Número correlativo de la actividad
        $correlativo .= $numeroActividad;
        
        return $correlativo;
    }

    public function guardar()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Generar correlativo si es una nueva actividad
            if (!$this->actividadId && empty($this->correlativo)) {
                $this->correlativo = $this->generarCorrelativo();
            }

            $datos = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'correlativo' => $this->correlativo,
                'resultadoActividad' => $this->resultadoActividad,
                'poblacion_objetivo' => $this->poblacion_objetivo,
                'medio_verificacion' => $this->medio_verificacion,
                'estado' => $this->estado,
                'idPoa' => $this->idPoa,
                'idPoaDepto' => $this->idPoaDepto,
                'idInstitucion' => $this->idInstitucion,
                'idDeptartamento' => $this->idDeptartamento,
                'idUE' => $this->idUE,
                'idTipo' => $this->idTipo,
                'idResultado' => $this->idResultado,
                'idCategoria' => $this->idCategoria,
            ];

            if ($this->actividadId) {
                $actividad = Actividad::findOrFail($this->actividadId);
                $actividad->update($datos);
                
                // Log de actualización
                LogService::activity(
                    'actualizar',
                    'actividades',
                    'Actividad actualizada: ' . $this->nombre,
                    [
                        'actividad_id' => $actividad->id,
                        'correlativo' => $this->correlativo,
                        'nombre' => $this->nombre,
                        'poa_id' => $this->idPoa,
                        'departamento_id' => $this->idDeptartamento,
                        'departamento' => $this->userContext['departamento']->name ?? null,
                        'tipo_actividad' => $this->idTipo,
                        'categoria' => $this->idCategoria,
                        'estado' => $this->estado,
                    ],
                    'info'
                );
                
                $mensaje = 'Actividad actualizada correctamente';
            } else {
                $actividad = Actividad::create($datos);
                
                // Log de creación
                LogService::activity(
                    'crear',
                    'actividades',
                    'Actividad creada: ' . $this->nombre,
                    [
                        'actividad_id' => $actividad->id,
                        'correlativo' => $this->correlativo,
                        'nombre' => $this->nombre,
                        'poa_id' => $this->idPoa,
                        'departamento_id' => $this->idDeptartamento,
                        'departamento' => $this->userContext['departamento']->name ?? null,
                        'tipo_actividad' => $this->idTipo,
                        'categoria' => $this->idCategoria,
                        'generada_con_ia' => !empty($this->indicadoresGenerados),
                    ],
                    'info'
                );
                
                // Crear indicadores si se generaron con IA
                if (!empty($this->indicadoresGenerados) && is_array($this->indicadoresGenerados)) {
                    foreach ($this->indicadoresGenerados as $indicadorData) {
                        \App\Models\Actividad\Indicador::create([
                            'nombre' => $indicadorData['nombre'] ?? '',
                            'descripcion' => $indicadorData['descripcion'] ?? '',
                            'cantidadPlanificada' => $indicadorData['cantidadPlanificada'] ?? 0,
                            'cantidadEjecutada' => 0,
                            'promedioAlcanzado' => 0,
                            'isCantidad' => $indicadorData['isCantidad'] ?? true,
                            'isPorcentaje' => $indicadorData['isPorcentaje'] ?? false,
                            'idActividad' => $actividad->id,
                        ]);
                    }
                }
                
                $mensaje = 'Actividad creada correctamente';
            }

            DB::commit();

            session()->flash('message', $mensaje);
            $this->modalOpen = false;
            $this->reset(['actividadId', 'nombre', 'descripcion']);
            $this->currentStep = 1;
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar la actividad: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $actividad = Actividad::find($id);
        
        if ($actividad && $actividad->idDeptartamento == $this->idDeptartamento) {
            $this->actividadToDelete = $actividad;
            $this->modalDelete = true;
        } else {
            session()->flash('error', 'No tiene permisos para eliminar esta actividad');
        }
    }

    public function eliminar()
    {
        if ($this->actividadToDelete) {
            try {
                // Guardar información para el log antes de eliminar
                $actividadId = $this->actividadToDelete->id;
                $actividadNombre = $this->actividadToDelete->nombre;
                $actividadCorrelativo = $this->actividadToDelete->correlativo;
                
                $this->actividadToDelete->delete();
                
                // Log de eliminación
                LogService::activity(
                    'eliminar',
                    'actividades',
                    'Actividad eliminada: ' . $actividadNombre,
                    [
                        'actividad_id' => $actividadId,
                        'correlativo' => $actividadCorrelativo,
                        'nombre' => $actividadNombre,
                        'poa_id' => $this->idPoa,
                        'departamento_id' => $this->idDeptartamento,
                        'departamento' => $this->userContext['departamento']->name ?? null,
                    ],
                    'info'
                );
                
                session()->flash('message', 'Actividad eliminada correctamente');
            } catch (\Exception $e) {
                session()->flash('error', 'Error al eliminar la actividad: ' . $e->getMessage());
            }
        }

        $this->modalDelete = false;
        $this->actividadToDelete = null;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function getResumenPresupuesto()
    {
        if (!$this->idPoaDepto) {
            return collect([]);
        }

        // Obtener el PoaDepto para acceder a los techos asignados
        $poaDepto = PoaDepto::with(['techoDeptos.techoUe.fuente'])
            ->find($this->idPoaDepto);

        if (!$poaDepto) {
            return collect([]);
        }

        $resumen = [];
        
        // Agrupar techos por fuente de financiamiento
        $techosPorFuente = $poaDepto->techoDeptos->groupBy(function($techoDepto) {
            return $techoDepto->techoUe->fuente->id ?? 'sin_fuente';
        });

        foreach ($techosPorFuente as $fuenteId => $techos) {
            if ($fuenteId === 'sin_fuente') {
                continue;
            }

            $fuente = $techos->first()->techoUe->fuente;
            $montoTotal = $techos->sum('monto');
            
            // Calcular monto asignado sumando todos los presupuestos de tareas con esta fuente
            // que pertenecen a actividades del departamento
            $idDeptartamento = $this->idDeptartamento;
            $idPoa = $this->idPoa;
            
            $montoAsignado = \App\Models\Presupuestos\Presupuesto::whereHas('tarea', function($q) use ($idDeptartamento, $idPoa) {
                $q->where('idDeptartamento', $idDeptartamento)
                  ->whereHas('actividad', function($aq) use ($idPoa) {
                      $aq->where('idPoa', $idPoa);
                  });
            })
            ->where('idfuente', $fuenteId)
            ->whereNull('deleted_at')
            ->sum('total');
            
            $montoDisponible = $montoTotal - $montoAsignado;
            $porcentajeUsado = $montoTotal > 0 ? ($montoAsignado / $montoTotal) * 100 : 0;

            $resumen[] = [
                'fuente' => $fuente->nombre ?? 'Sin fuente',
                'identificador' => $fuente->identificador ?? 'Sin identificador',
                'montoTotal' => $montoTotal,
                'montoAsignado' => $montoAsignado,
                'montoDisponible' => $montoDisponible,
                'porcentajeUsado' => $porcentajeUsado,
                'estado' => $this->getEstadoPresupuesto($porcentajeUsado),
            ];
        }

        return collect($resumen);
    }

    private function getEstadoPresupuesto($porcentaje)
    {
        if ($porcentaje >= 100) {
            return ['clase' => 'bg-red-500', 'texto' => 'Agotado', 'color' => 'text-red-700'];
        } elseif ($porcentaje >= 60) {
            return ['clase' => 'bg-yellow-500', 'texto' => 'Poco recurso', 'color' => 'text-yellow-700'];
        } else {
            return ['clase' => 'bg-green-500', 'texto' => 'Disponible', 'color' => 'text-green-700'];
        }
    }

    public function render()
    {
        if (!$this->idDeptartamento) {
            $actividadesVacias = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                10,
                1,
                ['path' => request()->url()]
            );
            
            return view('livewire.actividad.actividades', [
                'actividades' => $actividadesVacias,
                'resumenPresupuesto' => collect([])
            ]);
        }

        $actividades = Actividad::where('idDeptartamento', $this->idDeptartamento)
            ->where('idPoa', $this->idPoa) // Filtrar por el POA específico
            ->when($this->search, function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->when($this->filtroEstado, function($query) {
                $query->where('estado', $this->filtroEstado);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->with(['tipo', 'departamento', 'categoria', 'resultado.area.objetivo.dimension'])
            ->paginate(10);

        $resumenPresupuesto = $this->getResumenPresupuesto();

        return view('livewire.actividad.actividades', [
            'actividades' => $actividades,
            'resumenPresupuesto' => $resumenPresupuesto
        ]);
    }
}
