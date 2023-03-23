<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CHEQUES</title>
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
                    <span style="font-size: 20px; font-weigth: bold;">Cheques por Cobrar</span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 16px"><strong>TOTAL CHEQUES POR COBRAR: ${{ number_format($total,2)
                            }}</strong></span>
                    <br>
                    <span style="font-size: 16px"><strong>Fecha de Consulta: {{ \Carbon\Carbon::now()->format('d-m-Y')
                            }}</strong></span>
                </td>
            </tr>
        </table>
    </header>
    <section>
        <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
            <thead>
                <tr>
                    <th>fecha</th>
                    <th>n° cheque</th>
                    <th>n° recibo</th>
                    <th>cliente</th>
                    <th>banco</th>
                    <th>cobro</th>
                    <th>descripcion</th>
                    <th>saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paychecks as $paycheck)
                <tr>
                    <td align="center">
                        <h6 class="text-center">{{Carbon\Carbon::parse($paycheck->created_at)->format('d-m-Y')}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">{{$paycheck->number}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">{{$paycheck->sale->file_number}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">{{$paycheck->customer->name}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">{{$paycheck->bank->alias}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">{{$paycheck->status->name}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">{{$paycheck->description}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">${{number_format($paycheck->amount,2)}}</h6>
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