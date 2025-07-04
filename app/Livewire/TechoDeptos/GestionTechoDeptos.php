<?php

namespace App\Livewire\TechoDeptos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Poa;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Departamento\Departamento;
use App\Models\TechoUes\TechoUe;
use App\Models\TechoUes\TechoDepto;
use App\Models\Poa\PoaDepto;
use App\Models\GrupoGastos\GrupoGasto;
use Illuminate\Pagination\LengthAwarePaginator;

class GestionTechoDeptos extends Component
{
    use WithPagination;

    public $idPoa;
    public $idUE;
    public $poa;
    public $unidadEjecutora;
    public $search = ''; // Buscador general (se mantiene para compatibilidad)
    public $searchConTecho = ''; // Buscador específico para departamentos con techo
    public $searchSinTecho = ''; // Buscador específico para departamentos sin techo
    public $activeTab = 'resumen'; // Nueva propiedad para el tab activo
    public $showModal = false;
    public $showDeleteModal = false;
    public $techoDeptoToDelete = null;
    public $isEditing = false;
    public $techoDeptoId;

    // Propiedades del formulario
    public $idDepartamento = '';
    public $montosPorFuente = []; // Array para almacenar montos por fuente
    public $techoDeptoEditando = null; // Para edición

    // Listados para los selects
    public $departamentos = [];
    public $techoUes = [];
    
    protected $rules = [
        'idDepartamento' => 'required|exists:departamentos,id',
    ];

    protected $messages = [
        'idDepartamento.required' => 'El departamento es obligatorio.',
        'idDepartamento.exists' => 'El departamento seleccionado no existe.',
    ];

    // Definimos explícitamente cómo queremos que se procesen los parámetros en la URL
    protected $queryString = [
        'idPoa' => ['except' => ''],
        'idUE' => ['except' => '']
    ];

    public function mount()
    {
        // No recibimos los parámetros directamente en mount ya que los manejamos vía $queryString
        
        if ($this->idPoa && $this->idUE) {
            $this->poa = Poa::findOrFail($this->idPoa);
            $this->unidadEjecutora = UnidadEjecutora::findOrFail($this->idUE);
            
            // Cargar listas para los selects
            $this->loadDepartamentos();
            $this->loadTechoUes();
            
            // Inicializar montos por fuente
            $this->initializeMontosPorFuente();
        } else {
            session()->flash('error', 'Se requiere un POA y una Unidad Ejecutora para gestionar los techos por departamento.');
            return redirect()->route('asignacionpresupuestaria');
        }
    }
    
    private function initializeMontosPorFuente()
    {
        // Inicializar array si no existe
        if (!is_array($this->montosPorFuente)) {
            $this->montosPorFuente = [];
        }
        
        // Asegurar que todas las fuentes estén presentes
        foreach ($this->techoUes as $techoUe) {
            if (!array_key_exists($techoUe->id, $this->montosPorFuente)) {
                $this->montosPorFuente[$techoUe->id] = 0.0;
            }
        }
    }
    
    private function loadDepartamentos()
    {
        $this->departamentos = Departamento::where('idUnidadEjecutora', $this->idUE)->orderBy('name')->get();
    }
    
    private function loadTechoUes()
    {
        $this->techoUes = TechoUe::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->with('fuente')
            ->get();
            
        // Solo inicializar montos por fuente si no estamos en modo edición
        if (!$this->isEditing) {
            $this->initializeMontosPorFuente();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchConTecho()
    {
        $this->resetPage();
    }

    public function updatingSearchSinTecho()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        
        // Limpiar los buscadores cuando cambias de pestaña
        $this->clearSearches();
    }

    public function clearSearches()
    {
        $this->searchConTecho = '';
        $this->searchSinTecho = '';
        $this->resetPage();
    }

    public function render()
    {
        // Obtener todos los departamentos de la UE
        $todosDepartamentosQuery = Departamento::where('idUnidadEjecutora', $this->idUE);
        
        // Aplicar filtro de búsqueda para departamentos sin techo si existe
        if ($this->searchSinTecho && $this->activeTab === 'sin-asignar') {
            $todosDepartamentosQuery->where('name', 'like', '%' . $this->searchSinTecho . '%');
        }
        
        $todosDepartamentos = $todosDepartamentosQuery->orderBy('name')->get();

        // Obtener IDs de departamentos que ya tienen techos asignados
        $departamentosConTechoIds = TechoDepto::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->pluck('idDepartamento')
            ->unique();

        // Separar departamentos con y sin techos
        $departamentosSinTecho = $todosDepartamentos->whereNotIn('id', $departamentosConTechoIds);
        $departamentosConTechoData = $todosDepartamentos->whereIn('id', $departamentosConTechoIds);

        // Obtener techos departamentales con relaciones (con buscador específico)
        $techoDeptos = TechoDepto::with(['departamento', 'techoUE.fuente'])
            ->where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->when($this->searchConTecho && $this->activeTab === 'con-asignacion', function ($query) {
                $query->whereHas('departamento', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchConTecho . '%');
                });
            })
            ->orderBy('idDepartamento')
            ->paginate(10);

        // Agrupar techos por departamento usando la colección de la página actual
        $techosAgrupadosPorDepto = $techoDeptos->getCollection()->groupBy('idDepartamento');

        // Calcular resumen del presupuesto
        $resumenPresupuesto = $this->getResumenPresupuesto();

        return view('livewire.techo-deptos.gestion-techo-deptos', [
            'techoDeptos' => $techoDeptos,
            'techosAgrupadosPorDepto' => $techosAgrupadosPorDepto,
            'departamentosSinTecho' => $departamentosSinTecho,
            'departamentosConTecho' => $departamentosConTechoData,
            'resumenPresupuesto' => $resumenPresupuesto,
        ])->layout('layouts.app');
    }

    private function getResumenPresupuesto()
    {
        // Obtener todos los techos UE para esta UE y POA
        $techosUE = TechoUe::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->with('fuente')
            ->get();

        $resumen = [];
        foreach ($techosUE as $techoUE) {
            // Calcular el monto total asignado a departamentos desde este techo UE
            $montoAsignado = TechoDepto::where('idPoa', $this->idPoa)
                ->where('idUE', $this->idUE)
                ->where('idTechoUE', $techoUE->id)
                ->sum('monto');

            $montoDisponible = $techoUE->monto - $montoAsignado;
            $porcentajeUsado = $techoUE->monto > 0 ? ($montoAsignado / $techoUE->monto) * 100 : 0;

            $resumen[] = [
                'fuente' => $techoUE->fuente->nombre ?? 'Sin fuente',
                'identificador' => $techoUE->fuente->identificador ?? 'Sin identificador',
                'montoTotal' => $techoUE->monto,
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

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->initializeMontosPorFuente(); // Asegurar inicialización
        $this->showModal = true;
    }

    public function createForDepartment($departamentoId)
    {
        $this->resetForm();
        $this->idDepartamento = $departamentoId;
        $this->isEditing = false;
        $this->initializeMontosPorFuente(); // Asegurar inicialización
        $this->showModal = true;
    }

    public function edit($id)
    {
        $techoDepto = TechoDepto::findOrFail($id);
        $this->editDepartment($techoDepto->idDepartamento);
    }

    public function editDepartment($departamentoId)
    {
        $this->resetForm();
        $this->idDepartamento = $departamentoId;
        $this->isEditing = true;
        
        // Inicializar montos por fuente con ceros
        $this->initializeMontosPorFuente();
        
        // Cargar montos existentes para este departamento
        $techosExistentes = TechoDepto::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->where('idDepartamento', $departamentoId)
            ->get();
            
        // Sobreescribir solo los montos que existen en la BD
        foreach ($techosExistentes as $techo) {
            $this->montosPorFuente[$techo->idTechoUE] = floatval($techo->monto);
        }
        
        $this->showModal = true;
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
            'idDepartamento' => 'required|exists:departamentos,id',
        ];
        
        // Validar cada monto por fuente
        foreach ($this->montosPorFuente as $idTechoUE => $monto) {
            $montoFloat = floatval($monto);
            if ($montoFloat > 0) {
                $rules["montosPorFuente.{$idTechoUE}"] = 'required|numeric|min:0.01';
                
                // En modo edición, validar que el monto no sea menor al mínimo permitido
                if ($this->isEditing) {
                    $montoMinimo = $this->getMontoMinimoPermitido($idTechoUE);
                    if ($montoFloat < $montoMinimo) {
                        $techoUE = $this->techoUes->firstWhere('id', $idTechoUE);
                        $fuenteNombre = $techoUE->fuente->nombre ?? 'la fuente';
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
        foreach ($this->montosPorFuente as $idTechoUE => $monto) {
            $montoFloat = floatval($monto);
            if ($montoFloat > 0) {
                $disponibilidadValida = $this->validarDisponibilidadPresupuesto($montoFloat, $idTechoUE);
                if (!$disponibilidadValida) {
                    return; // El error ya se muestra en el método de validación
                }
            }
        }

        // Crear o encontrar el PoaDepto para esta combinación POA-Departamento
        $poaDepto = PoaDepto::firstOrCreate([
            'idPoa' => $this->idPoa,
            'idDepartamento' => $this->idDepartamento,
        ], [
            'isActive' => true,
        ]);

        if ($this->isEditing) {
            // Eliminar techos existentes para este departamento
            TechoDepto::where('idPoa', $this->idPoa)
                ->where('idUE', $this->idUE)
                ->where('idDepartamento', $this->idDepartamento)
                ->delete();
        }

        // Crear nuevos techos para cada fuente con monto > 0
        foreach ($this->montosPorFuente as $idTechoUE => $monto) {
            $montoFloat = floatval($monto);
            if ($montoFloat > 0) {
                TechoDepto::create([
                    'monto' => $montoFloat,
                    'idUE' => $this->idUE,
                    'idPoa' => $this->idPoa,
                    'idDepartamento' => $this->idDepartamento,
                    'idPoaDepto' => $poaDepto->id,
                    'idTechoUE' => $idTechoUE,
                ]);
            }
        }
        
        $message = $this->isEditing ? 'Techos departamentales actualizados correctamente.' : 'Techos departamentales creados correctamente.';
        session()->flash('message', $message);

        $this->closeModal();
    }

    public function getDisponibilidadFuente($idTechoUE)
    {
        // Calcular el monto ya asignado desde esta fuente (excluyendo el departamento actual si estamos editando)
        $montoAsignado = TechoDepto::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->where('idTechoUE', $idTechoUE)
            ->when($this->isEditing && $this->idDepartamento, function($query) {
                $query->where('idDepartamento', '!=', $this->idDepartamento);
            })
            ->sum('monto');

        $techoUE = $this->techoUes->firstWhere('id', $idTechoUE);
        if (!$techoUE) {
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
        $montoTotalTecho = floatval($techoUE->monto);
        $montoDisponible = $montoTotalTecho - $montoAsignado;
        $porcentajeUsado = $montoTotalTecho > 0 ? ($montoAsignado / $montoTotalTecho) * 100 : 0;
        $montoMinimo = floatval($this->getMontoMinimoPermitido($idTechoUE));

        return [
            'disponible' => $montoDisponible,
            'usado' => $montoAsignado,
            'porcentaje' => $porcentajeUsado,
            'minimo' => $montoMinimo,
            'total' => $montoTotalTecho
        ];
    }

    private function getMontoMinimoPermitido($idTechoUE)
    {
        if (!$this->isEditing || !$this->idDepartamento) {
            return 0.0;
        }

        // Obtener el monto actual asignado desde esta fuente para este departamento
        $montoActual = TechoDepto::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->where('idDepartamento', $this->idDepartamento)
            ->where('idTechoUE', $idTechoUE)
            ->sum('monto');

        return floatval($montoActual);
    }

    private function validarDisponibilidadPresupuesto($montoAValidar, $idTechoUE)
    {
        // Obtener el techo UE seleccionado
        $techoUE = TechoUe::find($idTechoUE);
        if (!$techoUE) {
            session()->flash('error', 'Techo UE no encontrado.');
            return false;
        }

        // Calcular el monto ya asignado desde este techo UE
        $montoAsignado = TechoDepto::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->where('idTechoUE', $idTechoUE);
            
        // Si estamos editando, excluir el monto actual de este departamento
        if ($this->isEditing) {
            $montoAsignado->where('idDepartamento', '!=', $this->idDepartamento);
        }
        
        $montoAsignado = floatval($montoAsignado->sum('monto'));
        $montoTotalTecho = floatval($techoUE->monto);
        $montoDisponible = $montoTotalTecho - $montoAsignado;
        $montoAValidarFloat = floatval($montoAValidar);

        if ($montoAValidarFloat > $montoDisponible) {
            session()->flash('error', 
                'El monto para ' . ($techoUE->fuente->nombre ?? 'la fuente') . ' excede el presupuesto disponible. ' .
                'Disponible: ' . number_format($montoDisponible, 2) . 
                ', Solicitado: ' . number_format($montoAValidarFloat, 2)
            );
            return false;
        }

        return true;
    }

    public function confirmDelete($id)
    {
        $techoDepto = TechoDepto::findOrFail($id);
        $this->techoDeptoToDelete = $techoDepto;
        $this->showDeleteModal = true;
    }
    
    public function confirmDeleteDepartment($departamentoId)
    {
        // Obtener todos los techos de este departamento
        $techosDepartamento = TechoDepto::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->where('idDepartamento', $departamentoId)
            ->with('departamento')
            ->get();
            
        if ($techosDepartamento->count() > 0) {
            $this->techoDeptoToDelete = $techosDepartamento->first(); // Para mostrar info del departamento
            $this->showDeleteModal = true;
        }
    }

    public function delete()
    {
        if ($this->techoDeptoToDelete) {
            // Eliminar todos los techos de este departamento
            TechoDepto::where('idPoa', $this->idPoa)
                ->where('idUE', $this->idUE)
                ->where('idDepartamento', $this->techoDeptoToDelete->idDepartamento)
                ->delete();
                
            session()->flash('message', 'Techos departamentales eliminados correctamente.');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->techoDeptoToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function resetForm()
    {
        $this->idDepartamento = '';
        $this->isEditing = false;
        $this->techoDeptoEditando = null;
        
        // Reinicializar montos por fuente sin sobrescribir valores existentes
        $this->montosPorFuente = [];
        $this->initializeMontosPorFuente();
    }

    public function backToPoa()
    {
        return redirect()->route('asignacionpresupuestaria');
    }
}
