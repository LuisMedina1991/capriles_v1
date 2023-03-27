<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TRANSACCIONES BANCARIAS</title>
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
                    <span style="font-size: 20px; font-weigth: bold;">Transacciones Bancarias</span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 16px"><strong>Cuenta Consultada: {{$account}}</strong></span>
                    <br>
                    @if ($reportRange == 0)
                    <span style="font-size: 16px"><strong>Transacciones del Dia</strong></span>
                    <br>
                    <span style="font-size: 16px"><strong>Fecha de Consulta: {{ \Carbon\Carbon::today()->format('d-m-Y')}}</strong></span>
                    @else
                    <span style="font-size: 16px"><strong>Transacciones por Fecha</strong></span>
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
                    <th>tipo de transaccion</th>
                    <th>n° recibo</th>
                    <th>saldo previo</th>
                    <th>monto</th>
                    <th>saldo actual</th>
                    <th>detalles</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                <tr>
                    <td>
                        <h5 class="text-center">{{\Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y')}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$transaction->action}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$transaction->relation_file_number}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">${{number_format($transaction->previus_balance,2)}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">${{number_format($transaction->amount,2)}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">${{number_format($transaction->actual_balance,2)}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$transaction->description}}</h5>
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