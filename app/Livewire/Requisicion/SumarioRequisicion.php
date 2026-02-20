<?php

namespace App\Livewire\Requisicion;

use App\Models\Requisicion\Requisicion as RequisicionModel;
use App\Models\Presupuestos\Presupuesto;
use App\Models\Departamento\Departamento;
use App\Models\Requisicion\DetalleRequisicion;
use App\models\Empleados\Empleado;
use App\Models\Tareas\Tarea;
use App\Models\Poa\Poa;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class SumarioRequisicion extends Component
{
    public $recursosSeleccionados = [];
    public $descripcion = '';
    public $observacion = '';
    public $fechaRequerido = '';
    public $idPoa = null;
    public $idDepartamento = null;
    public $idEstado = null;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $successMessage = '';
    public $isEditing = false;
    public $departamentoSeleccionado = null;

    public $puedeCrearRequisicion = false;
    public $mensajePlazoRequisicion = '';

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
        'monto' => 0,
        'monto_en_letras' => '',
    ];
    public $empleados = [];
    public $showCrearRequisicionModal = false; // Propiedad para controlar la visibilidad de la modal

    public function mount()
    {
        $this->empleados = Empleado::all();
        $this->departamentoSeleccionado = session('departamentoSeleccionado');
        
        $recursosGuardados = session('recursosSeleccionados', []);
        
        // Asegurar que es_combustible esté presente en cada recurso
        $this->recursosSeleccionados = collect($recursosGuardados)->map(function($recurso) {
            if (!isset($recurso['es_combustible'])) {
                $nombre = strtoupper($recurso['nombre'] ?? '');
                $recurso['es_combustible'] = str_contains($nombre, 'GASOLINA') || str_contains($nombre, 'DIESEL');
            }
            return $recurso;
        })->toArray();

        $this->verificarPlazoRequisicion(); 
    }

    public function quitarRecursoDelSumario($recursoId)
    {
        $this->recursosSeleccionados = collect($this->recursosSeleccionados)
            ->reject(fn($item) => $item['id'] == $recursoId)
            ->values()
            ->toArray();

        session(['recursosSeleccionados' => $this->recursosSeleccionados]);
    }

    protected function getEstadoPresentadoId()
    {
        $estado = DB::table('estado_requisicion')->where('estado', 'Presentado')->first();
        return $estado ? $estado->id : null;
    }

    public function crearRequisicion()
    {
        if (!$this->puedeCrearRequisicion) {
            session()->flash('error', $this->mensajePlazoRequisicion);
            return;
        }
        $this->validate([
            'descripcion' => 'required',
            'fechaRequerido' => 'required|date',
        ]);

        if (empty($this->recursosSeleccionados)) {
            $this->errorMessage = 'No hay recursos seleccionados para crear la requisición.';
            $this->showErrorModal = true;
            return;
        }

        $user = Auth::user();

        // Obtener idPoa del primer recurso si no está definido
        if (!$this->idPoa) {
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

        $empleadoDepto = DB::table('empleado_deptos')
            ->where('idEmpleado', $user->id)
            ->whereNull('deleted_at')
            ->first();
        $this->idDepartamento = $this->departamentoSeleccionado ?? ($empleadoDepto ? $empleadoDepto->idDepto : ($user->idDepartamento ?? null));
        $this->idEstado = $this->getEstadoPresentadoId();

        $departamento = $this->idDepartamento ? Departamento::find($this->idDepartamento) : null;
        $ultimo = RequisicionModel::orderBy('id', 'desc')->first();
        $numero = $ultimo ? $ultimo->id + 1 : 1;
        $tipoDepto = $departamento->tipo ?? '';
        $nombreDepto = $departamento->name ?? '';
        $anio = $poa ? $poa->anio : date('Y');
        $correlativo = \App\Helpers\CorrelativoHelper::generarCorrelativo($tipoDepto, $nombreDepto, $anio, $numero);

        try {
            $requisicion = RequisicionModel::create([
                'correlativo'    => $correlativo,
                'descripcion'    => $this->descripcion,
                'observacion'    => $this->observacion,
                'created_by'     => $user->id,
                'approved_by'    => null,
                'idPoa'          => $this->idPoa,
                'idDepartamento' => $this->idDepartamento,
                'idEstado'       => $this->idEstado,
                'fechaSolicitud' => now(),
                'fechaRequerido' => $this->fechaRequerido,
            ]);

            foreach ($this->recursosSeleccionados as $recurso) {
            $presupuesto = Presupuesto::find($recurso['id']);
            if ($presupuesto) {
                $detalle = DetalleRequisicion::create([
                    'idRequisicion' => $requisicion->id,
                    'idPoa'         => $this->idPoa,
                    'idPresupuesto' => $presupuesto->id,
                    'idRecurso'     => $presupuesto->idHistorico,
                    'cantidad'      => $recurso['cantidad_seleccionada'],
                    'idUnidadMedida'=> $presupuesto->idunidad,
                    'entregado'     => false,
                    'created_by'    => $user->id,
                ]);

                // Actualizar orden de combustible con el detalle definitivo
                \DB::table('orden_combustible')
                    ->where('idRecurso', $presupuesto->id)
                    ->where('created_by', $user->id)
                    ->whereNull('idDetalleRequisicion') // solo los que no tienen detalle aún
                    ->orWhere(function($q) use ($presupuesto, $user) {
                        // o los que tienen un detalle provisional diferente
                        $q->where('idRecurso', $presupuesto->id)
                        ->where('created_by', $user->id);
                    })
                    ->orderByDesc('id')
                    ->limit(1)
                    ->update(['idDetalleRequisicion' => $detalle->id]); // apuntar al detalle real
            }
        }
            session()->forget('recursosSeleccionados');
            $this->showCrearRequisicionModal = false;
            session()->flash('message', 'Requisición creada correctamente.');
            foreach ($this->recursosSeleccionados as $recurso) {
                session()->forget("orden_combustible_{$recurso['id']}");
            }
            session()->forget('recursosSeleccionados');
            session()->forget('departamentoSeleccionado');
            session()->forget('poaYearSeleccionado');
            return redirect()->route('requisicion');

        } catch (\Exception $e) {
            $this->errorMessage = 'Error al guardar: ' . $e->getMessage();
            $this->showErrorModal = true;
        }
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

  public function abrirOrdenCombustibleModal($recursoId)
    {
        $recurso = collect($this->recursosSeleccionados)->firstWhere('id', $recursoId);
        $this->ordenCombustibleRecursoId = $recursoId;
        $this->ordenCombustibleRecursoNombre = $recurso['nombre'] ?? '';
        $monto = $recurso['total'] ?? 0;

        $ordenIdEnSesion = session("orden_combustible_{$recursoId}");
        
        $ordenExistente = null;
        if ($ordenIdEnSesion) {
            $ordenExistente = DB::table('orden_combustible')
                ->where('id', $ordenIdEnSesion)
                ->first();
        }

        if ($ordenExistente) {
            $this->ordenCombustibleData = [
                'modelo_vehiculo'      => $ordenExistente->modelo_vehiculo,
                'placa'                => $ordenExistente->placa,
                'lugar_salida'         => $ordenExistente->lugar_salida,
                'lugar_destino'        => $ordenExistente->lugar_destino,
                'recorrido_km'         => $ordenExistente->recorrido_km,
                'fecha_actividad'      => $ordenExistente->fecha_actividad,
                'responsable'          => $ordenExistente->responsable,
                'actividades_realizar' => $ordenExistente->actividades_realizar,
                'monto'                => $ordenExistente->monto,
                'monto_en_letras'      => $ordenExistente->monto_en_letras,
                'id'                   => $ordenExistente->id,
            ];
        } else {
            $this->ordenCombustibleData = [
                'modelo_vehiculo'      => '',
                'placa'                => '',
                'lugar_salida'         => '',
                'lugar_destino'        => '',
                'recorrido_km'         => 0,
                'fecha_actividad'      => '',
                'responsable'          => '',
                'actividades_realizar' => '',
                'monto'                => $monto,
                'monto_en_letras'      => $this->numeroALetras($monto),
                'id'                   => null,
            ];
        }

        $this->showOrdenCombustibleModal = true;
    }
    public function cerrarOrdenCombustibleModal()
    {
        $this->showOrdenCombustibleModal = false;
        $this->successMessage = '';
    }

    public function guardarOrdenCombustible()
    {
        $this->validate([
            'ordenCombustibleData.modelo_vehiculo'     => 'required',
            'ordenCombustibleData.placa'               => 'required',
            'ordenCombustibleData.lugar_salida'        => 'required',
            'ordenCombustibleData.lugar_destino'       => 'required',
            'ordenCombustibleData.recorrido_km'        => 'required|numeric',
            'ordenCombustibleData.fecha_actividad'     => 'required|date',
            'ordenCombustibleData.responsable'         => 'required|exists:empleados,id',
            'ordenCombustibleData.actividades_realizar' => 'required',
        ]);

        // Obtener idPoa del recurso si no está definido
        if (empty($this->idPoa) && $this->ordenCombustibleRecursoId) {
            $presupuesto = Presupuesto::find($this->ordenCombustibleRecursoId);
            if ($presupuesto && $presupuesto->idtarea) {
                $tarea = Tarea::find($presupuesto->idtarea);
                if ($tarea && $tarea->idPoa) {
                    $this->idPoa = $tarea->idPoa;
                }
            }
        }

        // Buscar o crear requisición temporal para el detalle
        $requisicion = RequisicionModel::where('idPoa', $this->idPoa)
            ->where('created_by', Auth::id())
            ->orderByDesc('id')
            ->first();

        if (!$requisicion) {
            $poa = Poa::find($this->idPoa);
            $empleadoDepto = DB::table('empleado_deptos')
                ->where('idEmpleado', Auth::id())
                ->whereNull('deleted_at')
                ->first();
            $idDepartamento = $empleadoDepto ? $empleadoDepto->idDepto : (Auth::user()->idDepartamento ?? null);
            $departamento = $idDepartamento ? Departamento::find($idDepartamento) : null;
            $ultimo = RequisicionModel::orderBy('id', 'desc')->first();
            $numero = $ultimo ? ($ultimo->id + 1) : 1;
            $anio = $poa ? $poa->anio : now()->format('Y');
            $correlativoReq = \App\Helpers\CorrelativoHelper::generarCorrelativo(
                $departamento->tipo ?? '', $departamento->name ?? '', $anio, $numero
            );
            $requisicion = RequisicionModel::create([
                'correlativo'    => $correlativoReq,
                'descripcion'    => $this->descripcion ?: 'Orden de combustible',
                'observacion'    => $this->observacion,
                'idPoa'          => $this->idPoa,
                'idDepartamento' => $idDepartamento,
                'idEstado'       => $this->getEstadoPresentadoId(),
                'fechaSolicitud' => now(),
                'fechaRequerido' => now(),
                'created_by'     => Auth::id(),
            ]);
        }

        // Buscar o crear detalle
        $idDetalleRequisicion = null;
        $detalle = DetalleRequisicion::where('idRequisicion', $requisicion->id)
            ->where('idPresupuesto', $this->ordenCombustibleRecursoId)
            ->orderByDesc('id')
            ->first();

        if ($detalle) {
            $idDetalleRequisicion = $detalle->id;
        } else {
            $presupuesto = Presupuesto::find($this->ordenCombustibleRecursoId);
            $idRecurso = $presupuesto ? ($presupuesto->idHistorico ?? null) : null;
            $idUnidadMedida = $presupuesto ? ($presupuesto->idunidad ?? null) : null;

            if ($idRecurso && $idUnidadMedida) {
                $detalleNuevo = DetalleRequisicion::create([
                    'idRequisicion'  => $requisicion->id,
                    'idPoa'          => $this->idPoa,
                    'idPresupuesto'  => $this->ordenCombustibleRecursoId,
                    'idRecurso'      => $idRecurso,
                    'cantidad'       => 1,
                    'idUnidadMedida' => $idUnidadMedida,
                    'entregado'      => false,
                    'created_by'     => Auth::id(),
                ]);
                $idDetalleRequisicion = $detalleNuevo->id;
            }
        }

        if (!$idDetalleRequisicion) {
            $this->errorMessage = 'No se pudo obtener el detalle de requisición.';
            $this->showErrorModal = true;
            return;
        }

        // Generar correlativo
        $ultimo = RequisicionModel::orderBy('id', 'desc')->first();
        $numero = $ultimo ? ($ultimo->id + 1) : 1;
        $correlativo = $numero . '-' . now()->format('Y');

        if (!empty($this->ordenCombustibleData['id'])) {
                // Actualizar orden existente
                DB::table('orden_combustible')
                    ->where('id', $this->ordenCombustibleData['id'])
                    ->update([
                        'monto'                => $this->ordenCombustibleData['monto'],
                        'monto_en_letras'      => $this->ordenCombustibleData['monto_en_letras'],
                        'modelo_vehiculo'      => $this->ordenCombustibleData['modelo_vehiculo'],
                        'lugar_salida'         => $this->ordenCombustibleData['lugar_salida'],
                        'lugar_destino'        => $this->ordenCombustibleData['lugar_destino'],
                        'placa'                => $this->ordenCombustibleData['placa'],
                        'recorrido_km'         => $this->ordenCombustibleData['recorrido_km'],
                        'fecha_actividad'      => $this->ordenCombustibleData['fecha_actividad'],
                        'actividades_realizar' => $this->ordenCombustibleData['actividades_realizar'],
                        'responsable'          => $this->ordenCombustibleData['responsable'],
                        'updated_at'           => now(),
                    ]);
                    session(["orden_combustible_{$this->ordenCombustibleRecursoId}" => $this->ordenCombustibleData['id']]);
        } else {
                // Insertar nueva orden
                DB::table('orden_combustible')->insert([
                    'correlativo'          => $correlativo,
                    'monto'                => $this->ordenCombustibleData['monto'],
                    'monto_en_letras'      => $this->ordenCombustibleData['monto_en_letras'],
                    'modelo_vehiculo'      => $this->ordenCombustibleData['modelo_vehiculo'],
                    'lugar_salida'         => $this->ordenCombustibleData['lugar_salida'],
                    'lugar_destino'        => $this->ordenCombustibleData['lugar_destino'],
                    'placa'                => $this->ordenCombustibleData['placa'],
                    'recorrido_km'         => $this->ordenCombustibleData['recorrido_km'],
                    'fecha_actividad'      => $this->ordenCombustibleData['fecha_actividad'],
                    'actividades_realizar' => $this->ordenCombustibleData['actividades_realizar'],
                    'idPoa'                => $this->idPoa,
                    'idDetalleRequisicion' => $idDetalleRequisicion,
                    'idRecurso'            => $this->ordenCombustibleRecursoId,
                    'responsable'          => $this->ordenCombustibleData['responsable'],
                    'created_by'           => Auth::id(),
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]);
                $idOrdenInsertada = DB::getPdo()->lastInsertId();
                session(["orden_combustible_{$this->ordenCombustibleRecursoId}" => $idOrdenInsertada]);

        }

        // Marcar recurso como con orden creada
        $this->recursosSeleccionados = collect($this->recursosSeleccionados)
            ->map(function($recurso) {
                if ($recurso['id'] == $this->ordenCombustibleRecursoId) {
                    $recurso['orden_combustible_creada'] = true;
                }
                return $recurso;
            })->toArray();

        session(['recursosSeleccionados' => $this->recursosSeleccionados]);

       $this->cerrarOrdenCombustibleModal();
       $this->successMessage = 'Orden de combustible guardada correctamente.';
    }

    private function numeroALetras($numero): string
    {
        $entero = (int) $numero;
        $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
            'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIEN', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
            'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        $convertir = function($n) use ($unidades, $decenas, $centenas, &$convertir) {
            if ($n == 0) return '';
            if ($n < 20) return $unidades[$n];
            if ($n < 100) {
                $d = intdiv($n, 10);
                $u = $n % 10;
                return $decenas[$d] . ($u ? ' Y ' . $unidades[$u] : '');
            }
            if ($n < 1000) {
                $c = intdiv($n, 100);
                $resto = $n % 100;
                $prefijo = $c == 1 && $resto > 0 ? 'CIENTO' : $centenas[$c];
                return $prefijo . ($resto ? ' ' . $convertir($resto) : '');
            }
            if ($n < 1000000) {
                $miles = intdiv($n, 1000);
                $resto = $n % 1000;
                $prefijo = $miles == 1 ? 'MIL' : $convertir($miles) . ' MIL';
                return $prefijo . ($resto ? ' ' . $convertir($resto) : '');
            }
            return (string)$n;
        };

        return $entero == 0 ? 'CERO' : $convertir($entero);
    }

    private function verificarPlazoRequisicion()
    {
        $poas = \App\Models\Poa\Poa::activo()->get();

        if ($poas->isEmpty()) {
            $this->puedeCrearRequisicion = false;
            $this->mensajePlazoRequisicion = 'No hay un POA activo.';
            return;
        }

        // Buscar el POA que tenga el plazo de requerimientos vigente
        foreach ($poas as $poa) {
            if ($poa->puedeRequerir()) {
                $this->puedeCrearRequisicion = true;
                $this->mensajePlazoRequisicion = '';
                return;
            }
        }

        // Ningún POA tiene el plazo vigente — mostrar mensaje del POA que tenga algo configurado
        $this->puedeCrearRequisicion = false;

        $poaConPlazo = $poas->first(function($poa) {
            return $poa->plazos()->where('tipo_plazo', 'requerimientos')->exists();
        });

        $this->mensajePlazoRequisicion = $poaConPlazo
            ? $poaConPlazo->getMensajeErrorPlazo('requerimientos')
            : 'No hay un plazo de requerimientos configurado.';
    }

    public function render()
    {
        return view('livewire.requisicion.sumario-recursos', [
            'recursosSeleccionados' => $this->recursosSeleccionados,
            'puedeCrearRequisicion' => $this->puedeCrearRequisicion,
            'mensajePlazoRequisicion' => $this->mensajePlazoRequisicion,
        ]);
    }
}