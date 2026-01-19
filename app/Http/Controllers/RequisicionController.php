<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Requisicion\Requisicion;

class RequisicionController extends Controller
{
    public function descargarPdf($correlativo)
    {
        $requisicion = Requisicion::with([
            'departamento',
            'detalleRequisiciones.presupuesto',
            'estado',
            'creador.empleado',
            'aprobadoPor',
            'logs'
        ])
            ->where('correlativo', $correlativo)
            ->firstOrFail();
        // Buscar log de recibido (contenga 'recibido')
        $logRecibido = $requisicion->logs->first(function($log) {
            return stripos($log->log, 'recibido') !== false;
        });
        $recibido_fecha = $logRecibido && $logRecibido->created_at ? $logRecibido->created_at->format('d/m/Y') : 'No recibido';
        $recibido_hora = $logRecibido && $logRecibido->created_at ? $logRecibido->created_at->format('H:i') : 'No recibido';

        $recursos = [];
        $monto_total = 0;
        foreach ($requisicion->detalleRequisiciones as $detalle) {
            $presupuesto = $detalle->presupuesto;
            $total = ($detalle->cantidad ?? 0) * ($presupuesto->costounitario ?? 0);
            $monto_total += $total;
            $recursos[] = [
                'cantidad' => $detalle->cantidad ?? '-',
                'unidad' => $presupuesto->unidadMedida ? $presupuesto->unidadMedida->nombre : '-',
                'recurso' => $presupuesto->recurso ?? '-',
                'detalle_tecnico' => $presupuesto->detalle_tecnico ?? '-',
                'precio_unitario' => $presupuesto->costounitario ?? 0,
                'total' => $total,
            ];
        }

        // Fechas desglosadas
        $fecha_presentado = $requisicion->fechaSolicitud ? \Carbon\Carbon::parse($requisicion->fechaSolicitud) : null;
        $fecha_requerido = $requisicion->fechaRequerido ? \Carbon\Carbon::parse($requisicion->fechaRequerido) : null;

        $recibido_nombre = 'No recibido';
        if ($requisicion->aprobadoPor) {
            if ($requisicion->aprobadoPor->empleado) {
                $recibido_nombre = $requisicion->aprobadoPor->empleado->nombre . ' ' . $requisicion->aprobadoPor->empleado->apellido;
            } else {
                $recibido_nombre = $requisicion->aprobadoPor->name;
            }
        }

        $data = [
            'estado' => $requisicion->estado->estado ?? '',
            'departamento' => $requisicion->departamento->name ?? '',
            'correlativo' => $requisicion->correlativo,
            'solicitante' => $requisicion->solicitante ?? '',
            'jefe_departamento' => $requisicion->creador && $requisicion->creador->empleado ? ($requisicion->creador->empleado->nombre . ' ' . $requisicion->creador->empleado->apellido) : ($requisicion->creador->name ?? ''),
            'proposito' => $requisicion->descripcion ?? '',
            'fecha_presentado' => $fecha_presentado ? $fecha_presentado->format('d/m/Y') : '',
            'fecha_presentado_dia' => $fecha_presentado ? $fecha_presentado->format('d') : '',
            'fecha_presentado_mes' => $fecha_presentado ? $fecha_presentado->format('m') : '',
            'fecha_presentado_anio' => $fecha_presentado ? $fecha_presentado->format('Y') : '',
            'fecha_requerido' => $fecha_requerido ? $fecha_requerido->format('d/m/Y') : '',
            'fecha_requerido_dia' => $fecha_requerido ? $fecha_requerido->format('d') : '',
            'fecha_requerido_mes' => $fecha_requerido ? $fecha_requerido->format('m') : '',
            'fecha_requerido_anio' => $fecha_requerido ? $fecha_requerido->format('Y') : '',
            'recibido_nombre' => $recibido_nombre,
            'recibido_fecha' => $recibido_fecha,
            'recibido_hora' => $recibido_hora,
            'recursos' => $recursos,
            'monto_total' => $monto_total,
            'observaciones' => $requisicion->observacion ?? '',
        ];

        $pdf = Pdf::loadView('pdf.requisicion', $data);
        return $pdf->stream('requisicion_'.$requisicion->correlativo.'.pdf');
    }
}
