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

class AsignacionPresupuestaria extends Component
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
    public $idUE = '';
    
    // Propiedades para múltiples Techos UE
    public $techos = [];

    protected $rules = [
        'anio' => 'required|integer|min:2020|max:2050',
        'idInstitucion' => 'required|exists:institucions,id',
        'idUE' => 'required|exists:unidad_ejecutora,id',
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
        'idUE.required' => 'La unidad ejecutora es obligatoria.',
        'idUE.exists' => 'La unidad ejecutora seleccionada no existe.',
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
        $poas = Poa::with(['institucion', 'unidadEjecutora', 'techoUes.grupoGasto', 'techoUes.fuente'])
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

        $instituciones = Institucion::orderBy('nombre')->get();
        $unidadesEjecutoras = UnidadEjecutora::orderBy('name')->get();
        $grupoGastos = GrupoGasto::orderBy('nombre')->get();
        $fuentes = Fuente::orderBy('nombre')->get();
        
        // Obtener años únicos para el filtro
        $anios = Poa::select('anio')
            ->distinct()
            ->whereNotNull('anio')
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        return view('livewire.consola.asignacion-presupuestaria', [
            'poas' => $poas,
            'instituciones' => $instituciones,
            'unidadesEjecutoras' => $unidadesEjecutoras,
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
    }

    public function edit($id)
    {
        $poa = Poa::findOrFail($id);
        $this->poaId = $poa->id;
        $this->name = $poa->name;
        $this->anio = $poa->anio;
        $this->idInstitucion = $poa->idInstitucion;
        $this->idUE = $poa->idUE;
        
        // Cargar múltiples techos si existen
        $techosExistentes = TechoUe::where('idPoa', $poa->id)->get();
        if ($techosExistentes->count() > 0) {
            $this->techos = $techosExistentes->map(function($techo) {
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

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $poa = Poa::findOrFail($this->poaId);
            $poa->update([
                'name' => $this->name,
                'anio' => $this->anio,
                'idInstitucion' => $this->idInstitucion,
                'idUE' => $this->idUE,
            ]);
            
            // Actualizar techos preservando las relaciones existentes
            $this->updateTechosPreservandoRelaciones($poa);
            
            session()->flash('message', 'POA actualizado correctamente.');
        } else {
            $poa = Poa::create([
                'name' => $this->name,
                'anio' => $this->anio,
                'idInstitucion' => $this->idInstitucion,
                'idUE' => $this->idUE,
            ]);
            
            // Crear múltiples TechoUe
            foreach ($this->techos as $techo) {
                if (!empty($techo['monto']) && !empty($techo['idFuente'])) {
                    TechoUe::create([
                        'monto' => $techo['monto'],
                        'idUE' => $this->idUE,
                        'idPoa' => $poa->id,
                        'idGrupo' => null, // Campo mantenido como null ya que existe en la base de datos
                        'idFuente' => $techo['idFuente'],
                    ]);
                }
            }
            
            session()->flash('message', 'POA creado correctamente.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->poaToDelete = Poa::findOrFail($id);
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
        session()->forget(['error', 'warning']); // Limpiar mensajes de error y advertencia
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        // Solo limpiar errores, mantener advertencias para que el usuario las vea
        session()->forget('error');
    }

    private function resetForm()
    {
        $this->poaId = null;
        $this->name = '';
        $this->anio = date('Y');
        $this->idInstitucion = '';
        $this->idUE = '';
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
     * Calcula el progreso de departamentos para un POA específico
     * Mide: porcentaje de departamentos con presupuesto asignado vs total de departamentos de la UE
     *
     * @param Poa $poa
     * @return array
     */
    private function calcularProgresoDepartamentos($poa)
    {
        // Obtener el total de departamentos de la UE
        $totalDepartamentos = $poa->unidadEjecutora->departamentos()->count();
        
        // Si no hay departamentos, el progreso es 0
        if ($totalDepartamentos == 0) {
            return [
                'porcentaje' => 0,
                'departamentos_con_presupuesto' => 0,
                'total_departamentos' => 0,
                'color' => 'bg-red-500'
            ];
        }
        
        // Contar departamentos con presupuesto asignado
        $departamentosConPresupuesto = $poa->techoDeptos()
            ->where('monto', '>', 0)
            ->distinct('idDepartamento')
            ->count('idDepartamento');
        
        // Calcular porcentaje
        $porcentaje = round(($departamentosConPresupuesto / $totalDepartamentos) * 100);
        
        // Determinar color según el porcentaje
        $color = 'bg-red-500'; // Rojo por defecto
        if ($porcentaje >= 65) {
            $color = 'bg-green-500'; // Verde para 65% o más
        } elseif ($porcentaje >= 30) {
            $color = 'bg-yellow-500'; // Amarillo para 30-64%
        }
        
        return [
            'porcentaje' => $porcentaje,
            'departamentos_con_presupuesto' => $departamentosConPresupuesto,
            'total_departamentos' => $totalDepartamentos,
            'color' => $color
        ];
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
        // Usamos query string de forma explícita
        return redirect()->to(route('techo-deptos') . "?idPoa={$poaId}&idUE={$idUE}");
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
                    'idUE' => $this->idUE,
                ]);
                $techosActualizados->put($techoId, $techoExistente);
            } else {
                // Crear nuevo techo
                $nuevoTecho = TechoUe::create([
                    'monto' => $techoData['monto'],
                    'idUE' => $this->idUE,
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
     * Limpia todos los mensajes de sesión
     */
    public function clearMessages()
    {
        session()->forget(['message', 'error', 'warning']);
    }
}