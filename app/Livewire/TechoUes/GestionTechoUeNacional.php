<?php

namespace App\Livewire\TechoUes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Poa;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\TechoUes\TechoUe;
use App\Models\GrupoGastos\GrupoGasto;
use Illuminate\Pagination\LengthAwarePaginator;

class GestionTechoUeNacional extends Component
{
    use WithPagination;

    public $idPoa;
    public $poa;
    public $search = ''; // Buscador general
    public $searchConTecho = ''; // Buscador específico para UEs con techo
    public $searchSinTecho = ''; // Buscador específico para UEs sin techo
    public $activeTab = 'resumen'; // Propiedad para el tab activo
    public $showModal = false;
    public $showDeleteModal = false;
    public $techoUeToDelete = null;
    public $isEditing = false;
    public $techoUeId;

    // Propiedades del formulario
    public $idUnidadEjecutora = '';
    public $montosPorFuente = []; // Array para almacenar montos por fuente
    public $techoUeEditando = null; // Para edición

    // Listados para los selects
    public $unidadesEjecutoras = [];
    
    // Estado del plazo de asignación nacional
    public $puedeAsignarPresupuesto = false;
    public $mensajePlazo = '';
    public $diasRestantes = null;
    
    public function getFuentesProperty()
    {
        // Obtener techos globales (con idUE null) del POA actual como fuentes disponibles
        return TechoUe::with('fuente')
            ->where('idPoa', $this->idPoa)
            ->whereNull('idUE')
            ->where('monto', '>', 0)
            ->get()
            ->map(function($techo) {
                return (object)[
                    'id' => $techo->idFuente,
                    'nombre' => $techo->fuente->nombre ?? 'Sin nombre',
                    'descripcion' => $techo->fuente->descripcion ?? 'Sin descripción',
                    'disponible' => $techo->monto,  // Este es el monto total del techo global
                    'total' => $techo->monto        // Agregamos total para claridad
                ];
            });
    }
    
    protected $rules = [
        'idUnidadEjecutora' => 'required|exists:unidad_ejecutora,id',
    ];

    protected $messages = [
        'idUnidadEjecutora.required' => 'La unidad ejecutora es obligatoria.',
        'idUnidadEjecutora.exists' => 'La unidad ejecutora seleccionada no existe.',
    ];

    public function mount($idPoa = null)
    {
        // Obtener idPoa de parámetros de ruta o request
        $this->idPoa = $idPoa ?? request()->get('idPoa') ?? request()->route('idPoa');
        
        if (!$this->idPoa) {
            abort(404, 'POA no encontrado');
        }
        
        $this->loadPoa();
        $this->verificarPlazo();
        $this->loadUnidadesEjecutoras();
    }

    private function verificarPlazo()
    {
        if ($this->poa) {
            $this->puedeAsignarPresupuesto = $this->poa->puedeAsignarPresupuestoNacional();
            $this->diasRestantes = $this->poa->getDiasRestantesAsignacionNacional();
            
            if (!$this->puedeAsignarPresupuesto) {
                $this->mensajePlazo = $this->poa->getMensajeErrorPlazo('asignacion_nacional');
            }
        }
    }

    public function loadPoa()
    {
        // Obtener institución del usuario autenticado
        $user = auth()->user();
        $userInstitucionId = $user->empleado?->unidadEjecutora?->idInstitucion;

        $query = Poa::with(['institucion', 'techoUes.unidadEjecutora', 'techoUes.grupoGasto']);
        
        // Filtrar por institución del usuario si aplica
        if ($userInstitucionId) {
            $query->where('idInstitucion', $userInstitucionId);
        }
        
        $this->poa = $query->findOrFail($this->idPoa);
    }

    public function loadUnidadesEjecutoras()
    {
        // Obtener institución del usuario para mostrar todas las UEs de su institución
        $user = auth()->user();
        $userInstitucionId = $user->empleado?->unidadEjecutora?->idInstitucion;
        
        // Mostrar todas las UEs de la institución del usuario
        $this->unidadesEjecutoras = $userInstitucionId 
            ? UnidadEjecutora::where('idInstitucion', $userInstitucionId)->orderBy('name')->get()
            : UnidadEjecutora::orderBy('name')->get();
    }

    public function render()
    {
        // Obtener institución del usuario (no filtrar por UE específica aquí)
        $user = auth()->user();
        $userInstitucionId = $user->empleado?->unidadEjecutora?->idInstitucion;
        
        $techoUesConTecho = collect();
        $unidadesSinTecho = collect();
        $resumenPorFuente = [];
        $totalAsignado = 0;

        if ($this->poa) {
            // Obtener techos asignados de TODAS las UEs de la institución
            $techoUesQuery = $this->poa->techoUes()
                ->with(['unidadEjecutora', 'grupoGasto'])
                ->whereNotNull('idUE') // Excluir techos globales
                // Filtrar solo UEs de la institución del usuario
                ->when($userInstitucionId, function ($query) use ($userInstitucionId) {
                    $query->whereHas('unidadEjecutora', function ($q) use ($userInstitucionId) {
                        $q->where('idInstitucion', $userInstitucionId);
                    });
                });
            
            if ($this->searchConTecho) {
                $techoUesQuery->whereHas('unidadEjecutora', function($q) {
                    $q->where('name', 'like', '%' . $this->searchConTecho . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->searchConTecho . '%');
                });
            }
            
            $techoUesConTecho = $techoUesQuery->get();
            $totalAsignado = $techoUesConTecho->sum('monto');

            // Obtener UEs sin techo asignado (todas de la institución)
            $uesConTechoIds = $techoUesConTecho->pluck('idUE')->unique();
            $unidadesSinTechoQuery = UnidadEjecutora::whereNotIn('id', $uesConTechoIds)
                // Filtrar solo UEs de la institución del usuario
                ->when($userInstitucionId, function ($query) use ($userInstitucionId) {
                    $query->where('idInstitucion', $userInstitucionId);
                });
            
            if ($this->searchSinTecho) {
                $unidadesSinTechoQuery->where(function($q) {
                    $q->where('name', 'like', '%' . $this->searchSinTecho . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->searchSinTecho . '%');
                });
            }
            
            $unidadesSinTecho = $unidadesSinTechoQuery->orderBy('name')->get();

            // Calcular resumen por fuente de financiamiento
            $resumenPorFuente = $techoUesConTecho->groupBy(function($techo) {
                    return $techo->fuente ? $techo->fuente->identificador . " - " . $techo->fuente->nombre : 'Sin fuente';
                })
                ->map(function($fuente) {
                    return [
                        'cantidad' => $fuente->count(),
                        'monto' => $fuente->sum('monto')
                    ];
                });
        }

        return view('livewire.techo-ues.gestion-techo-ue-nacional', [
            'techoUesConTecho' => $techoUesConTecho,
            'unidadesSinTecho' => $unidadesSinTecho,
            'resumenPorFuente' => $resumenPorFuente,
            'totalAsignado' => $totalAsignado,
            'fuentes' => \App\Models\GrupoGastos\Fuente::orderBy('nombre')->get()
                ])->layout('layouts.app');


    }

    public function create()
    {
        // Verificar que se pueda asignar presupuesto
        if (!$this->puedeAsignarPresupuesto) {
            session()->flash('error', $this->mensajePlazo);
            return;
        }

        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->idUnidadEjecutora = '';
        $this->montosPorFuente = [];
        $this->techoUeEditando = null;
        $this->isEditing = false;
        $this->techoUeId = null;
        $this->resetValidation();
    }

    public function save()
    {
        // Limpiar validadores custom antes de validar
        $this->resetValidation();
        
        // Validar que al menos un monto sea mayor que 0
        $montosValidos = array_filter($this->montosPorFuente, function($monto) {
            return floatval($monto) > 0;
        });
        
        if (empty($montosValidos)) {
            session()->flash('error', 'Debe asignar al menos un monto mayor que 0.');
            return;
        }
        
        // Validar reglas básicas
        $rules = [
            'idUnidadEjecutora' => 'required|exists:unidad_ejecutora,id',
        ];
        
        // Validar cada monto por fuente
        foreach ($this->montosPorFuente as $idFuente => $monto) {
            $montoFloat = floatval($monto);
            if ($montoFloat > 0) {
                $rules["montosPorFuente.{$idFuente}"] = 'required|numeric|min:0.01';
                
                // En modo edición, validar que el monto no sea menor al mínimo permitido
                if ($this->isEditing) {
                    $montoMinimo = $this->getMontoMinimoPermitido($idFuente);
                    if ($montoFloat < $montoMinimo) {
                        $fuentes = $this->getFuentesProperty();
                        $fuente = $fuentes->firstWhere('id', $idFuente);
                        $fuenteNombre = $fuente->nombre ?? 'la fuente';
                        session()->flash('error', 
                            "El monto para {$fuenteNombre} no puede ser menor al asignado anteriormente (" . number_format($montoMinimo, 2) . ")."
                        );
                        return;
                    }
                }
            }
        }
        
        $this->validate($rules);

        // Validar disponibilidad de presupuesto para cada fuente
        foreach ($this->montosPorFuente as $idFuente => $monto) {
            $montoFloat = floatval($monto);
            if ($montoFloat > 0) {
                $disponibilidadValida = $this->validarDisponibilidadPresupuesto($montoFloat, $idFuente);
                if (!$disponibilidadValida) {
                    return; // El error ya se muestra en el método de validación
                }
            }
        }

        try {
            if ($this->isEditing) {
                $this->updateTechoUe();
            } else {
                $this->createTechoUe();
            }

            $this->showModal = false;
            $this->resetForm();
            session()->flash('message', $this->isEditing ? 'Techo actualizado exitosamente.' : 'Techo creado exitosamente.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    private function createTechoUe()
    {
        foreach ($this->montosPorFuente as $idFuente => $monto) {
            if ($monto > 0) {
                TechoUe::create([
                    'idPoa' => $this->idPoa,
                    'idUE' => $this->idUnidadEjecutora,
                    'idGrupo' => null,      // Para POA nacional, grupo puede ser null
                    'idFuente' => $idFuente,
                    'monto' => $monto,
                ]);
            }
        }
    }

    private function updateTechoUe()
    {
        // Obtener techos existentes para esta UE
        $techosExistentes = TechoUe::where('idPoa', $this->idPoa)
                                  ->where('idUE', $this->idUnidadEjecutora)
                                  ->get()
                                  ->keyBy('idFuente');

        $fuentesNuevas = [];
        
        // Procesar montos por fuente
        foreach ($this->montosPorFuente as $idFuente => $monto) {
            $montoFloat = floatval($monto);
            if ($montoFloat > 0) {
                $fuentesNuevas[] = $idFuente;
                
                if ($techosExistentes->has($idFuente)) {
                    // Actualizar techo existente - PRESERVA LAS RELACIONES CON TECHOS DEPARTAMENTALES
                    $techoExistente = $techosExistentes[$idFuente];
                    $techoExistente->update([
                        'monto' => $montoFloat,
                    ]);
                } else {
                    // Crear nuevo techo
                    TechoUe::create([
                        'idPoa' => $this->idPoa,
                        'idUE' => $this->idUnidadEjecutora,
                        'idGrupo' => null,
                        'idFuente' => $idFuente,
                        'monto' => $montoFloat,
                    ]);
                }
            }
        }
        
        // Solo eliminar techos que ya no están en los montos por fuente Y que no tienen departamentos asignados
        $fuentesAEliminar = $techosExistentes->keys()->diff($fuentesNuevas);
        if ($fuentesAEliminar->isNotEmpty()) {
            // Verificar cuales tienen departamentos asignados antes de eliminar
            $techosConDepartamentos = TechoUe::where('idPoa', $this->idPoa)
                   ->where('idUE', $this->idUnidadEjecutora)
                   ->whereIn('idFuente', $fuentesAEliminar)
                   ->whereHas('techoDeptos')
                   ->get();
                   
            if ($techosConDepartamentos->isNotEmpty()) {
                // Si hay techos con departamentos, solo actualizar a monto 0 en lugar de eliminar
                foreach ($techosConDepartamentos as $techo) {
                    $techo->update(['monto' => 0]);
                }
                
                // Eliminar solo los que NO tienen departamentos
                $idsTechosConDepartamentos = $techosConDepartamentos->pluck('idFuente');
                $fuentesSinDepartamentos = collect($fuentesAEliminar)->diff($idsTechosConDepartamentos);
                
                if ($fuentesSinDepartamentos->isNotEmpty()) {
                    TechoUe::where('idPoa', $this->idPoa)
                           ->where('idUE', $this->idUnidadEjecutora)
                           ->whereIn('idFuente', $fuentesSinDepartamentos)
                           ->delete();
                }
            } else {
                // Si ningún techo tiene departamentos, eliminar normalmente
                TechoUe::where('idPoa', $this->idPoa)
                       ->where('idUE', $this->idUnidadEjecutora)
                       ->whereIn('idFuente', $fuentesAEliminar)
                       ->delete();
            }
        }
    }

    public function edit($idUnidadEjecutora)
    {
        // Verificar que se pueda asignar presupuesto
        if (!$this->puedeAsignarPresupuesto) {
            session()->flash('error', $this->mensajePlazo);
            return;
        }

        $this->isEditing = true;
        $this->idUnidadEjecutora = $idUnidadEjecutora;
        
        // Cargar los techos existentes
        $techosExistentes = TechoUe::where('idPoa', $this->idPoa)
                                   ->where('idUE', $idUnidadEjecutora)
                                   ->get();
        
        $this->montosPorFuente = [];
        foreach ($techosExistentes as $techo) {
            $this->montosPorFuente[$techo->idFuente] = $techo->monto;
        }
        
        $this->showModal = true;
    }

    public function confirmDelete($idUnidadEjecutora)
    {
        // Obtener el primer techo para mostrar información de la UE
        $this->techoUeToDelete = TechoUe::with('unidadEjecutora')
            ->where('idPoa', $this->idPoa)
            ->where('idUE', $idUnidadEjecutora)
            ->first();
            
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            if ($this->techoUeToDelete) {
                TechoUe::where('idPoa', $this->idPoa)
                       ->where('idUE', $this->techoUeToDelete->idUE)
                       ->delete();
                
                session()->flash('message', 'Todos los techos de la unidad ejecutora han sido eliminados exitosamente.');
            }
            
            $this->closeDeleteModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar los techos: ' . $e->getMessage());
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->techoUeToDelete = null;
    }

    public function eliminarTodosLosTechos($idUnidadEjecutora)
    {
        $this->confirmDelete($idUnidadEjecutora);
    }

    public function crearTechoParaUe($idUnidadEjecutora)
    {
        $this->resetForm();
        $this->idUnidadEjecutora = $idUnidadEjecutora;
        $this->showModal = true;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['searchConTecho', 'searchSinTecho'])) {
            $this->resetPage();
        }
    }

    public function getDisponibilidadFuente($idFuente)
    {
        // Calcular el monto ya asignado desde esta fuente (excluyendo la UE actual si estamos editando)
        $montoAsignado = TechoUe::where('idPoa', $this->idPoa)
            ->where('idFuente', $idFuente)
            ->whereNotNull('idUE') // Solo techos de UEs, no globales
            ->when($this->isEditing && $this->idUnidadEjecutora, function($query) {
                $query->where('idUE', '!=', $this->idUnidadEjecutora);
            })
            ->sum('monto');

        // Obtener el techo global (fuente) para esta fuente
        $techoGlobal = TechoUe::where('idPoa', $this->idPoa)
            ->where('idFuente', $idFuente)
            ->whereNull('idUE') // Techo global
            ->first();

        if (!$techoGlobal) {
            return [
                'disponible' => 0.0,
                'usado' => 0.0,
                'porcentaje' => 0.0,
                'minimo' => 0.0,
                'total' => 0.0
            ];
        }

        // Asegurar que todos los valores son float
        $montoAsignado = floatval($montoAsignado);
        $montoTotalTecho = floatval($techoGlobal->monto);
        $montoDisponible = $montoTotalTecho - $montoAsignado;
        $porcentajeUsado = $montoTotalTecho > 0 ? ($montoAsignado / $montoTotalTecho) * 100 : 0;
        $montoMinimo = floatval($this->getMontoMinimoPermitido($idFuente));

        return [
            'disponible' => $montoDisponible,
            'usado' => $montoAsignado,
            'porcentaje' => $porcentajeUsado,
            'minimo' => $montoMinimo,
            'total' => $montoTotalTecho
        ];
    }

    private function getMontoMinimoPermitido($idFuente)
    {
        if (!$this->isEditing || !$this->idUnidadEjecutora) {
            return 0.0;
        }

        // Obtener el monto actual asignado desde esta fuente para esta UE
        $montoActual = TechoUe::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUnidadEjecutora)
            ->where('idFuente', $idFuente)
            ->sum('monto');

        return floatval($montoActual);
    }

    private function validarDisponibilidadPresupuesto($montoAValidar, $idFuente)
    {
        // Obtener el techo global para esta fuente
        $techoGlobal = TechoUe::where('idPoa', $this->idPoa)
            ->where('idFuente', $idFuente)
            ->whereNull('idUE')
            ->first();
            
        if (!$techoGlobal) {
            session()->flash('error', 'Fuente de financiamiento no encontrada.');
            return false;
        }

        // Calcular el monto ya asignado desde esta fuente
        $montoAsignado = TechoUe::where('idPoa', $this->idPoa)
            ->where('idFuente', $idFuente)
            ->whereNotNull('idUE'); // Solo techos de UEs
            
        // Si estamos editando, excluir el monto actual de esta UE
        if ($this->isEditing) {
            $montoAsignado->where('idUE', '!=', $this->idUnidadEjecutora);
        }
        
        $montoAsignado = floatval($montoAsignado->sum('monto'));
        $montoTotalTecho = floatval($techoGlobal->monto);
        $montoDisponible = $montoTotalTecho - $montoAsignado;
        $montoAValidarFloat = floatval($montoAValidar);

        if ($montoAValidarFloat > $montoDisponible) {
            $fuente = $techoGlobal->fuente;
            session()->flash('error', 
                'El monto para ' . ($fuente->nombre ?? 'la fuente') . ' excede el presupuesto disponible. ' .
                'Disponible: ' . number_format($montoDisponible, 2) . 
                ', Solicitado: ' . number_format($montoAValidarFloat, 2)
            );
            return false;
        }

        return true;
    }
}