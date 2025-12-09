<?php

namespace App\Livewire\Consola;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Poa;
use App\Models\Instituciones\Institucion;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\TechoUes\TechoUe;
use App\Models\TechoUes\TechoDepto;
use App\Models\GrupoGastos\GrupoGasto;
use App\Models\GrupoGastos\Fuente;

class AsignacionPresuNacional extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $poaToDelete = null;
    public $isEditing = false;
    public $poaId;
    public $filtroAnio = 'todos';
    public $sortField = 'anio';
    public $sortDirection = 'desc';

    // Propiedades del formulario
    public $name = '';
    public $anio = '';
    public $idInstitucion = '';
    public $activo = true; // Estado activo del POA
    
    // Propiedades para múltiples Techos UE
    public $techos = [];

    // Nota: El plazo de asignación_nacional solo aplica para asignar presupuesto a las UEs,
    // no para asignar presupuesto de fuentes al POA. Por lo tanto, estas propiedades
    // siempre se mantienen en valores que permiten la edición del POA.
    public $puedeAsignarPresupuestoNacional = true;
    public $mensajePlazo = '';
    public $diasRestantes = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'anio' => 'required|integer|min:2020|max:2050',
        'idInstitucion' => 'required|exists:institucions,id',
        'techos.*.monto' => 'required|numeric|min:0',
        'techos.*.idFuente' => 'required|exists:fuente,id',
    ];

    protected $messages = [
        'anio.required' => 'El año es obligatorio.',
        'anio.integer' => 'El año debe ser un número.',
        'anio.min' => 'El año debe ser mayor a 2020.',
        'anio.max' => 'El año debe ser menor a 2050.',
        'idInstitucion.required' => 'La institución es obligatoria.',
        'idInstitucion.exists' => 'La institución seleccionada no existe.',
        'techos.*.monto.required' => 'El monto es obligatorio.',
        'techos.*.monto.numeric' => 'El monto debe ser un número.',
        'techos.*.monto.min' => 'El monto debe ser mayor o igual a 0.',
        'techos.*.idFuente.required' => 'La fuente de financiamiento es obligatoria.',
        'techos.*.idFuente.exists' => 'La fuente de financiamiento seleccionada no existe.',
    ];

    public function mount()
    {
        $this->resetForm(); // Esto ya establece el año y los techos iniciales
    }

    private function initializeTechos()
    {
        // Inicializar con al menos un techo vacío
        if (empty($this->techos)) {
            $this->techos = [
                [
                    'monto' => '',
                    'idFuente' => ''
                ]
            ];
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFiltroAnio()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }



    public function render()
    {
        // Obtener institución y UE del usuario autenticado
        $user = auth()->user();
        $userInstitucionId = $user->empleado?->unidadEjecutora?->idInstitucion;
        $userUE = $user->empleado?->idUnidadEjecutora;

        $poas = Poa::with(['institucion', 'unidadEjecutora', 'techoUes.grupoGasto', 'techoUes.fuente'])
            // Filtrar por institución del usuario
            ->when($userInstitucionId, function ($query) use ($userInstitucionId) {
                $query->where('idInstitucion', $userInstitucionId);
            })
            // Filtrar por UE del usuario si tiene una asignada
            // Mostrar POAs sin UE (nacionales) O POAs de su UE O POAs con techos asignados a su UE
            ->when($userUE, function ($query) use ($userUE) {
                $query->where(function ($q) use ($userUE) {
                    $q->whereNull('idUE') // POAs nacionales sin UE específica
                      ->orWhere('idUE', $userUE) // POAs de su UE
                      ->orWhereHas('techoUes', function ($subQ) use ($userUE) {
                          $subQ->where('idUE', $userUE); // POAs con techos asignados a su UE
                      });
                });
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('anio', 'like', '%' . $this->search . '%')
                      ->orWhereHas('institucion', function ($q) {
                          $q->where('nombre', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('unidadEjecutora', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filtroAnio !== 'todos', function ($query) {
                $query->where('anio', $this->filtroAnio);
            })
            ->withCount('poaDeptos')
            ->orderBy($this->sortField, $this->sortDirection)
             ->paginate(12);

        // Calcular progreso de departamentos para cada POA
        foreach ($poas as $poa) {
            $poa->progreso_departamentos = $this->calcularProgresoDepartamentos($poa);
        }

        // Filtrar instituciones, grupos y fuentes por la institución del usuario
        $instituciones = $userInstitucionId 
            ? Institucion::where('id', $userInstitucionId)->orderBy('nombre')->get()
            : Institucion::orderBy('nombre')->get();
            
        $grupoGastos = GrupoGasto::orderBy('nombre')->get();
        $fuentes = Fuente::orderBy('nombre')->get();
        
        // Obtener años únicos para el filtro (solo de la institución del usuario)
        $anios = Poa::select('anio')
            ->when($userInstitucionId, function ($query) use ($userInstitucionId) {
                $query->where('idInstitucion', $userInstitucionId);
            })
            ->distinct()
            ->whereNotNull('anio')
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        return view('livewire.consola.asignacion-presu-nacional', [
            'poas' => $poas,
            'instituciones' => $instituciones,
            'grupoGastos' => $grupoGastos,
            'fuentes' => $fuentes,
            'anios' => $anios
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->resetValidation();
        $this->showModal = true;
        $this->initializeTechos(); // Inicializar techos para POA nacional
    }

    public function edit($id)
    {
        $poa = Poa::findOrFail($id);
        
        // Validar que el POA no sea de un año anterior
        $anioActual = now()->year;
        if ($poa->anio < $anioActual) {
            session()->flash('error', "No se puede editar un POA de años anteriores. El POA {$poa->anio} ya no puede ser modificado.");
            return;
        }
        
        // Verificar plazo de asignación nacional
        $this->verificarPlazo($poa);
        
        $this->poaId = $poa->id;
        $this->name = $poa->name;
        $this->anio = $poa->anio;
        $this->idInstitucion = $poa->idInstitucion;
        $this->activo = $poa->activo ?? true; // Cargar estado activo
        
        // Cargar techos globales del POA nacional (sin UE específica)
        $techosGlobales = TechoUe::where('idPoa', $poa->id)
                                 ->whereNull('idUE') // Solo techos globales
                                 ->get();
        
        if ($techosGlobales->count() > 0) {
            $this->techos = $techosGlobales->map(function($techo) {
                return [
                    'id' => $techo->id,
                    'monto' => $techo->monto,
                    'idFuente' => $techo->idFuente
                ];
            })->toArray();
        } else {
            $this->initializeTechos();
        }
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    private function verificarPlazo($poa)
    {
        // El plazo de asignación_nacional solo aplica para asignar a las UEs, 
        // no para asignar presupuesto de fuentes al POA
        // Por lo tanto, siempre permitimos la edición del POA
        $this->puedeAsignarPresupuestoNacional = true;
        $this->diasRestantes = null;
        $this->mensajePlazo = '';
    }

    public function save()
    {
        // El plazo de asignación_nacional solo aplica para asignar a las UEs,
        // no para asignar presupuesto de fuentes al POA
        // Por lo tanto, no validamos plazos aquí

        try {
            // Log para debugging
            \Log::info('Iniciando save de POA', [
                'name' => $this->name,
                'anio' => $this->anio,
                'idInstitucion' => $this->idInstitucion,
                'techos' => $this->techos,
                'isEditing' => $this->isEditing
            ]);

            // Validación específica para creación: año debe ser actual o futuro
            if (!$this->isEditing) {
                $anioActual = now()->year;
                if ($this->anio < $anioActual) {
                    session()->flash('error', "No se pueden crear POAs con años anteriores. El año debe ser {$anioActual} o posterior.");
                    return;
                }

                // Validar que no exista otro POA con el mismo año
                $poaExistente = Poa::where('anio', $this->anio)->first();
                if ($poaExistente) {
                    session()->flash('error', "Ya existe un POA para el año {$this->anio}. No se puede crear un POA duplicado.");
                    return;
                }
            }

            // Validar campos básicos
            $this->validate([
                'name' => 'required|string|max:255',
                'anio' => 'required|integer|min:2020|max:2050',
                'idInstitucion' => 'required|exists:institucions,id',
            ]);

            \Log::info('Validación básica pasada');

            // Validar techos solo si tienen datos
            $techosConDatos = collect($this->techos)->filter(function($techo) {
                return !empty($techo['monto']) || !empty($techo['idFuente']);
            });

            \Log::info('Techos con datos:', ['count' => $techosConDatos->count()]);

            if ($techosConDatos->count() > 0) {
                $this->validate([
                    'techos.*.monto' => 'required|numeric|min:0',
                    'techos.*.idFuente' => 'required|exists:fuente,id',
                ]);
                \Log::info('Validación de techos pasada');
            }
        } catch (\Exception $e) {
            \Log::error('Error en save:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
            return;
        }

        try {
            if ($this->isEditing) {
                \Log::info('Editando POA existente');
                $poa = Poa::findOrFail($this->poaId);
                $poa->update([
                    'name' => $this->name,
                    'anio' => $this->anio,
                    'idInstitucion' => $this->idInstitucion,
                    'activo' => $this->activo, // Actualizar estado activo
                ]);
                
                // Actualizar techos globales del POA nacional
                $this->actualizarTechosGlobales($poa);
                
                session()->flash('message', 'POA Nacional actualizado correctamente.');
                \Log::info('POA actualizado exitosamente', ['id' => $poa->id]);
            } else {
                \Log::info('Creando nuevo POA');
                // Crear POA nacional
                $poa = Poa::create([
                    'name' => $this->name,
                    'anio' => $this->anio,
                    'idInstitucion' => $this->idInstitucion,
                    'idUE' => null, // POA nacional no tiene UE específica
                    'activo' => true, // Por defecto activo al crear
                ]);
                
                \Log::info('POA creado exitosamente', ['id' => $poa->id]);
                
                // Crear techos globales por fuente
                $this->crearTechosGlobales($poa);
                
                session()->flash('message', 'POA Nacional creado correctamente con presupuesto global. Ahora puede distribuir a las unidades ejecutoras.');
            }

            $this->closeModal();
            \Log::info('Save completado exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al guardar POA:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Error al guardar el POA: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $poa = Poa::findOrFail($id);
        
        // Validar que el POA no sea de un año anterior
        $anioActual = now()->year;
        if ($poa->anio < $anioActual) {
            session()->flash('error', "No se puede eliminar un POA de años anteriores. El POA {$poa->anio} ya no puede ser modificado.");
            return;
        }
        
        $this->poaToDelete = $poa;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->poaToDelete) {
            // Verificar si tiene departamentos asociados
            if ($this->poaToDelete->poaDeptos()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el POA porque tiene departamentos asociados.');
                $this->closeDeleteModal();
                return;
            }

            $this->poaToDelete->delete();
            session()->flash('message', 'POA eliminado correctamente.');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->poaToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        // Solo limpiar errores, mantener advertencias para que el usuario las vea
        session()->forget('error');
    }

    /**
     * Crear techos globales para POA nacional (sin UE específica)
     */
    private function crearTechosGlobales($poa)
    {
        foreach ($this->techos as $techo) {
            if (!empty($techo['monto']) && $techo['monto'] > 0 && !empty($techo['idFuente'])) {
                TechoUe::create([
                    'idPoa' => $poa->id,
                    'idUE' => null, // Techo global, sin UE específica
                    'idGrupo' => null,
                    'idFuente' => $techo['idFuente'],
                    'monto' => $techo['monto'],
                ]);
            }
        }
    }

    /**
     * Actualizar techos globales para POA nacional
     */
    private function actualizarTechosGlobales($poa)
    {
        // Eliminar techos globales existentes
        TechoUe::where('idPoa', $poa->id)
               ->whereNull('idUE') // Solo techos globales
               ->delete();

        // Crear nuevos techos globales
        $this->crearTechosGlobales($poa);
    }

    private function resetForm()
    {
        $this->poaId = null;
        $this->name = '';
        $this->anio = date('Y');
        $this->idInstitucion = '';
        $this->activo = true; // Por defecto activo
        $this->techos = []; // Limpia completamente el array de techos
        $this->initializeTechos(); // Inicializa con un techo vacío
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filtroAnio = 'todos';
        $this->resetPage();
    }

    public function addTecho()
    {
        // Limitar a un máximo de 3 techos presupuestarios
        if (count($this->techos) < 3) {
            $this->techos[] = [
                'monto' => '',
                'idFuente' => ''
            ];
        } else {
            session()->flash('error', 'No se pueden agregar más de 3 techos presupuestarios.');
        }
    }

    public function removeTecho($index)
    {
        if (count($this->techos) > 1) {
            $techoAEliminar = $this->techos[$index] ?? null;
            
            // Si estamos editando y el techo tiene ID (es decir, ya existe en la BD)
            if ($this->isEditing && isset($techoAEliminar['id'])) {
                // Verificar si tiene TechoDepto asociados
                $tieneTechoDeptos = TechoDepto::where('idTechoUE', $techoAEliminar['id'])->exists();
                
                if ($tieneTechoDeptos) {
                    // Obtener el nombre de la fuente para el mensaje
                    $techoUe = TechoUe::with('fuente')->find($techoAEliminar['id']);
                    $nombreFuente = $techoUe->fuente->nombre ?? 'Sin nombre';
                    
                    // Mostrar advertencia y no eliminar
                    session()->flash('warning', 
                        'No se puede eliminar el techo de la fuente "' . $nombreFuente . 
                        '" porque tiene asignaciones departamentales. Primero elimine las asignaciones departamentales.'
                    );
                    return; // No eliminar el techo
                }
            }
            
            // Si no tiene asignaciones o es un techo nuevo, proceder con la eliminación
            unset($this->techos[$index]);
            $this->techos = array_values($this->techos); // Reindexar
        }
    }
    
    /**
     * Calcula el total de los techos presupuestarios.
     * 
     * @return float
     */
    public function getTotalTechosProperty()
    {
        return array_reduce($this->techos, function ($carry, $techo) {
            $monto = !empty($techo['monto']) ? (float)$techo['monto'] : 0;
            return $carry + $monto;
        }, 0);
    }

    /**
     * Obtiene las fuentes disponibles para un techo específico
     * excluyendo las que ya están seleccionadas en otros techos
     * 
     * @param int $currentIndex
     * @return array
     */
    public function getFuentesDisponibles($currentIndex)
    {
        // Todas las fuentes de financiamiento
        $allFuentes = Fuente::orderBy('nombre')->get();
        
        // Fuentes ya seleccionadas en otros techos
        $fuentesSeleccionadas = collect($this->techos)
            ->filter(function ($techo, $index) use ($currentIndex) {
                return $index != $currentIndex && !empty($techo['idFuente']);
            })
            ->pluck('idFuente')
            ->toArray();
        
        // Fuente actualmente seleccionada en este techo
        $currentFuente = $this->techos[$currentIndex]['idFuente'] ?? null;
        
        // Agregar la opción "Seleccione una fuente"
        $options = [['value' => '', 'text' => 'Seleccione una fuente']];
        
        // Filtrar las fuentes para mostrar solo las no seleccionadas o la que ya está seleccionada en este techo
        foreach ($allFuentes as $fuente) {
            if (!in_array($fuente->id, $fuentesSeleccionadas) || $fuente->id == $currentFuente) {
                $options[] = ['value' => $fuente->id, 'text' => $fuente->identificador . ' - ' . $fuente->nombre];
            }
        }
        
        return $options;
    }

    /**
     * Obtiene el monto mínimo permitido para un techo en edición
     * No puede ser menor al último monto asignado a este techo
     * 
     * @param int $techoId
     * @return float
     */
    public function getMontoMinimo($techoId)
    {
        if (!$this->isEditing || !$techoId) {
            return 0;
        }
        
        // Obtener el monto actual del techo específico
        $techoUe = TechoUe::find($techoId);
        
        if (!$techoUe) {
            return 0;
        }
        
        return (float) $techoUe->monto;
    }
    
    /**
     * Valida que los techos en edición no tengan montos menores al mínimo permitido
     * 
     * @return bool
     */
    public function validateTechosEdicion()
    {
        if (!$this->isEditing) {
            return true;
        }
        
        $errores = [];
        
        foreach ($this->techos as $index => $techo) {
            $techoId = $techo['id'] ?? null;
            if ($techoId) {
                $montoMinimo = $this->getMontoMinimo($techoId);
                $montoActual = (float) ($techo['monto'] ?? 0);
                
                if ($montoActual < $montoMinimo) {
                    $errores[] = "El techo " . ($index + 1) . " no puede tener un monto menor a " . number_format($montoMinimo, 2) . " (último monto asignado)";
                }
            }
        }
        
        if (!empty($errores)) {
            session()->flash('error', implode('<br>', $errores));
            return false;
        }
        
        return true;
    }

    /**
     * Calcula el progreso de asignaciones a unidades ejecutoras para un POA nacional
     * Mide: porcentaje de UEs con presupuesto asignado vs total de UEs
     *
     * @param Poa $poa
     * @return array
     */
    private function calcularProgresoDepartamentos($poa)
    {
        // Para POA nacional, calculamos el progreso de UEs en lugar de departamentos
        return $this->calcularProgresoUnidadesEjecutoras($poa);
    }

    /**
     * Calcula el progreso de asignaciones a unidades ejecutoras para un POA nacional
     * Mide: porcentaje de UEs con presupuesto asignado vs total de UEs
     *
     * @param Poa $poa
     * @return array
     */
    private function calcularProgresoUnidadesEjecutoras($poa)
    {
        // Obtener el total de unidades ejecutoras
        $totalUEs = UnidadEjecutora::count();
        
        // Si no hay UEs, el progreso es 0
        if ($totalUEs == 0) {
            return [
                'porcentaje' => 0,
                'departamentos_con_presupuesto' => 0, // Mantener nombre para compatibilidad
                'total_departamentos' => 0, // Mantener nombre para compatibilidad
                'ues_con_presupuesto' => 0,
                'total_ues' => 0,
                'color' => 'bg-red-500'
            ];
        }
        
        // Contar UEs con presupuesto asignado (techos con monto > 0)
        $uesConPresupuesto = TechoUe::where('idPoa', $poa->id)
            ->where('monto', '>', 0)
            ->distinct('idUE')
            ->count('idUE');
        
        // Calcular porcentaje
        $porcentaje = round(($uesConPresupuesto / $totalUEs) * 100);
        
        // Determinar color según el porcentaje
        $color = 'bg-red-500'; // Rojo por defecto
        if ($porcentaje >= 85) {
            $color = 'bg-green-500'; // Verde para 85% o más
        } elseif ($porcentaje >= 30) {
            $color = 'bg-yellow-500'; // Amarillo para 30-84%
        }
        
        return [
            'porcentaje' => $porcentaje,
            'departamentos_con_presupuesto' => $uesConPresupuesto, // Mantener nombre para compatibilidad
            'total_departamentos' => $totalUEs, // Mantener nombre para compatibilidad
            'ues_con_presupuesto' => $uesConPresupuesto,
            'total_ues' => $totalUEs,
            'color' => $color
        ];
    }

    /**
     * Navega al CRUD de TechoUE Nacional para el POA seleccionado
     * Aquí se gestionan las asignaciones a nivel de unidades ejecutoras
     *
     * @param int $poaId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function gestionarTechoUeNacional($poaId)
    {
        // Redirigir a la gestión de techos a nivel nacional (todas las UEs)
        return redirect()->route('techonacional', [
            'idPoa' => $poaId
        ]);
    }

    /**
     * Navega al CRUD de TechoDepto para el POA seleccionado
     *
     * @param int $poaId
     * @param int $idUE
     * @return \Illuminate\Http\RedirectResponse
     */
    public function gestionarTechoDepto($poaId, $idUE)
    {
        // Usar la ruta con estructura de 3 partes para mejor breadcrumb
        return redirect()->route('techodeptos', [
            'idPoa' => $poaId,
            'idUE' => $idUE
        ]);
    }
    
    /**
     * Actualiza los techos preservando las relaciones existentes con TechoDepto
     * 
     * @param Poa $poa
     * @return bool Retorna true si hubo advertencias
     */
    private function updateTechosPreservandoRelaciones($poa)
    {
        // Obtener techos existentes
        $techosExistentes = TechoUe::where('idPoa', $poa->id)->get()->keyBy('id');
        
        // Procesar techos del formulario
        $techosFormulario = collect($this->techos)->filter(function($techo) {
            return !empty($techo['monto']) && !empty($techo['idFuente']);
        });
        
        $techosActualizados = collect();
        
        // Actualizar o crear techos según corresponda
        foreach ($techosFormulario as $index => $techoData) {
            $techoId = $techoData['id'] ?? null;
            
            if ($techoId && $techosExistentes->has($techoId)) {
                // Actualizar techo existente
                $techoExistente = $techosExistentes->get($techoId);
                $techoExistente->update([
                    'monto' => $techoData['monto'],
                    'idFuente' => $techoData['idFuente'],
                ]);
                $techosActualizados->put($techoId, $techoExistente);
            } else {
                // Crear nuevo techo
                $nuevoTecho = TechoUe::create([
                    'monto' => $techoData['monto'],
                    'idUE' => null,
                    'idPoa' => $poa->id,
                    'idGrupo' => null,
                    'idFuente' => $techoData['idFuente'],
                ]);
                $techosActualizados->put($nuevoTecho->id, $nuevoTecho);
            }
        }
        
        // Eliminar techos que ya no están en el formulario
        // Solo eliminar si no tienen asignaciones departamentales
        $idsAEliminar = $techosExistentes->keys()->diff($techosActualizados->keys());
        foreach ($idsAEliminar as $id) {
            $techoAEliminar = $techosExistentes->get($id);
            
            // Verificar si tiene TechoDepto asociados
            $tieneTechoDeptos = TechoDepto::where('idTechoUE', $id)->exists();
            
            if (!$tieneTechoDeptos) {
                // Solo eliminar si no tiene asignaciones departamentales
                $techoAEliminar->delete();
            }
            // Si tiene asignaciones, no eliminar (la advertencia ya se mostró en removeTecho)
        }
        
        return false; // Ya no manejamos advertencias aquí
    }
    
    /**
     * Crea asignaciones automáticas a todas las unidades ejecutoras
     * Similar a como se asigna a departamentos en la asignación regular
     * 
     * @param Poa $poa
     * @return void
     */
    private function crearAsignacionesUnidadesEjecutoras($poa)
    {
        // Obtener todas las unidades ejecutoras activas
        $unidadesEjecutoras = UnidadEjecutora::all();
        
        // Obtener los techos creados para este POA
        $techosCreados = TechoUe::where('idPoa', $poa->id)->get();
        
        // Para cada unidad ejecutora, crear asignaciones basadas en los techos del POA
        foreach ($unidadesEjecutoras as $ue) {
            
            // Crear techos para esta UE basados en los techos del POA original
            foreach ($techosCreados as $techoOriginal) {
                TechoUe::create([
                    'monto' => 0, // Inicialmente sin monto asignado
                    'idUE' => $ue->id,
                    'idPoa' => $poa->id,
                    'idGrupo' => $techoOriginal->idGrupo,
                    'idFuente' => $techoOriginal->idFuente,
                ]);
            }
        }
    }

    /**
     * Actualiza las asignaciones a todas las unidades ejecutoras cuando se edita un POA
     * 
     * @param Poa $poa
     * @return void
     */
    private function actualizarAsignacionesUnidadesEjecutoras($poa)
    {
        // Obtener todas las unidades ejecutoras
        $unidadesEjecutoras = UnidadEjecutora::all();
        
        // Obtener los techos actualizados del POA principal
        $techosActualizados = TechoUe::where('idPoa', $poa->id)
            ->whereNull('idUE')
            ->get();
        
        // Para cada unidad ejecutora
        foreach ($unidadesEjecutoras as $ue) {
            
            // Obtener techos existentes de esta UE para este POA
            $techosExistentesUE = TechoUe::where('idPoa', $poa->id)
                ->where('idUE', $ue->id)
                ->get()
                ->keyBy('idFuente');
            
            // Sincronizar con los techos del POA principal
            foreach ($techosActualizados as $techoActualizado) {
                if ($techosExistentesUE->has($techoActualizado->idFuente)) {
                    // Ya existe, mantener el monto actual pero actualizar otros campos si es necesario
                    $techoExistente = $techosExistentesUE->get($techoActualizado->idFuente);
                    $techoExistente->update([
                        'idGrupo' => $techoActualizado->idGrupo,
                        // Mantener el monto existente
                    ]);
                } else {
                    // No existe, crear nuevo techo para esta UE
                    TechoUe::create([
                        'monto' => 0, // Inicialmente sin monto
                        'idUE' => $ue->id,
                        'idPoa' => $poa->id,
                        'idGrupo' => $techoActualizado->idGrupo,
                        'idFuente' => $techoActualizado->idFuente,
                    ]);
                }
            }
            
            // Eliminar techos que ya no existen en el POA principal
            $fuentesActualizadas = $techosActualizados->pluck('idFuente')->toArray();
            $techosAEliminar = $techosExistentesUE->filter(function($techo) use ($fuentesActualizadas) {
                return !in_array($techo->idFuente, $fuentesActualizadas);
            });
            
            foreach ($techosAEliminar as $techoAEliminar) {
                // Solo eliminar si no tiene asignaciones departamentales
                if ($techoAEliminar->techoDeptos()->count() == 0) {
                    $techoAEliminar->delete();
                }
            }
        }
    }

    /**
     * Limpia todos los mensajes de sesión
     */
    public function clearMessages()
    {
        session()->forget(['message', 'error', 'warning']);
    }
}