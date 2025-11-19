<?php

namespace App\Livewire\Actividad;

use Livewire\Component;
use Livewire\WithPagination;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    
    // Campos que se toman por defecto del contexto del usuario
    public $idPoa;
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

    // Propiedades para IA
    public $usarIA = false;
    public $generandoConIA = false;
    public $nombreParaIA = '';

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

    public function mount()
    {
        $this->loadUserContext();
        $this->loadSelectData();
        $this->verificarPlazo();
    }

    public function verificarPlazo()
    {
        if (isset($this->userContext['poa'])) {
            $poa = $this->userContext['poa'];
            $this->puedeCrearActividades = $poa->puedePlanificar();
            $this->diasRestantes = $poa->getDiasRestantesPlanificacion();
            
            if (!$this->puedeCrearActividades) {
                $this->mensajePlazo = $poa->getMensajeErrorPlazo('planificacion');
            }
        }
    }

    public function loadUserContext()
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

        $departamento = $empleado->departamentos()->first();
        
        if (!$departamento) {
            session()->flash('error', 'El empleado no tiene un departamento asignado');
            return;
        }

        // Obtener POA activo
        $poaActivo = Poa::where('activo', true)->first();
        
        if (!$poaActivo) {
            session()->flash('error', 'No hay un POA activo');
            return;
        }

        // Obtener PoaDepto
        $poaDepto = PoaDepto::where('idPoa', $poaActivo->id)
            ->where('idDepartamento', $departamento->id)
            ->first();

        // Establecer valores por defecto
        $this->idDeptartamento = $departamento->id;
        $this->idUE = $empleado->idUnidadEjecutora;
        $this->idPoa = $poaActivo->id;
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
            $this->resultadosPorDimension = Resultado::where('idDimension', $value)
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
        
        $this->estado = 'planificada';
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
            session()->flash('error', "⏱️ Por favor espera {$segundosRestantes} segundos antes de generar otra actividad con IA.");
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
        $actividad = Actividad::findOrFail($id);
        
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
        $this->idDimension = $actividad->resultado ? $actividad->resultado->idDimension : null;

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

    public function guardar()
    {
        $this->validate();

        try {
            DB::beginTransaction();

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
                $mensaje = 'Actividad actualizada correctamente';
            } else {
                Actividad::create($datos);
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
                $this->actividadToDelete->delete();
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
            
            // Por ahora, el monto asignado a actividades será 0 ya que no hay relación directa
            // Esto se puede implementar cuando se defina cómo las actividades consumen presupuesto
            $montoAsignado = 0;
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
            ])->layout('layouts.app');
        }

        $actividades = Actividad::where('idDeptartamento', $this->idDeptartamento)
            ->when($this->search, function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->when($this->filtroEstado, function($query) {
                $query->where('estado', $this->filtroEstado);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->with(['tipo', 'departamento', 'categoria', 'resultado.dimension'])
            ->paginate(10);

        $resumenPresupuesto = $this->getResumenPresupuesto();

        return view('livewire.actividad.actividades', [
            'actividades' => $actividades,
            'resumenPresupuesto' => $resumenPresupuesto
        ])->layout('layouts.app');
    }
}
