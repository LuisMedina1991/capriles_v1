<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if ($reportType == 0)
    <title>Reporte General</title>
    @endif
    @if ($reportType == 1)
    <title>Reporte de Ingresos</title>
    @endif
    @if ($reportType == 2)
    <title>Reporte de Traspasos</title>
    @endif
    @if ($reportType == 3)
    <title>Reporte de Ventas</title>
    @endif
    <link rel="stylesheet" href="{{ public_path('css/custom_pdf.css') }}">
    <link rel="stylesheet" href="{{ public_path('css/custom_page.css') }}">

</head>

<body>
    <header>
        <table width="100%">
            <tr>
                <td class="text-center">
                    <span style="font-size: 25px; font-weigth: bold;">Tu Empresa</span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    @if ($reportType == 0)
                    <span style="font-size: 20px; font-weigth: bold;">Reporte General</span>
                    @endif
                    @if ($reportType == 1)
                    <span style="font-size: 20px; font-weigth: bold;">Reporte de Ingresos</span>
                    @endif
                    @if ($reportType == 2)
                    <span style="font-size: 20px; font-weigth: bold;">Reporte de Traspasos</span>
                    @endif
                    @if ($reportType == 3)
                    <span style="font-size: 20px; font-weigth: bold;">Reporte de Ventas</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    @if ($reportRange == 0)
                    <span style="font-size: 16px"><strong>Reporte del Dia</strong></span>
                    @else
                    <span style="font-size: 16px"><strong>Reporte por Fecha</strong></span>
                    @endif
                    <br>
                    <span style="font-size: 16px"><strong>Usuario: {{$user}}</strong></span>
                    <br>
                    @if ($reportRange != 0)
                    <span style="font-size: 16px"><strong>Fecha de Consulta: {{ $dateFrom }} al {{ $dateTo
                            }}</strong></span>
                    @else
                    <span style="font-size: 16px"><strong>Fecha de Consulta: {{ \Carbon\Carbon::today()->format('d-m-Y')
                            }}</strong></span>
                    @endif
                </td>
            </tr>
        </table>
    </header>
    <section>

        <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
            <thead>
                <tr>
                    <th>fecha</th>
                    <th>n° recibo</th>
                    <th>producto</th>
                    <th>estado</th>
                    <th>marca</th>
                    <th>cant</th>
                    <th>costo</th>
                    @switch($reportType)
                    @case (0)
                    <th>total</th>
                    <th>sucursal</th>
                    @break
                    @case(1)
                    <th>total</th>
                    <th>impuesto</th>
                    <th>sucursal</th>
                    <th>tipo de ingreso</th>
                    @break
                    @case(2)
                    <th>origen</th>
                    <th>destino</th>
                    @break
                    @case(3)
                    <th>total</th>
                    <th>impuesto</th>
                    <th>utilidad</th>
                    <th>sucursal</th>
                    @break
                    @endswitch
                </tr>
            </thead>
            <tbody>
                @if($reportType == 0 || $reportType == 1)
                @foreach ($incomes as $income)
                <tr>
                    <td align="center">{{\Carbon\Carbon::parse($income->created_at)->format('d-m-Y')}}</td>
                    <td align="center">{{$income->file_number}}</td>
                    <td align="center">{{$income->stock->value->product->code}}</td>
                    <td align="center">{{$income->status->name}}</td>
                    <td align="center">{{$income->stock->value->product->brand->name}}</td>
                    <td align="center">{{$income->quantity}}</td>
                    <td align="center">${{number_format($income->stock->value->cost,2)}}</td>
                    @if($reportType == 0)
                    <td align="center">${{number_format($income->total,2)}}</td>
                    <td align="center">{{$income->stock->office->name}}</td>
                    @else
                    <td align="center">${{number_format($income->total,2)}}</td>
                    @if($income->tax)
                    <td align="center">${{number_format($income->tax->amount,2)}}</td>
                    @else
                    <td></td>
                    @endif
                    <td align="center">{{$income->stock->office->name}}</td>
                    <td align="center">{{$income->income_type}}</td>
                    @endif
                </tr>
                @endforeach
                @endif
                @if($reportType == 0 || $reportType == 2)
                @foreach ($transfers as $transfer)
                <tr>
                    <td align="center">{{\Carbon\Carbon::parse($transfer->created_at)->format('d-m-Y')}}</td>
                    <td align="center">{{$transfer->file_number}}</td>
                    <td align="center">{{$transfer->stock->value->product->code}}</td>
                    <td align="center">{{$transfer->status->name}}</td>
                    <td align="center">{{$transfer->stock->value->product->brand->name}}</td>
                    <td align="center">{{$transfer->quantity}}</td>
                    <td align="center">${{number_format($transfer->stock->value->cost,2)}}</td>
                    @if($reportType == 0)
                    <td align="center"></td>
                    <td align="center">{{$transfer->from_office}}</td>
                    @else
                    <td align="center">{{$transfer->from_office}}</td>
                    <td align="center">{{$transfer->to_office}}</td>
                    @endif
                </tr>
                @endforeach
                @endif
                @if($reportType == 0 || $reportType == 3)
                @foreach ($sales as $sale)
                <tr>
                    <td align="center">{{\Carbon\Carbon::parse($sale->created_at)->format('d-m-Y')}}</td>
                    <td align="center">{{$sale->file_number}}</td>
                    <td align="center">{{$sale->stock->value->product->code}}</td>
                    <td align="center">{{$sale->status->name}}</td>
                    <td align="center">{{$sale->stock->value->product->brand->name}}</td>
                    <td align="center">{{$sale->quantity}}</td>
                    <td align="center">${{number_format($sale->stock->value->cost,2)}}</td>
                    @if($reportType == 0)
                    <td align="center">${{number_format($sale->total_cost,2)}}</td>
                    <td align="center">{{$sale->stock->office->name}}</td>
                    @else
                    <td align="center">${{number_format($sale->total_cost,2)}}</td>
                    @if($sale->tax)
                    <td align="center">${{number_format($sale->tax->amount,2)}}</td>
                    @else
                    <td></td>
                    @endif
                    <td align="center">${{number_format($sale->utility,2)}}</td>
                    <td align="center">{{$sale->stock->office->name}}</td>
                    @endif
                </tr>
                @endforeach
                @endif
            </tbody>
            <tfoot>
                @if($reportType == 0 || $reportType == 1)
                <tr>
                    <td class="text-center">
                        <b>TOTAL INGRESOS</b>
                    </td>
                    <td colspan="4"></td>
                    <td class="text-center">
                        <b>{{ $incomes->sum('quantity') }}</b>
                    </td>
                    <td></td>
                    <td class="text-center">
                        <b>${{ number_format($incomes->sum('total'), 2) }}</b>
                    </td>
                    @if($reportType == 1)
                    <td class="text-center">
                        <b>${{number_format($incomes->sum('tax.amount'), 2)}}</b>
                    </td>
                    @endif
                    <td colspan="2"></td>
                </tr>
                @endif
                @if($reportType == 0 || $reportType == 2)
                <tr>
                    <td class="text-center">
                        <b>TOTAL TRASPASOS</b>
                    </td>
                    <td colspan="4"></td>
                    <td class="text-center">
                        <b>{{ $transfers->sum('quantity') }}</b>
                    </td>
                    <td colspan="3"></td>
                </tr>
                @endif
                @if($reportType == 0 || $reportType == 3)
                <tr>
                    <td class="text-center">
                        <b>TOTAL VENTAS</b>
                    </td>
                    <td colspan="4"></td>
                    <td class="text-center">
                        <b>{{ $sales->sum('quantity') }}</b>
                    </td>
                    <td></td>
                    <td class="text-center" colspan="1">
                        <span><b>${{ number_format($sales->sum('total_cost'), 2) }}</b></span>
                    </td>
                    @if($reportType == 3)
                    <td class="text-center">
                        <b>${{number_format($sales->sum('tax.amount'), 2)}}</b>
                    </td>
                    <td class="text-center">
                        <b>${{number_format($sales->sum('utility'), 2)}}</b>
                    </td>
                    @endif
                    <td colspan="1"></td>
                </tr>
                @endif
            </tfoot>
        </table>

        {{--@if ($reportType == 1)
        <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
            <thead>
                <tr>
                    <th width="10%">PRODUCTO</th>
                    <th width="10%">MARCA</th>
                    <th width="10%">COSTO</th>
                    <th width="10%">N° RECIBO</th>
                    <th width="10%">CANTIDAD</th>
                    <th width="10%">TOTAL</th>
                    <th width="10%">SUCURSAL</th>
                    <th width="10%">TIPO DE INGRESO</th>
                    <th>USUARIO</th>
                    <th width="10%">FECHA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($income as $i)
                <tr>
                    <td align="center">{{ $i->code }}</td>
                    <td align="center">{{ $i->brand }}</td>
                    <td align="center">${{ $i->cost }}</td>
                    <td align="center">{{ $i->pf }}</td>
                    <td align="center">{{ $i->quantity }}</td>
                    <td align="center">${{ number_format($i->total, 2) }}</td>
                    <td align="center">{{ $i->office }}</td>
                    <td align="center">{{ $i->type }}</td>
                    <td align="center">{{ $i->user }}</td>
                    <td align="center">{{\Carbon\Carbon::parse($i->created_at)->format('d-m-Y')}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <span><b>TOTALES</b></span>
                    </td>
                    <td colspan="3"></td>
                    <td class="text-center">
                        <b>{{ $income->sum('quantity') }}</b>
                    </td>
                    <td class="text-center" colspan="1">
                        <span><b>${{ number_format($income->sum('total'), 2) }}</b></span>
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
                    <th width="10%">MARCA</th>
                    <th width="10%">COSTO</th>
                    <th width="10%">N° RECIBO</th>
                    <th width="10%">CANTIDAD</th>
                    <th width="10%">TOTAL</th>
                    <th width="10%">ORIGEN</th>
                    <th width="10%">DESTINO</th>
                    <th>USUARIO</th>
                    <th width="10%">FECHA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transfer as $i)
                <tr>
                    <td align="center">{{ $i->code }}</td>
                    <td align="center">{{ $i->brand }}</td>
                    <td align="center">${{ $i->cost }}</td>
                    <td align="center">{{ $i->pf }}</td>
                    <td align="center">{{ $i->quantity }}</td>
                    <td align="center">${{ number_format($i->total, 2) }}</td>
                    <td align="center">{{ $i->from_office }}</td>
                    <td align="center">{{ $i->to_office }}</td>
                    <td align="center">{{ $i->user }}</td>
                    <td align="center">{{\Carbon\Carbon::parse($i->created_at)->format('d-m-Y')}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <span><b>TOTALES</b></span>
                    </td>
                    <td colspan="3"></td>
                    <td class="text-center">
                        <b>{{ $transfer->sum('quantity') }}</b>
                    </td>
                    <td class="text-center" colspan="1">
                        <span><b>${{ number_format($transfer->sum('total'), 2) }}</b></span>
                    </td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
        @endif

        @if ($reportType == 3)
        <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
            <thead>
                <tr>
                    <th width="10%">PRODUCTO</th>
                    <th width="10%">MARCA</th>
                    <th width="10%">COSTO</th>
                    <th width="10%">PRECIO</th>
                    <th width="10%">PRECIO VENTA</th>
                    <th width="10%">CANTIDAD</th>
                    <th width="10%">TOTAL</th>
                    <th width="10%">UTILIDAD</th>
                    <th width="10%">SUCURSAL</th>
                    <th width="10%">N° RECIBO</th>
                    <th>USUARIO</th>
                    <th width="10%">FECHA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale as $i)
                <tr>
                    <td align="center">{{ $i->description }}</td>
                    <td align="center">{{ $i->brand }}</td>
                    <td align="center">${{ $i->cost }}</td>
                    <td align="center">${{ $i->price }}</td>
                    <td align="center">${{ number_format(($i->total / $i->quantity),2) }}</td>
                    <td align="center">{{ $i->quantity }}</td>
                    <td align="center">${{ $i->total }}</td>
                    <td align="center">${{ $i->utility }}</td>
                    <td align="center">{{ $i->office }}</td>
                    <td align="center">{{ $i->pf }}</td>
                    <td align="center">{{ $i->user }}</td>
                    <td align="center">{{\Carbon\Carbon::parse($i->created_at)->format('d-m-Y')}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <span><b>TOTALES</b></span>
                    </td>
                    <td colspan="4"></td>
                    <td class="text-center">
                        <b>{{ $sale->sum('quantity') }}</b>
                    </td>
                    <td class="text-center" colspan="1">
                        <span><b>${{ number_format($sale->sum('total'), 2) }}</b></span>
                    </td>
                    <td class="text-center" colspan="1">
                        <span><b>${{ number_format($sale->sum('utility'), 2) }}</b></span>
                    </td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
        </table>
        @endif--}}
    </section>
    <section class="footer">
        <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
            <tr>
                <td class="text-center" width="20%">
                    <span>Tu Empresa</span>
                </td>
                <td class="text-center" width="60%">
                    tuempresa.com.bo
                </td>
                <td class="text-center" width="20%">
                    Pagina <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>