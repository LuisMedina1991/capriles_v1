<!--directiva de livewire restringe las actualizaciones al elemento en si pero no a sus hijos-->
<div wire:ignore.self id="modal-details" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>Detalle de Ingreso</b>
                </h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close"> <!--boton de cierre-->
                    <span class="text-white">&times;</span> <!--icono "x"-->
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">  <!--tabla-->
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                            <tr>
                                <th class="table-th text-center text-white">TIPO DE PRODUCTO</th>
                                <th class="table-th text-center text-white">MEDIDA</th>
                                <th class="table-th text-center text-white">CANT</th>
                                <th class="table-th text-center text-white">COSTO</th>
                                <th class="table-th text-center text-white">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody> <!--cuerpo de tabla-->
                            @foreach ($details as $detail)  <!--iteracion de los datos almacenados en variable pasada desde controlador-->
                                <tr>
                                    <td class="text-center"><h6>{{$detail->type}}</h6></td>
                                    <td class="text-center"><h6>{{$detail->product}}</h6></td>
                                    <!--funcion number_format de php para dar formato decimal recibe 2 parametros (numero,cantidad de decimales)-->
                                    <td class="text-center"><h6>{{number_format($detail->quantity, 0)}}</h6></td>
                                    <!--funcion number_format de php para dar formato decimal recibe 2 parametros (numero,cantidad de decimales)-->
                                    <td class="text-center"><h6>${{number_format($detail->cost, 2)}}</h6></td>
                                    <!--aqui se obtiene la cantidad * precio y se da formato decimal al resultado-->
                                    <td class="text-center"><h6>${{number_format($detail->quantity * $detail->cost, 2)}}</h6></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot> <!--pie de pagina de tabla-->
                            <td class="text-left"><h6 class="text-info">TOTALES: </h6></td>
                            <td></td>
                            <td class="text-center">
                                @if($details)   <!--validar si se tiene detalle de venta-->
                                    <h6 class="text-info">{{$details->sum('quantity')}}</h6>    <!--imprimir cantidad de productos de la venta-->
                                @endif
                            </td>
                            @if($details)   <!--validar si se tiene detalle de venta-->
                                @php    //directiva de blade 
                                    $mytotal = 0;
                                @endphp
                                @foreach ($details as $detail)  <!--iteracion de los datos almacenados en variable pasada desde controlador-->
                                    @php    //directiva de blade
                                        $mytotal += $detail->quantity * $detail->cost; //suma de cantidad * precio y guardado en variable
                                    @endphp
                                @endforeach
                                <td></td>
                                <!--funcion number_format de php para dar formato decimal recibe 2 parametros (numero,cantidad de decimales)-->
                                <td class="text-center"><h6 class="text-info">${{number_format($mytotal, 2)}}</h6></td>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>