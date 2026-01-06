<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Requisicion\Requisicion;

class RequisicionController extends Controller
{
    public function descargarPdf($correlativo)
    {
        $requisicion = Requisicion::with(['departamento', 'detalleRequisiciones.presupuesto', 'estado'])
            ->where('correlativo', $correlativo)
            ->firstOrFail();

        $recursos = [];
        $monto_total = 0;
        foreach ($requisicion->detalleRequisiciones as $detalle) {
            $presupuesto = $detalle->presupuesto;
            $total = ($detalle->cantidad ?? 0) * ($presupuesto->costounitario ?? 0);
            $monto_total += $total;
            $recursos[] = [
                'cantidad' => $detalle->cantidad ?? '-',
                'unidad' => $presupuesto->unidad ?? '-',
                'recurso' => $presupuesto->recurso ?? '-',
                'detalle_tecnico' => $presupuesto->detalle_tecnico ?? '-',
                'precio_unitario' => $presupuesto->costounitario ?? 0,
                'total' => $total,
            ];
        }

        // Fechas desglosadas
        $fecha_presentado = $requisicion->fechaSolicitud ? \Carbon\Carbon::parse($requisicion->fechaSolicitud) : null;
        $fecha_requerido = $requisicion->fechaRequerido ? \Carbon\Carbon::parse($requisicion->fechaRequerido) : null;

        $data = [
            'estado' => $requisicion->estado->estado ?? '',
            'departamento' => $requisicion->departamento->name ?? '',
            'correlativo' => $requisicion->correlativo,
            'solicitante' => $requisicion->solicitante ?? '',
            'jefe_departamento' => $requisicion->jefe_departamento ?? '',
            'proposito' => $requisicion->proposito ?? '',
            'fecha_presentado' => $fecha_presentado ? $fecha_presentado->format('d/m/Y') : '',
            'fecha_presentado_dia' => $fecha_presentado ? $fecha_presentado->format('d') : '',
            'fecha_presentado_mes' => $fecha_presentado ? $fecha_presentado->format('m') : '',
            'fecha_presentado_anio' => $fecha_presentado ? $fecha_presentado->format('Y') : '',
            'fecha_requerido' => $fecha_requerido ? $fecha_requerido->format('d/m/Y') : '',
            'fecha_requerido_dia' => $fecha_requerido ? $fecha_requerido->format('d') : '',
            'fecha_requerido_mes' => $fecha_requerido ? $fecha_requerido->format('m') : '',
            'fecha_requerido_anio' => $fecha_requerido ? $fecha_requerido->format('Y') : '',
            'recibido_nombre' => $requisicion->recibido_nombre ?? '',
            'recibido_fecha' => $requisicion->recibido_fecha ?? '',
            'recibido_hora' => $requisicion->recibido_hora ?? '',
            'recursos' => $recursos,
            'monto_total' => $monto_total,
            'observaciones' => $requisicion->observacion ?? '',
        ];

        $pdf = Pdf::loadView('pdf.requisicion', $data);
        return $pdf->stream('requisicion_'.$requisicion->correlativo.'.pdf');
    }
}
