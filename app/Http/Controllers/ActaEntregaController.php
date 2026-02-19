<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actas\ActaEntrega;
use App\Models\Requisicion\Requisicion;
use App\Models\Requisicion\DetalleRequisicion;
use App\Models\Actas\DetalleActaEntrega;
use App\Models\EjecucionPresupuestaria\DetalleEjecucionPresupuestaria;
use Barryvdh\DomPDF\Facade\Pdf;

class ActaEntregaController extends Controller
{

    private function prepararDatosActa($requisicionId): array
    {
        $requisicion = Requisicion::with([
            'departamento', 'estado', 'creador.empleado',
            'detalleRequisiciones.presupuesto.unidadMedida'
        ])->findOrFail($requisicionId);

        $actaEntrega = ActaEntrega::with([
            'tipoActaEntrega', 'ejecucionPresupuestaria',
            'detalles.detalleRequisicion.presupuesto.unidadMedida',
            'detalles.detalleEjecucionPresupuestaria'
        ])->where('idRequisicion', $requisicionId)->firstOrFail();

        return [
            'acta' => $actaEntrega,
            'requisicion' => $requisicion,
            'detalles' => $actaEntrega->detalles,
        ];
    }

    public function descargarPdf($requisicionId)
    {
        $data = $this->prepararDatosActa($requisicionId);
        $pdf = Pdf::loadView('pdf.acta-entrega', $data);
        $pdf->setPaper('letter', 'portrait');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Acta-Entrega-'.$data['acta']->correlativo.'.pdf"');
    }

    public function descargarPdfDownload($requisicionId)
    {
        $data = $this->prepararDatosActa($requisicionId);
        $pdf = Pdf::loadView('pdf.acta-entrega', $data);
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('Acta-Entrega-'.$data['acta']->correlativo.'.pdf');
    }

    public function descargarIntermediaPdf($requisicionId)
    {
        $requisicion = \App\Models\Requisicion\Requisicion::findOrFail($requisicionId);

        // Buscar o crear, pero SIEMPRE actualizar los detalles
        $actaEntrega = \App\Models\Actas\ActaEntrega::where('idRequisicion', $requisicionId)
            ->where('idTipoActaEntrega', 2)
            ->latest('id')
            ->first();

        $userId = auth()->id();

        // Obtener TODOS los detalles de ejecución actuales
        $detallesEjecucion = \App\Models\EjecucionPresupuestaria\DetalleEjecucionPresupuestaria::whereIn(
            'idDetalleRequisicion',
            $requisicion->detalleRequisiciones()->pluck('id')->toArray()
        )->get();

        $idEjecucionPresupuestaria = $detallesEjecucion->first()->idEjecucion ?? null;

        if (!$actaEntrega) {
            $ultimoActa = \App\Models\Actas\ActaEntrega::orderBy('id', 'desc')->first();
            $numero = $ultimoActa ? ($ultimoActa->id + 1) : 1;
            $correlativo = 'ACT-' . str_pad($numero, 6, '0', STR_PAD_LEFT) . '-' . now()->format('Y');

            $actaEntrega = \App\Models\Actas\ActaEntrega::create([
                'correlativo' => $correlativo,
                'fecha_extendida' => now(),
                'idTipoActaEntrega' => 2,
                'idRequisicion' => $requisicion->id,
                'idEjecucionPresupuestaria' => $idEjecucionPresupuestaria,
                'created_by' => $userId,
            ]);
        }

        // SIEMPRE borrar detalles viejos y recrear con datos frescos
        \App\Models\Actas\DetalleActaEntrega::where('idActaEntrega', $actaEntrega->id)->delete();

        foreach ($detallesEjecucion as $detalleEjecucion) {
            \App\Models\Actas\DetalleActaEntrega::create([
                'log_cant_ejecutada' => $detalleEjecucion->cant_ejecutada,
                'log_monto_unitario_ejecutado' => $detalleEjecucion->monto_unitario_ejecutado,
                'log_fechaEjecucion' => $detalleEjecucion->fechaEjecucion,
                'idActaEntrega' => $actaEntrega->id,
                'idRequisicion' => $requisicion->id,
                'idDetalleRequisicion' => $detalleEjecucion->idDetalleRequisicion,
                'idEjecucionPresupuestaria' => $detalleEjecucion->idEjecucion,
                'idDetalleEjecucionPresupuestaria' => $detalleEjecucion->id,
                'created_by' => $userId,
                'observacion' => $detalleEjecucion->observacion ?? null,
                'referenciaActaEntrega' => $detalleEjecucion->referenciaActaEntrega ?? null,
            ]);
        }

        $actaEntrega->load('detalles.detalleRequisicion.presupuesto');

        $data = [
            'requisicion' => $requisicion,
            'acta' => $actaEntrega,
            'detalles' => $actaEntrega->detalles,
            'recursosGestionados' => $requisicion->detalleRequisiciones()->where('entregado', '>', 0)->get(),
        ];

        $pdf = Pdf::loadView('pdf.acta-entrega-intermedia', $data);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="acta-intermedia-' . $requisicion->correlativo . '.pdf"');
    }
    
    public function descargarIntermediaPdfDownload($requisicionId)
    {
        $requisicion = \App\Models\Requisicion\Requisicion::findOrFail($requisicionId);

        $actaEntrega = \App\Models\Actas\ActaEntrega::with([
            'detalles.detalleRequisicion.presupuesto'
        ])
        ->where('idRequisicion', $requisicionId)
        ->where('idTipoActaEntrega', 2)
        ->latest('id')
        ->first();

        if (!$actaEntrega) {
            abort(404, 'No se encontró el acta intermedia.');
        }

        $detalles = $actaEntrega->detalles ?? collect();
        $recursosGestionados = $requisicion->detalleRequisiciones()
            ->where('entregado', '>', 0)->get();

        $data = [
            'requisicion' => $requisicion,
            'acta' => $actaEntrega,
            'detalles' => $detalles,
            'recursosGestionados' => $recursosGestionados,
        ];

        $pdf = Pdf::loadView('pdf.acta-entrega-intermedia', $data);

        return $pdf->download('acta-entrega-intermedia-' . $requisicion->correlativo . '.pdf');
    }
}
