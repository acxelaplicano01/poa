<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Combustible</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
         @page {
            size: letter; /* Define el tamaño de la página como carta */
            margin: 20mm; /* Márgenes estándar */
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .parent {
            position: relative;
            width: 100%;
            padding: 5px;
        }
        .header {
            padding: 20px 0 0 0;
            margin-top: 1.5em;
        }
        .header p {
            font-size: 11px;
            line-height: 0.4;
            margin: 0;
        }
        img.logo-img {
            width: 90px;
            height: auto;
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .b-right { border-right: 1px solid black; }
        .b-left { border-left: 1px solid black; }
        .b-buttom { border-bottom: 1px solid black; }
        .b-top { border-top: 1px solid black; }
        .datos-table td {
            font-size: 13px;
            padding: 5px 3px;
        }
        .firma {
            display: flex !important;
            justify-content: flex-end !important;
            margin-top: 30px !important;
            width: 100% !important;
        }
        .marca {
            position: relative;
        }
        .marca:after {
            content: "COPIA";
            font-size: 8em;
            font-family: 'Arial', sans-serif;
            color: rgba(211, 211, 211, 0.4);
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-27deg);
            pointer-events: none;
            user-select: none;
            z-index: 9999;
        }
    </style>
</head>
<body>
    @php
        $bloques = ['original', 'copia'];
    @endphp

    @foreach($bloques as $i => $tipo)
        <main class="flex {{ $tipo === 'copia' ? 'marca' : '' }}" style="width:100%;">
            <div>
                <div style="display: flex; justify-content: center;">
                    <div class="parent mt-0">
                        <div class="header d-flex justify-content-center">
                            <section class="section-header">
                                <p align="center" style="font-size: 18px;">Campus Choluteca</p> <br>
                                <p align="center" style="font-size: 12px;"> <br>
                                    ORDEN DE COMBUSTIBLE No. {{ $orden->correlativo ?? '-' }}
                                </p> <br>
                                <p align="center" style="font-size: 12px;">
                                    {{ $orden->detalleRequisicion->requisicion->departamento->siglas ?? '-' }}
                                </p> <br><br>
                            </section>
                        </div>
                        <img class="logo-img" src="{{ public_path('Logo/logounah.png') }}" alt="Logo UNAH">
                    </div>
                </div>
                {{-- Tabla de datos --}}
                <div class="datos-table">
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;" align="center">
                        <tbody>
                            <tr>
                                <td class="b-left b-top b-buttom" width="7%" align="center">
                                    @php
                                        $nombreRecurso = strtolower($orden->detalleRequisicion->presupuesto->recurso ?? '');
                                    @endphp
                                    @if(str_contains($nombreRecurso, 'gasolina')) X @endif
                                </td>
                                <td class="b-left b-top b-buttom" width="23%">Gasolina</td>
                                <td class="b-left b-top b-buttom b-right" width="16%" rowspan="2">
                                    L. {{ number_format($orden->monto, 2) }}
                                </td>
                                <td class="b-left b-top b-buttom b-right" width="16%" align="left" rowspan="2" colspan="3">
                                    <span>Valor en Letras: {{ $orden->monto_en_letras }} LEMPIRAS</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="b-left b-buttom">
                                    @if(str_contains($nombreRecurso, 'diesel')) X @endif
                                </td>
                                <td class="b-left b-buttom">Diesel</td>
                            </tr>
                            <tr>
                                <td class="b-left b-buttom" colspan="2" align="right">Vehículo modelo</td>
                                <td class="b-left b-buttom" colspan="2">{{ $orden->modelo_vehiculo }}</td>
                                <td class="b-left b-buttom" align="right">No. de placa</td>
                                <td class="b-right b-left b-buttom" width="16%">{{ $orden->placa }}</td>
                            </tr>
                            <tr>
                                <td class="b-left b-buttom" colspan="2" align="right">Lugar Salida</td>
                                <td class="b-left b-buttom" colspan="2">{{ $orden->lugar_salida }}</td>
                                <td class="b-left b-buttom" align="right">Recorrido Km.</td>
                                <td class="b-right b-left b-buttom">{{ $orden->recorrido_km }}</td>
                            </tr>
                            <tr>
                                <td class="b-left b-buttom" colspan="2" align="right">Lugar destino</td>
                                <td class="b-left b-buttom" colspan="2">{{ $orden->lugar_destino }}</td>
                                <td class="b-left b-buttom" align="right">Fecha de actividad</td>
                                <td class="b-right b-left b-buttom">
                                    {{ \Carbon\Carbon::parse($orden->fecha_actividad)->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="b-left b-buttom" colspan="2" align="right">Responsable de la actividad</td>
                                <td class="b-left b-buttom" colspan="2">
                                    {{ $orden->empleado->nombre ?? '' }} {{ $orden->empleado->apellido ?? '' }}
                                </td>
                                <td class="b-left b-buttom" align="right">Ref. POA</td>
                                <td class="b-right b-left b-buttom">
                                    {{ $orden->detalleRequisicion->presupuesto->tarea->actividade->correlativo ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="b-left b-buttom b-right" colspan="2" align="right">Actividades a realizar</td>
                                <td class="b-left b-buttom b-right" colspan="4">{{ $orden->actividades_realizar }}</td>
                            </tr>
                            <tr>
                                <td class="b-left b-buttom b-right" colspan="6">
                                    Autorizado por: {{ $userDescarga->empleado->nombre ?? '' }} {{ $userDescarga->empleado->apellido ?? '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="firma" style="width: 100%; margin-top: 30px;">
                <table style="width: 100%; border: none;">

                    <tr>
                        <td style="border: none;"></td>
                        <td style="border: none; width: 220px; text-align: right;">
                            <div style="border-top: 1px solid; width: 180px; margin-left: auto; text-align: center;">
                                <p style="font-size: 12px; margin-bottom: 0;">
                                    {{ $userSolicitante->empleado->nombre ?? '' }} {{ $userSolicitante->empleado->apellido ?? '' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </main>
    @endforeach
</body>
</html>