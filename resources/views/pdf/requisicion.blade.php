<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Requisición de Materiales</title>
    <style>
        .Presentado {
            color: white;
            background-color: rgb(31, 31, 31);
            padding: 8px;
            border-radius: 10px;
        }

        .Recibido {
            color: white;
            background-color: rgb(119, 0, 141);
            padding: 8px;
            border-radius: 10px;
        }

        .Proceso {
            color: white;
            background-color: rgb(0, 90, 217);
            padding: 8px;
            border-radius: 10px;
        }

        .Aprobado {
            color: white;
            background-color: rgb(0, 127, 15);
            padding: 8px;
            border-radius: 10px;
        }

        .Rechazado {
            color: white;
            background-color: rgb(161, 0, 0);
            padding: 8px;
            border-radius: 10px;
        }

        .entregado {
            color: white;
            background-color: rgb(9, 183, 56);
            padding: 8px;
            border-radius: 10px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }

        .parent {
            position: relative;
            width: 100%;
            padding: 5px;
        }

        .header {
            padding: 20px 0 0 0;
            text-align: center;
        }

        .header h1 {
            line-height: 1.6;
            font-size: 14px;
            font-weight: 600;
            margin: 0;
        }

        .header p {
            font-size: 9px;
            line-height: 1.6;
            margin: 0;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .logo img {
            width: 120px;
            height: auto;
        }

        .instrucciones {
            width: 57%;
            font-size: 10px;
            margin-bottom: 10px;
        }

        .instrucciones p {
            line-height: 1.2;
            margin: 0;
        }

        .estado-badge {
            position: absolute;
            top: 40px;
            right: 45px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            font-size: 9px;
            padding: 3px;
        }

        .table th {
            background: #B8CCE4;
            font-weight: 600;
        }

        .table td {
            font-size: 10px;
        }

        .obser {
            background-color: #B8CCE4;
            width: 100%;
            text-align: center;
            font-size: 11px;
            font-weight: 550;
        }

        .observaciones p {
            width: 100%;
            height: 22px;
            margin: 0;
        }

        .firmas th,
        .firmas td {
            border: 1px solid #000;
            font-size: 9px;
            padding: 3px;
        }

        .firmas th {
            background: #B8CCE4;
            font-weight: 650;
        }

        .firmas td {
            height: 30px;
        }
    </style>
</head>

<body>
    <div style="display: flex; justify-content: center;">
        <div class="parent mt-0">
            <div class="header">
                <h1>UNIVERSIDAD NACIONAL AUTÓNOMA DE HONDURAS</h1>
                <p>CENTRO UNIVERSITARIO REGIONAL DEL LITORAL PACIFICO</p>
                <p>DEPARTAMENTO DE ADMINISTRACION</p>
                <p style="margin-top: 1em; font-size: 13px; font-weight: 700;">REQUISICION MATERIALES</p>
            </div>
            <div class="logo">
                <img src="{{ public_path('Logo/logounah.png') }}">
            </div>
            <div
                style="display: flex; flex-direction: row; align-items: flex-start; justify-content: space-between; width: 100%; position: relative;">
                <div class="instrucciones mt-3" style="margin-bottom:0;">
                    <p><b>Instrucciones:</b></p>
                    <p>Favor ingresar los datos para la solicitud, indicar con claridad y detalle la descripción de los
                        equipos o insumos requeridos (Cantidad, medidas, color, material, especificaciones técnicas,
                        etc). Hacer referencia al número de actividad en el POA.</p>
                </div>
                <div class="estado-badge" style="position:absolute; right:0; top:-18px;">
                    <div class="{{ $estado ?? 'Presentado' }}" style="display:inline; font-size:13px; font-weight:500;">
                        {{ $estado ?? 'Presentado' }}
                    </div>
                </div>
            </div>
            <br>
            <div class="datos-requisicion">
                <table class="table mt-2" style="table-layout: fixed;">
                    <tbody>
                        <tr>
                            <th width="25%">DEPARTAMENTO SOLICITANTE:</th>
                            <td width="35%">{{ $departamento ?? '-' }}</td>
                            <th width="15%">REQUISICIÓN No.</th>
                            <td width="25%">{{ $correlativo ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th width="25%">JEFE DEPARTAMENTO:</th>
                            <td width="35%">{{ $jefe_departamento ?? '-' }}</td>
                            <th width="15%">FECHA DE SOLICITUD:</th>
                            <td width="25%">
                                <table style="width:100%; border-collapse:collapse;">
                                    <tr>
                                        <td style="border:none; border-right:1px solid #000; width:33%; text-align:center; padding:0;">
                                            {{ $fecha_presentado_dia ?? '-' }}</td>
                                        <td style="border:none; border-right:1px solid #000; width:33%; text-align:center; padding:0;">
                                            {{ $fecha_presentado_mes ?? '-' }}</td>
                                        <td style="border:none; width:34%; text-align:center; padding:0;">
                                            {{ $fecha_presentado_anio ?? '-' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th width="25%">PROPOSITO / ACTIVIDAD:</th>
                            <td width="35%">{{ $proposito ?? '-' }}</td>
                            <th width="15%">FECHA REQUERIDO:</th>
                            <td width="25%">
                                <table style="width:100%; border-collapse:collapse;">
                                    <tr>
                                        <td style="border:none; border-right:1px solid #000; width:40%; text-align:center; padding:0;">
                                            {{ $fecha_requerido_dia ?? '-' }}</td>
                                        <td style="border:none; border-right:1px solid #000; width:40%; text-align:center; padding:0;">
                                            {{ $fecha_requerido_mes ?? '-' }}</td>
                                        <td style="border:none; width:40%; text-align:center; padding:0;">
                                            {{ $fecha_requerido_anio ?? '-' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="datos">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>CANTIDAD</th>
                            <th>DESCRIPCIÓN</th>
                            <th>UNIDAD MEDIDA</th>
                            <th>PRECIO UNIDAD</th>
                            <th>TOTAL</th>
                            <th>REF. POA</th>
                            <th>REF. ACTA ENTREGA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 7; $i++)
                            @if (isset($recursos[$i]))
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $recursos[$i]['cantidad'] }}</td>
                                    <td>{{ $recursos[$i]['recurso'] }} {{ $recursos[$i]['detalle_tecnico'] }}</td>
                                    <td>{{ $recursos[$i]['unidad'] }}</td>
                                    <td>L {{ number_format($recursos[$i]['precio_unitario'], 2) }}</td>
                                    <td>L {{ number_format($recursos[$i]['total'], 2) }}</td>
                                    <td>{{ $recursos[$i]['ref_poa'] ?? '' }}</td>
                                    <td>{{ $recursos[$i]['ref_acta'] ?? '' }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        @endfor
                        <tr>
                            <td colspan="5"></td>
                            <td style="font-weight:600;">COSTO TOTAL:</td>
                            <td colspan="2" style="font-weight:600;">L {{ number_format($monto_total ?? 0, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 20px;">
                <div style="font-size: 11px; font-weight: 650; background: none; margin-bottom: 0;">FAVOR ADJUTAR
                    COTIZACIONES</div>
                <table class="table" style="margin-top:0;">
                    <thead>
                        <tr>
                            <th colspan="1"
                                style="background: #B8CCE4; text-align: center; font-size: 12px; font-weight: 600;">
                                OBSERVACIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="height: 22px; vertical-align: top;">{{ $observaciones ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="height: 22px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 22px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="datos">
                <div style="position:relative; width:100%;">
                    <table class="table firmas mb-2" style="border-collapse:collapse; width:100%;">
                        <thead>
                            <tr>
                                <th width="32%"><b>FIRMA Y SELLO DEL SOLICITANTE</b></th>
                                <th width="23%"><b>RECIBIDO POR</b></th>
                                <th width="45%"><b>Vo. Bo. (PLANIFICACIÓN)</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td style="padding:0; border-left:1px solid #000; border-right:1px solid #000; vertical-align:top;">
                                    <table style="width:100%; border-collapse:collapse; height:auto;">
                                        <tr>
                                            <td style="width:30%; background-color:#B8CCE4; font-weight:600; border-right:1px solid #000;">NOMBRE</td>
                                            <td style="width:30%; background-color:#fff; text-align:right; padding: 0.5px 0.5px;">{{ $recibido_nombre ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:30%; background-color:#B8CCE4; font-weight:600; border-right:1px solid #000;">FECHA</td>
                                            <td style="width:30%; background-color:#fff; text-align:right; padding: 0.5px 0.5px;">{{ $recibido_fecha ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:30%; background-color:#B8CCE4; font-weight:600; border-right:1px solid #000;">HORA</td>
                                            <td style="width:30%; background-color:#fff; text-align:right; padding: 0.5px 0.5px;">{{ $recibido_hora ?? '' }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div style="margin-top: 10px;">
                <table class="table" style="margin-top:0;">
                    <thead>
                        <tr>
                            <th colspan="1"
                                style="background: #B8CCE4; text-align: center; font-size: 12px; font-weight: 600;">PARA
                                USO DE LA ADMINISTRACIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="height: 22px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 22px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 22px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>

    </div>
</body>

</html>
