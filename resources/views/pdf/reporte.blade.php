<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if ($reportType == 0)
     <title>Reporte de Ventas</title>
    @endif
    @if ($reportType == 1)
     <title>Reporte de Ingresos</title>
    @endif
    @if ($reportType == 2)
     <title>Reporte de Traspasos</title>
    @endif
    <link rel="stylesheet" href="{{ public_path('css/custom_pdf.css') }}">    <!--estilos de hoja pdf-->
    <link rel="stylesheet" href="{{ public_path('css/custom_page.css') }}"> <!--estilos de hoja pdf-->

</head>
<body>
    <section class="header" style="top: -287px;">
        <table cellpading="0" cellspacing="0" width="100%">
            <tr>
                <td colspan="2" class="text-center">
                    <span style="font-size: 25px; font-weigth: bold;">Importadora Capriles</span>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-top: 5px; padding-right: 15px; position: relative" width="30%">
                    <img src="{{ public_path('assets/img/LOGO3.png') }}" alt="" class="invoice-logo" style="">
                </td>
                <td width="70%" class="text-left text-company" style="vertical-align: top; padding-top: 10px;">
                    @if ($reportRange == 0)  <!--validar si se esta seleccionando ventas del dia-->
                        <span style="font-size: 16px"><strong>Reportes del Dia</strong></span>
                    @else
                        <span style="font-size: 16px"><strong>Reportes por Fecha</strong></span>
                    @endif
                    <br>
                    @if ($reportRange != 0)  <!--validar si se esta seleccionando ventas por fecha-->
                        <span style="font-size: 16px"><strong>Fecha de Consulta: {{ $dateFrom }} al {{ $dateTo }}</strong></span>
                    @else
                        <span style="font-size: 16px"><strong>Fecha de Consulta: {{ \Carbon\Carbon::now()->format('d-M-Y') }}</strong></span>
                    @endif
                    <br>
                    <span style="font-size: 14px">Usuario: {{ $user }}</span>
                </td>
            </tr>
        </table>
    </section>
    <section style="margin-top: -110px">

        @if ($reportType == 0)
            <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
                <thead>
                    <tr>
                        <th width="10%">PRODUCTO</th>
                        <th width="10%">SUCURSAL</th>
                        <th width="12%">TOTAL</th>
                        <th width="10%">CANTIDAD</th>
                        <th width="10%">UTILIDAD</th>
                        {{--<th width="12%">ESTADO</th>--}}
                        <th>USUARIO</th>
                        <th width="20%">FECHA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td align="center">{{ $item->product }}</td>
                            <td align="center">{{ $item->office }}</td>
                            <td align="center">${{ number_format($item->total, 2) }}</td>
                            <td align="center">{{ $item->items }}</td>
                            <td align="center">{{ $item->utility }}</td>
                            {{--<td align="center">{{ $item->status }}</td>--}}
                            <td align="center">{{ $item->user }}</td>
                            <td align="center">{{ $item->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-center">
                            <span><b>TOTALES</b></span>
                        </td>
                        <td colspan="1"></td>
                        <td class="text-center" colspan="1">
                            <span><b>${{ number_format($data->sum('total'), 2) }}</b></span>
                        </td>
                        <td class="text-center">
                            <b>{{ $data->sum('items') }}</b>
                        </td>
                        <td class="text-center">
                            <b>{{ $data->sum('utility') }}</b>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        @endif

        @if ($reportType == 1)
            <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
                <thead>
                    <tr>
                        <th width="10%">PRODUCTO</th>
                        <th width="10%">SUCURSAL</th>
                        <th width="12%">TOTAL</th>
                        <th width="10%">CANTIDAD</th>
                        {{--<th width="12%">ESTADO</th>--}}
                        <th>USUARIO</th>
                        <th width="18%">FECHA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td align="center">{{ $item->product }}</td>
                            <td align="center">{{ $item->office }}</td>
                            <td align="center">${{ number_format($item->total, 2) }}</td>
                            <td align="center">{{ $item->items }}</td>
                            {{--<td align="center">{{ $item->status }}</td>--}}
                            <td align="center">{{ $item->user }}</td>
                            <td align="center">{{ $item->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-center">
                            <span><b>TOTALES</b></span>
                        </td>
                        <td class="text-center" colspan="1">
                            <span><b>${{ number_format($data->sum('total'), 2) }}</b></span>
                        </td>
                        <td class="text-center">
                            <b>{{ $data->sum('items') }}</b>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        @endif

        @if ($reportType == 2)
            <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
                <thead>
                    <tr>
                        <th width="10%">PRODUCTO</th>
                        <th width="12%">TOTAL</th>
                        <th width="10%">CANTIDAD</th>
                        <th width="10%">ORIGEN</th>
                        <th width="10%">DESTINO</th>
                        {{--<th width="12%">ESTADO</th>--}}
                        <th>USUARIO</th>
                        <th width="18%">FECHA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td align="center">{{ $item->product }}</td>
                            <td align="center">${{ number_format($item->total, 2) }}</td>
                            <td align="center">{{ $item->items }}</td>
                            <td align="center">{{ $item->from_office }}</td>
                            <td align="center">{{ $item->to_office }}</td>
                            {{--<td align="center">{{ $item->status }}</td>--}}
                            <td align="center">{{ $item->user }}</td>
                            <td align="center">{{ $item->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-center">
                            <span><b>TOTALES</b></span>
                        </td>
                        <td class="text-center" colspan="1">
                            <span><b>${{ number_format($data->sum('total'), 2) }}</b></span>
                        </td>
                        <td class="text-center">
                            <b>{{ $data->sum('items') }}</b>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </section>
    <section class="footer">
        <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
            <tr>
                <td width="20%">
                    <span>IMPORTADORA CAPRILES</span>
                </td>
                <td width="60%" class="text-center">
                    importadoracapriles.com.bo
                </td>
                <td class="text-center" width="20%">
                    Pagina <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </section>
</body>
</html>