<?php

namespace App\Livewire\Requisicion;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Requisicion\Requisicion;
use App\Models\Requisicion\EstadoRequisicionLog;
use App\Models\Departamento\Departamento;
use App\Models\Poa\Poa;
use Livewire\Attributes\Layout;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestaria;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestariaLog;
use App\Models\EjecucionPresupuestaria\EstadoEjecucionPresupuestaria;
use App\Models\Actas\ActaEntrega;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.app')]
class AdministrarRequisiciones extends Component
{
    use WithPagination;

    public $search = '';
    public $anio = '';
    public $departamento = '';
    public $estado = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $showDetalleModal = false;
    public $detalleRecursos = [];
    public $detalleRequisicion = [];
    public $observacionModal = '';
    public $requisicionSeleccionadaId;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function actualizarEstado($id, $nuevoEstado)
    {
        $requisicion = Requisicion::findOrFail($id);
        $requisicion->idEstado = $nuevoEstado;
        $requisicion->save();
        session()->flash('message', 'Estado actualizado correctamente.');
    }

    public function verDetalleRequisicion($id)
    {
        $this->requisicionSeleccionadaId = $id;
        $requisicion = Requisicion::with(['departamento', 'estado', 'creador', 'detalleRequisiciones.presupuesto'])
            ->findOrFail($id);

        $recursos = [];
        $monto_total = 0;
        foreach ($requisicion->detalleRequisiciones as $detalle) {
            $presupuesto = $detalle->presupuesto;
            $total = ($detalle->cantidad ?? 0) * ($presupuesto->costounitario ?? 0);
            $monto_total += $total;
            $recursos[] = [
                'recurso' => $presupuesto->recurso ?? '-',
                'detalle_tecnico' => $presupuesto->detalle_tecnico ?? '-',
                'cantidad' => $detalle->cantidad ?? '-',
                'precio_unitario' => $presupuesto->costounitario ?? 0,
                'total' => $total,
            ];
        }

        $fechaPresentado = $requisicion->fechaSolicitud;
        $fechaRequerido = $requisicion->fechaRequerido;
        if ($fechaPresentado && !($fechaPresentado instanceof \Carbon\Carbon)) {
            $fechaPresentado = \Carbon\Carbon::parse($fechaPresentado);
        }
        if ($fechaRequerido && !($fechaRequerido instanceof \Carbon\Carbon)) {
            $fechaRequerido = \Carbon\Carbon::parse($fechaRequerido);
        }
        $this->detalleRequisicion = [
            'correlativo' => $requisicion->correlativo,
            'departamento' => $requisicion->departamento->name ?? '-',
            'descripcion' => $requisicion->descripcion ?? '-',
            'observacion' => $requisicion->observacion ?? '-',
            'estado' => $requisicion->estado->estado ?? '-',
            'fecha_presentado' => $fechaPresentado ? $fechaPresentado->format('M d, Y') : '',
            'fecha_requerido' => $fechaRequerido ? $fechaRequerido->format('M d, Y') : '',
            'monto_total' => $monto_total,
            'tipo_proceso' => $this->obtenerTipoProcesoSugerido($monto_total),
        ];
        $this->detalleRecursos = $recursos;
        $this->showDetalleModal = true;
        $this->observacionModal = '';
        
    }

    private function obtenerTipoProcesoSugerido($monto)
    {
        $tipo = \App\Models\ProcesoCompras\TipoProcesoCompra::obtenerPorMonto($monto);
        return $tipo ? [
            'nombre' => $tipo->nombre,
            'monto_minimo' => $tipo->monto_minimo,
            'monto_maximo' => $tipo->monto_maximo,
        ] : null;
    }

    public function cerrarDetalleModal()
    {
        $this->showDetalleModal = false;
        $this->reset(['requisicionSeleccionadaId', 'detalleRequisicion', 'detalleRecursos', 'observacionModal']);
    }

    public function marcarComoRecibido()
    {
        if (!isset($this->detalleRequisicion['correlativo'])) {
            session()->flash('error', 'No se encontró la requisición.');
            return;
        }
        $requisicion = Requisicion::where('correlativo', $this->detalleRequisicion['correlativo'])->first();
        if (!$requisicion) {
            session()->flash('error', 'No se encontró la requisición.');
            return;
        }
        $estadoRecibido = \App\Models\Requisicion\EstadoRequisicion::where('estado', 'Recibido')->first();
        if ($estadoRecibido) {
            $requisicion->idEstado = $estadoRecibido->id;
        }
        $requisicion->approvedBy = auth()->id();
        $requisicion->save();
        // Log de cambio de estado
        EstadoRequisicionLog::create([
            'observacion' => $this->observacionModal ?? '',
            'log' => 'Cambio a Recibido',
            'idRequisicion' => $requisicion->id,
            'created_by' => auth()->id(),
        ]);
        $this->detalleRequisicion['estado'] = $estadoRecibido ? $estadoRecibido->estado : 'Recibido';
        session()->flash('message', 'Requisición marcada como Recibida.');
    }

    public function marcarComoRechazado()
    {
        if (!isset($this->detalleRequisicion['correlativo'])) {
            session()->flash('error', 'No se encontró la requisición.');
            return;
        }
        $requisicion = Requisicion::where('correlativo', $this->detalleRequisicion['correlativo'])->first();
        if (!$requisicion) {
            session()->flash('error', 'No se encontró la requisición.');
            return;
        }
        $estadoRechazado = \App\Models\Requisicion\EstadoRequisicion::where('estado', 'Rechazado')->first();
        if ($estadoRechazado) {
            $requisicion->idEstado = $estadoRechazado->id;
        }
        $requisicion->approvedBy = auth()->id();
        $requisicion->save();
        // Log de cambio de estado
        EstadoRequisicionLog::create([
            'observacion' => $this->observacionModal ?? '',
            'log' => 'Cambio a Rechazado',
            'idRequisicion' => $requisicion->id,
            'created_by' => auth()->id(),
        ]);
        $this->detalleRequisicion['estado'] = $estadoRechazado ? $estadoRechazado->estado : 'Rechazado';
        $this->cerrarDetalleModal();
        session()->flash('message', 'Requisición marcada como Rechazada.');
    }

    public function marcarComoAprobado()
    {
        if (!isset($this->detalleRequisicion['correlativo'])) {
            session()->flash('error', 'No se encontró la requisición.');
            return;
        }
        $requisicion = Requisicion::where('correlativo', $this->detalleRequisicion['correlativo'])->first();
        if (!$requisicion) {
            session()->flash('error', 'No se encontró la requisición.');
            return;
        }
        $estadoAprobado = \App\Models\Requisicion\EstadoRequisicion::where('estado', 'Aprobado')->first();
        if ($estadoAprobado) {
            $requisicion->idEstado = $estadoAprobado->id;
        }
        $requisicion->approvedBy = auth()->id();
        $requisicion->save();
        // Log de cambio de estado
        EstadoRequisicionLog::create([
            'observacion' => $this->observacionModal ?? '',
            'log' => 'Cambio a Aprobado',
            'idRequisicion' => $requisicion->id,
            'created_by' => auth()->id(),
        ]);
        $this->detalleRequisicion['estado'] = $estadoAprobado ? $estadoAprobado->estado : 'Aprobado';
        session()->flash('message', 'Requisición marcada como Aprobada.');
    }

    public function marcarComoProcesoCompra()
    {
        $this->validate(['observacionModal' => 'nullable|string|max:500']);

        try {
            DB::beginTransaction();

            $requisicion = Requisicion::findOrFail($this->requisicionSeleccionadaId);
            
            // Buscar el estado "En Proceso de Compra"
            $estadoProcesoCompra = \App\Models\Requisicion\EstadoRequisicion::where('estado', 'En Proceso de Compra')->first();
            
            if (!$estadoProcesoCompra) {
                throw new \Exception('No se encontró el estado "En Proceso de Compra"');
            }

            // Guardar la observación anterior
            $observacionAnterior = $requisicion->observacion;
            
            // Concatenar la nueva observación si existe
            $nuevaObservacion = $observacionAnterior;
            if ($this->observacionModal) {
                $nuevaObservacion = $observacionAnterior 
                    ? $observacionAnterior . ' | ' . $this->observacionModal 
                    : $this->observacionModal;
            }

            // Actualizar la requisición
            $requisicion->update([
                'idEstado' => $estadoProcesoCompra->id,
                'observacion' => $nuevaObservacion,
                'approvedBy' => Auth::id(),
            ]);

            // Crear log de cambio de estado para la requisición
            EstadoRequisicionLog::create([
                'observacion' => $this->observacionModal ?? 'Cambio automático a En Proceso de Compra',
                'log' => 'Cambio a En Proceso de Compra',
                'idRequisicion' => $requisicion->id,
                'created_by' => Auth::id(),
            ]);

            // Crear ejecución presupuestaria
            $this->crearEjecucionPresupuestaria($requisicion);

            // Actualizar los datos del modal
            $this->detalleRequisicion['estado'] = $estadoProcesoCompra->estado;
            $this->detalleRequisicion['observacion'] = $nuevaObservacion;

            DB::commit();

            session()->flash('message', 'Requisición marcada como "En Proceso de Compra" correctamente.');
            
            // Refrescar el modal en lugar de cerrarlo
            $this->verDetalleRequisicion($this->requisicionSeleccionadaId);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al marcar como proceso de compra:', [
                'error' => $e->getMessage(),
                'requisicion_id' => $this->requisicionSeleccionadaId ?? 'No definido',
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    protected function crearEjecucionPresupuestaria($requisicion)
    {
        // Verificar si ya existe una ejecución para esta requisición
        $ejecucionExistente = EjecucionPresupuestaria::where('idRequisicion', $requisicion->id)->first();
        
        if ($ejecucionExistente) {
            Log::info('Ya existe una ejecución presupuestaria', ['requisicion_id' => $requisicion->id]);
            return $ejecucionExistente;
        }

        try {
            // Crear ejecución presupuestaria con estado "Parcialmente ejecutado" (ID: 1)
            // Observación se deja NULL para que el usuario la agregue manualmente
            $ejecucion = EjecucionPresupuestaria::create([
                'observacion' => null,
                'fechaInicioEjecucion' => now(),
                'idRequisicion' => $requisicion->id,
                'idEstadoEjecucion' => 1, // Parcialmente ejecutado
                'created_by' => Auth::id(),
            ]);

            Log::info('Ejecución presupuestaria creada', [
                'requisicion_id' => $requisicion->id,
                'ejecucion_id' => $ejecucion->id
            ]);

            // Crear log
            $log = EjecucionPresupuestariaLog::create([
                'observacion' => 'Log generado por el sistema',
                'log' => 'Ejecución creada',
                'idEjecucionPresupuestaria' => $ejecucion->id,
                'created_by' => Auth::id(),
            ]);

            Log::info('Log de ejecución creado', [
                'ejecucion_id' => $ejecucion->id,
                'log_id' => $log->id
            ]);

            return $ejecucion;

        } catch (\Exception $e) {
            Log::error('Error al crear ejecución presupuestaria', [
                'requisicion_id' => $requisicion->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function descargarActaPdf($requisicionId)
    {
        try {
            \Log::info('Descargando acta PDF desde Livewire', ['requisicion_id' => $requisicionId]);

            $requisicion = Requisicion::with([
                'departamento',
                'estado',
                'creador.empleado',
                'detalleRequisiciones.presupuesto.unidadMedida'
            ])->findOrFail($requisicionId);

            // Obtener el acta de entrega
            $actaEntrega = ActaEntrega::with([
                'tipoActaEntrega',
                'ejecucionPresupuestaria',
                'detalles.detalleRequisicion.presupuesto.unidadMedida',
                'detalles.detalleEjecucionPresupuestaria'
            ])->where('idRequisicion', $requisicionId)->first();

            if (!$actaEntrega) {
                session()->flash('error', 'No se encontró el acta de entrega para esta requisición.');
                return;
            }

            // Preparar datos para el PDF
            $data = [
                'acta' => $actaEntrega,
                'requisicion' => $requisicion,
                'detalles' => $actaEntrega->detalles,
            ];

            $pdf = Pdf::loadView('pdf.acta-entrega', $data);
            $pdf->setPaper('letter', 'portrait');

            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, 'Acta-Entrega-' . $actaEntrega->correlativo . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Error al generar PDF de acta:', [
                'requisicion_id' => $requisicionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $anios = Poa::select('anio')->distinct()->orderByDesc('anio')->pluck('anio');
        $departamentos = Departamento::orderBy('name')->get();
        $estados = [
            'Todos', 'Presentado', 'Recibido', 'En Proceso de Compra', 'Aprobado', 'Rechazado', 'Finalizado'
        ];

        $query = Requisicion::with(['departamento', 'estado'])
            ->when($this->search, function($q) {
                $q->where('correlativo', 'like', "%{$this->search}%")
                  ->orWhereHas('departamento', function($q2) {
                      $q2->where('name', 'like', "%{$this->search}%");
                  });
            })
            ->when($this->anio, function($q) {
                $q->whereHas('poa', function($q2) {
                    $q2->where('anio', $this->anio);
                });
            })
            ->when($this->departamento && $this->departamento !== 'Todos', function($q) {
                $q->whereHas('departamento', function($q2) {
                    $q2->where('id', $this->departamento);
                });
            })
            ->when($this->estado && $this->estado !== 'Todos', function($q) {
                $q->whereHas('estado', function($q2) {
                    $q2->where('estado', $this->estado);
                });
            });

        $requisiciones = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);

        return view('livewire.seguimiento.Requisicion.administrar-requisiciones', [
            'requisiciones' => $requisiciones,
            'anios' => $anios,
            'departamentos' => $departamentos,
            'estados' => $estados,
            'detalleRecursos' => $this->detalleRecursos ?? [],
        ]);
    }
}
