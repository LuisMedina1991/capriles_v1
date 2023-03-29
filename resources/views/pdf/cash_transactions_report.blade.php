<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TRANSACCIONES EN EFECTIVO</title>
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
                    <span style="font-size: 20px; font-weigth: bold;">Transacciones en Efectivo</span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 16px"><strong>SALDO DE CAJA: ${{number_format($total,2)}}</strong></span>
                    <br>
                    @if ($reportRange == 0 && $search_2 == 0)
                    <span style="font-size: 16px"><strong>Transacciones Activas del Dia</strong></span>
                    <br>
                    <span style="font-size: 16px"><strong>Fecha de Consulta: {{ \Carbon\Carbon::today()->format('d-m-Y')}}</strong></span>
                    @endif
                    @if ($reportRange == 0 && $search_2 == 1)
                    <span style="font-size: 16px"><strong>Transacciones Anuladas del Dia</strong></span>
                    <br>
                    <span style="font-size: 16px"><strong>Fecha de Consulta: {{ \Carbon\Carbon::today()->format('d-m-Y')}}</strong></span>
                    @endif
                    @if ($reportRange == 1 && $search_2 == 0)
                    <span style="font-size: 16px"><strong>Transacciones Activas por Fecha</strong></span>
                    <br>
                    <span style="font-size: 16px"><strong>Fecha de Consulta: {{ $dateFrom }} al {{ $dateTo}}</strong></span>
                    @endif
                    @if ($reportRange == 1 && $search_2 == 1)
                    <span style="font-size: 16px"><strong>Transacciones Anuladas por Fecha</strong></span>
                    <br>
                    <span style="font-size: 16px"><strong>Fecha de Consulta: {{ $dateFrom }} al {{ $dateTo}}</strong></span>
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
                    <th>accion</th>
                    <th width="50%">detalles</th>
                    <th>monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                <tr>
                    <td>
                        <h5 class="text-center">{{\Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y')}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$transaction->file_number}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$transaction->action}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$transaction->description}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">${{number_format($transaction->amount,2)}}</h5>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    <section class="footer">
        <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
            <tr>
                <td width="20%" class="text-center">
                    Tu Empresa
                </td>
                <td width="60%" class="text-center">
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