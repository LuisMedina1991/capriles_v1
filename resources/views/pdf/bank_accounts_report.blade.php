<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CUENTAS BANCARIAS</title>
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
                    <span style="font-size: 20px; font-weigth: bold;">Cuentas Bancarias</span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
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
                    <th>n° cuenta</th>
                    <th>propietario</th>
                    <th>banco</th>
                    <th>tipo de cuenta</th>
                    <th>moneda</th>
                    <th>saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                <tr>
                    <td>
                        <h5 class="text-center">{{$account->number}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$account->company->alias}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$account->bank->alias}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$account->type}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">{{$account->currency}}</h5>
                    </td>
                    <td>
                        <h5 class="text-center">${{number_format($account->balance,2)}}</h5>
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