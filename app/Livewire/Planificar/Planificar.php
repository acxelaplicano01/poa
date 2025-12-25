<?php

namespace App\Livewire\Planificar;

use Livewire\Component;
use App\Models\Departamento\Departamento;
use App\Models\TechoUes\TechoDepto;
use App\Models\Poa\Poa;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
//#[Lazy]
class Planificar extends Component
{
    public $departamentoSeleccionado = null;
    public $departamentosUsuario = [];
    public $poasHistorial = [];
    public $mostrarSelector = false;

    //crear funcion para placeholder
    /*public function placeholder()
    {
        return view('livewire.placeholder.loaders')->layout('layouts.app');
    }*/
        
    public function mount()
    {
        $this->cargarDepartamentosUsuario();
        
        // Si solo tiene un departamento, seleccionarlo automáticamente
        if ($this->departamentosUsuario->count() === 1) {
            $this->departamentoSeleccionado = $this->departamentosUsuario->first()->id;
            $this->mostrarSelector = false;
        } else {
            $this->mostrarSelector = true;
            // Seleccionar el primer departamento por defecto si hay varios
            if ($this->departamentosUsuario->count() > 0) {
                $this->departamentoSeleccionado = $this->departamentosUsuario->first()->id;
            }
        }
        
        $this->cargarPoasHistorial();
    }

    private function cargarDepartamentosUsuario()
    {
        $user = Auth::user();
        
        if (!$user || !$user->empleado) {
            $this->departamentosUsuario = collect();
            return;
        }
        
        // Obtener todos los departamentos del empleado
        $this->departamentosUsuario = $user->empleado->departamentos()
            ->with('unidadEjecutora')
            ->get();
    }

    public function updatedDepartamentoSeleccionado()
    {
        $this->cargarPoasHistorial();
    }

    private function cargarPoasHistorial()
    {
        if (!$this->departamentoSeleccionado) {
            $this->poasHistorial = collect();
            return;
        }

        // Obtener todos los techos departamentales para el departamento seleccionado
        // agrupados por POA, ordenados por año descendente
        // Solo POAs activos
        $techosDeptos = TechoDepto::with([
                'poa',
                'departamento',
                'techoUE.fuente',
                'unidadEjecutora'
            ])
            ->where('idDepartamento', $this->departamentoSeleccionado)
            ->whereHas('poa', function ($query) {
                $query->where('activo', true);
            })
            ->get()
            ->groupBy('idPoa')
            ->map(function ($techos, $idPoa) {
                $primerTecho = $techos->first();
                $poa = $primerTecho->poa;
                
                // Determinar el estado del POA en relación al año actual
                $anioActual = (int) date('Y');
                $anioPoa = (int) ($poa->anio ?? 0);
                
                $estadoPoa = 'pasado'; // Por defecto
                if ($anioPoa == $anioActual) {
                    $estadoPoa = 'actual';
                } elseif ($anioPoa > $anioActual) {
                    $estadoPoa = 'proximo';
                }
                
                return [
                    'idPoa' => $idPoa,
                    'poa' => $poa,
                    'anio' => $poa->anio ?? 'N/A',
                    'nombre' => $poa->name ?? 'Sin nombre',
                    'departamento' => $primerTecho->departamento,
                    'unidadEjecutora' => $primerTecho->unidadEjecutora,
                    'techos' => $techos,
                    'montoTotal' => $techos->sum('monto'),
                    'cantidadFuentes' => $techos->count(),
                    'fuentes' => $techos->map(function ($techo) {
                        return [
                            'id' => $techo->id,
                            'fuente' => $techo->techoUE->fuente->nombre ?? 'Sin fuente',
                            'identificador' => $techo->techoUE->fuente->identificador ?? '',
                            'monto' => $techo->monto,
                        ];
                    }),
                    'estadoPoa' => $estadoPoa, // 'actual', 'proximo', o 'pasado'
                    'isActual' => $estadoPoa === 'actual', // Mantener compatibilidad
                ];
            })
            ->sortByDesc('anio')
            ->values();
        
        $this->poasHistorial = $techosDeptos;
    }

    public function seleccionarPoa($idPoa)
    {
        // Redirigir a actividades con el POA específico y el departamento
        return redirect()->route('actividades', [
            'idPoa' => $idPoa, 
            'departamento' => $this->departamentoSeleccionado
        ]);
    }

    public function render()
    {
        return view('livewire.Planificar.planificar', [
            'departamentosUsuario' => $this->departamentosUsuario,
            'poasHistorial' => $this->poasHistorial,
            'mostrarSelector' => $this->mostrarSelector,
        ])->layout('layouts.app');
    }
}
