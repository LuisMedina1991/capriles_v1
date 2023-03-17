<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IMPUESTOS</title>
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
                    <span style="font-size: 20px; font-weigth: bold;">Impuestos por Pagar</span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 16px"><strong>TOTAL DE IMPUESTOS POR PAGAR: ${{ number_format($total,2)
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
                    <th>n° de recibo</th>
                    <th>detalle</th>
                    <th>saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($taxes as $tax)
                <tr>
                    <td align="center">
                        <h6 class="text-center">{{Carbon\Carbon::parse($tax->created_at)->format('d-m-Y')}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">{{$tax->taxable->file_number}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">{{$tax->description}}</h6>
                    </td>
                    <td align="center">
                        <h6 class="text-center">${{number_format($tax->amount,2)}}</h6>
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