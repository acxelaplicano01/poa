<?php

namespace App\Livewire\TechoDeptos;

use Livewire\Component;
use App\Models\Poa\Poa;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\TechoUes\TechoDepto;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class DetalleEstructura extends Component
{
    public $idPoa;
    public $idUE;
    public $estructura;
    public $poa;
    public $unidadEjecutora;
    public $detalleEstructura;
    public $activeTab = 'departamentos';

    protected $queryString = [
        'idPoa' => ['except' => ''],
        'idUE' => ['except' => ''],
        'estructura' => ['except' => '']
    ];

    public function mount($idPoa = null, $idUE = null, $estructura = null)
    {
        // Si los parámetros vienen desde la ruta, los usamos
        // Si no, Livewire los tomará automáticamente desde queryString
        if ($idPoa) $this->idPoa = $idPoa;
        if ($idUE) $this->idUE = $idUE;
        if ($estructura) $this->estructura = urldecode($estructura);
        
        // Validar que tenemos todos los parámetros necesarios
        if (!$this->idPoa || !$this->idUE || !$this->estructura) {
            abort(404, 'Parámetros requeridos faltantes: idPoa, idUE, estructura');
        }
        
        // Decodificar estructura si viene URL encoded
        $this->estructura = urldecode($this->estructura);
        
        // Cargar POA y Unidad Ejecutora
        $this->poa = Poa::findOrFail($this->idPoa);
        $this->unidadEjecutora = UnidadEjecutora::findOrFail($this->idUE);
        
        // Cargar el detalle de la estructura
        $this->loadDetalleEstructura();
    }

    // Este método se llama cuando alguna propiedad de queryString cambia
    public function updatedIdPoa()
    {
        $this->loadComponentData();
    }

    public function updatedIdUE()
    {
        $this->loadComponentData();
    }

    public function updatedEstructura()
    {
        $this->loadComponentData();
    }

    private function loadComponentData()
    {
        // Solo cargar si tenemos todos los parámetros
        if ($this->idPoa && $this->idUE && $this->estructura) {
            try {
                $this->poa = Poa::findOrFail($this->idPoa);
                $this->unidadEjecutora = UnidadEjecutora::findOrFail($this->idUE);
                $this->loadDetalleEstructura();
            } catch (\Exception $e) {
                // Manejar errores si los IDs no existen
                session()->flash('error', 'Error al cargar los datos: ' . $e->getMessage());
            }
        }
    }

    public function loadDetalleEstructura()
    {
        // Obtener todos los techos departamentales para esta estructura
        $techosConDepartamentos = TechoDepto::with(['departamento', 'techoUE.fuente'])
            ->where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->whereHas('departamento', function ($query) {
                if ($this->estructura === 'Sin Estructura') {
                    $query->whereNull('estructura');
                } else {
                    $query->where('estructura', $this->estructura);
                }
            })
            ->get();

        if ($techosConDepartamentos->isEmpty()) {
            abort(404, 'No se encontraron datos para esta estructura');
        }

        $departamentosUnicos = $techosConDepartamentos->pluck('departamento')->unique('id');
        $montoTotal = $techosConDepartamentos->sum('monto');
        $cantidadDepartamentos = $departamentosUnicos->count();
        $promedioMonto = $cantidadDepartamentos > 0 ? $montoTotal / $cantidadDepartamentos : 0;
        
        // Agrupar por fuente dentro de la estructura
        $fuentesUsadas = $techosConDepartamentos
            ->groupBy(function ($techo) {
                return $techo->techoUE->fuente->nombre ?? 'Sin Fuente';
            })
            ->map(function ($techosPorFuente, $nombreFuente) {
                return [
                    'nombre' => $nombreFuente,
                    'monto' => $techosPorFuente->sum('monto'),
                    'cantidad_asignaciones' => $techosPorFuente->count(),
                    'asignaciones' => $techosPorFuente->map(function ($techo) {
                        return [
                            'id' => $techo->id,
                            'departamento' => $techo->departamento->name,
                            'siglas' => $techo->departamento->siglas,
                            'monto' => $techo->monto,
                            'fecha_asignacion' => $techo->created_at
                        ];
                    })
                ];
            });

        // Agrupar por departamento
        $departamentosDetalle = $departamentosUnicos->map(function ($depto) use ($techosConDepartamentos) {
            $techosDepto = $techosConDepartamentos->where('idDepartamento', $depto->id);
            
            $fuentesPorDepto = $techosDepto
                ->groupBy(function ($techo) {
                    return $techo->techoUE->fuente->nombre ?? 'Sin Fuente';
                })
                ->map(function ($techosPorFuente, $nombreFuente) {
                    return [
                        'nombre' => $nombreFuente,
                        'monto' => $techosPorFuente->sum('monto'),
                        'asignaciones' => $techosPorFuente->map(function ($techo) {
                            return [
                                'id' => $techo->id,
                                'monto' => $techo->monto,
                                'fecha_asignacion' => $techo->created_at
                            ];
                        })
                    ];
                });

            return [
                'id' => $depto->id,
                'nombre' => $depto->name,
                'siglas' => $depto->siglas,
                'tipo' => $depto->tipo,
                'monto_asignado' => $techosDepto->sum('monto'),
                'cantidad_asignaciones' => $techosDepto->count(),
                'fuentes' => $fuentesPorDepto
            ];
        })->sortByDesc('monto_asignado');

        $this->detalleEstructura = [
            'estructura' => $this->estructura,
            'cantidad_departamentos' => $cantidadDepartamentos,
            'monto_total_asignado' => $montoTotal,
            'promedio_por_departamento' => $promedioMonto,
            'fuentes_utilizadas' => $fuentesUsadas,
            'departamentos' => $departamentosDetalle
        ];
    }

    public function volver()
    {
        return redirect()->route('techodeptos', [
            'idPoa' => $this->idPoa,
            'idUE' => $this->idUE
        ])->with('setActiveTab', 'por-estructura');
    }

    public function render()
    {
        return view('livewire.techo-deptos.detalle-estructura');
    }
}
