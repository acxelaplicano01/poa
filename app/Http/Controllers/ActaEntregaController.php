<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actas\ActaEntrega;
use App\Models\Requisicion\Requisicion;
use Barryvdh\DomPDF\Facade\Pdf;

class ActaEntregaController extends Controller
{
    public function descargarPdf($requisicionId)
    {
        try {
            \Log::info('=== INICIO DESCARGA ACTA PDF ===', ['requisicion_id' => $requisicionId]);

            // Verificar que la requisición existe
            $requisicion = Requisicion::with([
                'departamento',
                'estado',
                'creador.empleado',
                'detalleRequisiciones.presupuesto.unidadMedida'
            ])->find($requisicionId);

            if (!$requisicion) {
                \Log::error('Requisición no encontrada', ['requisicion_id' => $requisicionId]);
                return back()->with('error', 'Requisición no encontrada.');
            }

            \Log::info('Requisición encontrada', [
                'id' => $requisicion->id,
                'correlativo' => $requisicion->correlativo,
                'estado' => $requisicion->estado->estado ?? 'N/A'
            ]);

            // Verificar que existe el acta
            $actaEntrega = ActaEntrega::with([
                'tipoActaEntrega',
                'ejecucionPresupuestaria',
                'detalles.detalleRequisicion.presupuesto.unidadMedida',
                'detalles.detalleEjecucionPresupuestaria'
            ])->where('idRequisicion', $requisicionId)->first();

            if (!$actaEntrega) {
                \Log::error('Acta no encontrada', ['requisicion_id' => $requisicionId]);
                return back()->with('error', 'No se encontró el acta de entrega. Asegúrate de que la requisición esté finalizada.');
            }

            \Log::info('Acta encontrada', [
                'acta_id' => $actaEntrega->id,
                'correlativo' => $actaEntrega->correlativo,
                'detalles_count' => $actaEntrega->detalles->count()
            ]);

            // Preparar datos
            $data = [
                'acta' => $actaEntrega,
                'requisicion' => $requisicion,
                'detalles' => $actaEntrega->detalles,
            ];

            \Log::info('Generando PDF...');

            // Generar PDF
            $pdf = Pdf::loadView('pdf.acta-entrega', $data);
            $pdf->setPaper('letter', 'portrait');

            \Log::info('PDF generado exitosamente');

            $filename = 'Acta-Entrega-' . $actaEntrega->correlativo . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('=== ERROR AL GENERAR PDF ===', [
                'requisicion_id' => $requisicionId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}
