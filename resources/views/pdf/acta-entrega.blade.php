<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Entrega</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .header-logo {
            display: table-cell;
            width: 100px;
            vertical-align: top;
        }
        .header-logo img {
            height: 80px;
            width: auto;
        }
        .header-text {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding-left: 20px;
        }
        .header-text h1 {
            font-size: 13pt;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .header-text h2 {
            font-size: 11pt;
            margin: 5px 0;
            font-weight: bold;
        }
        .header-text h3 {
            font-size: 12pt;
            margin: 15px 0 0 0;
            font-weight: bold;
        }
        .title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin: 25px 0 20px 0;
            text-decoration: underline;
        }
        .info-section {
            margin: 20px 0;
        }
        .info-row {
            margin: 8px 0;
            font-size: 10pt;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 140px;
        }
        .content-text {
            text-align: justify;
            margin: 20px 0 15px 0;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 9pt;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .signatures {
            margin-top: 80px;
            display: table;
            width: 100%;
        }
        .signature-block {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
            vertical-align: top;
        }
        .signature-line {
            border-top: 2px solid #000;
            margin-top: 60px;
            padding-top: 8px;
            font-size: 9pt;
            line-height: 1.5;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="header-logo">
            <img src="{{ public_path('Logo/logounah.png') }}" alt="Logo UNAH">
        </div>
        <div class="header-text">
            <h1>UNIVERSIDAD NACIONAL AUTÓNOMA DE HONDURAS</h1>
            <h2>UNAH</h2>
            <h3>ACTA ENTREGA</h3>
        </div>
    </div>

    <div class="title">
        Según requisición {{ $requisicion->correlativo }}
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Correlativo Acta:</span>
            <span>{{ $acta->correlativo }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Entrega:</span>
            <span>{{ $acta->fecha_extendida->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Departamento:</span>
            <span>{{ $requisicion->departamento->name ?? '-' }}</span>
        </div>
    </div>

    <p class="content-text">
        El suscrito administrador del Centro Regional del Litoral Pacífico <strong>HACE CONSTAR</strong> que se entrega los suministros a  
        <strong>{{ $requisicion->creador->empleado ? trim($requisicion->creador->empleado->nombres . ' ' . $requisicion->creador->empleado->apellidos) : $requisicion->creador->name }}</strong>
    </p>

    <p style="margin: 10px 0; font-weight: bold;">Que a continuación se detallan:</p>

    <table>
        <thead>
            <tr>
                <th style="width: 6%;">No.</th>
                <th style="width: 10%;">Cantidad</th>
                <th style="width: 8%;">UM</th>
                <th style="width: 38%;">Descripción</th>
                <th style="width: 16%;">Precio Unitario</th>
                <th style="width: 22%;">No. de factura</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $contador = 1;
                $detallesAgrupados = $detalles->groupBy('idDetalleRequisicion');
            @endphp
            @foreach($detallesAgrupados as $idDetalle => $grupo)
                @php
                    $primerDetalle = $grupo->first();
                    $detalleReq = $primerDetalle->detalleRequisicion;
                    $presupuesto = $detalleReq->presupuesto;
                    $cantidadTotal = $grupo->sum('log_cant_ejecutada');
                    $montoUnitario = $grupo->last()->log_monto_unitario_ejecutado;
                    $subtotal = $cantidadTotal * $montoUnitario;
                    $total += $subtotal;
                    $ultimaEjecucion = $primerDetalle->detalleEjecucionPresupuestaria;
                    $factura = $ultimaEjecucion->referenciaActaEntrega ?? '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $contador++ }}</td>
                    <td class="text-center">{{ number_format($cantidadTotal, 2) }}</td>
                    <td class="text-center">{{ $presupuesto->unidadMedida->unidad ?? 'UND' }}</td>
                    <td>
                        <strong>{{ $presupuesto->recurso ?? '-' }}</strong>
                        @if($presupuesto->detalle_tecnico)
                            <br><small style="color: #555; font-size: 8pt;">{{ $presupuesto->detalle_tecnico }}</small>
                        @endif
                    </td>
                    <td class="text-right">L {{ number_format($montoUnitario, 2) }}</td>
                    <td class="text-center">{{ $factura }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; background-color: #f0f0f0;">
                <td colspan="4" class="text-right" style="font-size: 10pt;">TOTAL</td>
                <td class="text-right" style="font-size: 10pt;">L {{ number_format($total, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <p class="content-text" style="margin-top: 25px;">
        Para los fines que se estime conveniente se extiende en la ciudad de Choluteca a los 
        <strong>{{ $acta->fecha_extendida->day }}</strong> días del mes de 
        <strong>{{ $acta->fecha_extendida->locale('es')->translatedFormat('F') }}</strong> del año 
        <strong>{{ $acta->fecha_extendida->year }}</strong>.
    </p>

    <div class="signatures">
        <div class="signature-block">
            <div class="signature-line">
                <strong>CELEO EMILIO ARIAS</strong><br>
                Director<br>
                UNAH - CURLP
            </div>
        </div>
        <div class="signature-block">
            <div class="signature-line">
                <strong>{{ $requisicion->creador->empleado ? strtoupper(trim($requisicion->creador->empleado->nombres . ' ' . $requisicion->creador->empleado->apellidos)) : strtoupper($requisicion->creador->name) }}</strong><br>
                Coordinador de Investigación<br>
                UNAH - CURLP
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Página 1 de 1 | Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
