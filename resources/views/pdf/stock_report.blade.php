<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INVENTARIO</title>
    <link rel="stylesheet" href="{{ public_path('css/custom_pdf.css') }}">
    <!--estilos de hoja pdf-->
    <link rel="stylesheet" href="{{ public_path('css/custom_page.css') }}">
    <!--estilos de hoja pdf-->
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
                    <span style="font-size: 20px; font-weigth: bold;">Inventario</span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 16px"><strong>VALOR TOTAL DE INVENTARIO: ${{ number_format($my_total,2)
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
                    <th>Codigo de <br>Producto</th>
                    <th>Marca</th>
                    <th>Contenedor</th>
                    <th>Informacion <br>Adicional</th>
                    <th>Costo/Precio</th>
                    <th>Stock por Costo</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stocks as $product)
                @if($product->activeStocks->sum('stock') > 0)
                <tr>
                    <td align="center">
                        <h4 class="text-center">{{$product->code}}</h4>
                    </td>
                    <td align="center">
                        <h4 class="text-center">{{$product->brand->name}}</h4>
                    </td>
                    <td align="center">
                        <h4 class="text-center">
                            {{$product->container->subcategory->category->name}}-{{$product->container->subcategory->name}}-{{$product->container->presentation->name}}
                        </h4>
                    </td>
                    <td align="center">
                        <h4 class="text-center">
                            {{$product->comment}}<br>{{$product->container->additional_info}}
                        </h4>
                    </td>
                    <td align="center">
                        @foreach($product->activeStocks as $pivot)
                        <li>
                            ${{number_format($pivot->value->cost,2)}}/${{number_format($pivot->value->price,2)}}
                        </li>
                        @endforeach
                    </td>
                    <td align="center">
                        @foreach($product->activeStocks as $pivot)
                        <li>
                            {{$pivot->office->name}} = {{$pivot->stock}}
                        </li>
                        @endforeach
                    </td>
                    <td align="center">
                        <h4 class="text-center">
                            {{$product->activeStocks->sum('stock')}}
                        </h4>
                    </td>
                </tr>
                @endif
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