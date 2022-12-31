<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget">
            <div class="widget-heading">
                <h4 class="card-title text-center"><b>{{$componentName}}</b></h4>   <!--nombre dinamico de componente-->
            </div>
            <div class="widget-content">    <!--card-->
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="col">
                            <div class="col-sm-12">
                                <h6>Elige el usuario</h6>
                                <div class="form-group">
                                    <!--directiva de livewire para relacionar el select con una propiedad publica-->
                                    <select wire:model="userId" class="form-control">
                                        <option value="0">Todos</option>    <!--opcion por defecto-->
                                        @foreach ($users as $user)  <!--iteracion de los datos almacenados en variable pasada desde controlador-->
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Elige el tipo de reporte</h6>
                                <div class="form-group">
                                    <!--directiva de livewire para relacionar el select con una propiedad publica-->
                                    <select wire:model="reportType" class="form-control">
                                        <option value="0">Reporte de Egresos</option>
                                        <option value="1">Reporte de Ingresos</option>
                                        <option value="2">Reporte de Traspasos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Elige el alcance del reporte</h6>
                                <div class="form-group">
                                    <!--directiva de livewire para relacionar el select con una propiedad publica-->
                                    <select wire:model="reportRange" class="form-control">
                                        <option value="0">Reportes del dia</option>
                                        <option value="1">Reportes por fecha</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <h6>Fecha inicial</h6>
                                <div class="form-group">
                                    <!--directiva de livewire para relacionar el input con una propiedad publica-->
                                    <!--clase flatpickr de plugin para calendario personalizado-->
                                    <input type="text" wire:model="dateFrom" class="form-control flatpickr" placeholder="Click para elegir">
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <h6>Fecha final</h6>
                                <div class="form-group">
                                    <!--directiva de livewire para relacionar el input con una propiedad publica-->
                                    <!--clase flatpickr de plugin para calendario personalizado-->
                                    <input type="text" wire:model="dateTo" class="form-control flatpickr" placeholder="Click para elegir">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <!--directiva de livewire para disparar magic action $refresh-->
                                <!--$refresh para re-renderizar el componente con solo el click-->
                                <button wire:click="$refresh" class="btn btn-dark btn-block">
                                    Consultar
                                </button>
                                <!--enlace a ruta de reporte pdf concatenandole /id_usuario/tipo_reporte/fecha_ini/fecha_fin-->
                                <!--validacion en la clase para deshabilitar boton en caso de no tener detalle de venta-->
                                <!--target="_blank" para abrir pagina en blanco con el reporte-->
                                <a href="{{ url('report/pdf' . '/' . $userId . '/' . $reportRange . '/' . $reportType . '/' . $dateFrom . '/' . $dateTo) }}" 
                                class="btn btn-dark btn-block {{count($data) < 1 ? 'disabled' : ''}}" target="_blank">
                                    Generar PDF
                                </a>
                                <!--enlace a ruta de reporte excel concatenandole /id_usuario/tipo_reporte/fecha_ini/fecha_fin-->
                                <!--validacion en la clase para deshabilitar boton en caso de no tener detalle de venta-->
                                <!--target="_blank" para abrir pagina en blanco con el reporte-->
                                {{--<a href="{{ url('report/excel' . '/' . $userId . '/' . $reportRange . '/' . $reportType . '/' . $dateFrom . '/' . $dateTo) }}" 
                                class="btn btn-dark btn-block {{count($data) < 1 ? 'disabled' : ''}}" target="_blank">
                                    Exportar Excel
                                </a>--}}
                            </div>
                        </div>
                    </div>

                    @if ($reportType == 0)
                        <div class="col-sm-12 col-md-9">
                            <div class="table-responsive">  <!--tabla-->
                                <table class="table table-striped table-bordered mt-1">
                                    <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                                        <tr>
                                            <th class="table-th text-white text-center">PRODUCTO</th>
                                            <th class="table-th text-white text-center">SUCURSAL</th>
                                            <th class="table-th text-white text-center">TOTAL</th>
                                            <th class="table-th text-white text-center">CANT</th>
                                            <th class="table-th text-white text-center">UTILIDAD</th>
                                            {{--<th class="table-th text-white text-center">ESTADO</th>--}}
                                            <th class="table-th text-white text-center">USUARIO</th>
                                            <th class="table-th text-white text-center">FECHA</th>
                                            <th class="table-th text-white text-center">DETALLE</th>
                                        </tr>
                                    </thead>
                                    <tbody> <!--cuerpo de tabla-->
                                        
                                        @if(count($data) < 1)   <!--validar si no se obtienen detalles de venta-->
                                            <tr>
                                                <td colspan="7">
                                                    <h5 class="text-center text-muted">Sin resultados</h5>
                                                </td>
                                            </tr>
                                        @endif

                                        @foreach ($data as $d)  <!--iteracion de los datos almacenados en variable pasada desde controlador-->
            
                                        <tr>
                                            <td class="text-center"><h6>{{ $d->product }}</h6></td>
                                            <td class="text-center"><h6>{{ $d->office }}</h6></td>
                                            <!--funcion number_format de php para dar formato decimal recibe 2 parametros (numero,cantidad de decimales)-->
                                            <td class="text-center"><h6>${{ number_format($d->total,2) }}</h6></td>
                                            <td class="text-center"><h6>{{ $d->items }}</h6></td>
                                            <td class="text-center"><h6>{{ $d->utility }}</h6></td>
                                            {{--<td class="text-center"><h6>{{ $d->status }}</h6></td>--}}
                                            <td class="text-center"><h6>{{ $d->user }}</h6></td>
                                            <!--imprimir la fecha con formato perzonalizado-->
                                            <td class="text-center"><h6>{{\Carbon\Carbon::parse($d->created_at)->format('d-m-Y')}}</h6></td>
                                            <td class="text-center">
                                                <!--directiva click de livewire que hace llamado a metodo del componente pasandole el id-->
                                                <button wire:click.prevent="getDetails({{ $d->id }})" class="btn btn-dark btn-sm">
                                                    <i class="fas fa-list"></i>
                                                </button>
                                            </td>
                                        </tr>
            
                                        @endforeach
            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($reportType == 1)
                        <div class="col-sm-12 col-md-9">
                            <div class="table-responsive">  <!--tabla-->
                                <table class="table table-striped table-bordered mt-1">
                                    <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                                        <tr>
                                            <th class="table-th text-white text-center">PRODUCTO</th>
                                            <th class="table-th text-white text-center">SUCURSAL</th>
                                            <th class="table-th text-white text-center">TOTAL</th>
                                            <th class="table-th text-white text-center">CANT</th>
                                            {{--<th class="table-th text-white text-center">ESTADO</th>--}}
                                            <th class="table-th text-white text-center">USUARIO</th>
                                            <th class="table-th text-white text-center">FECHA</th>
                                            <th class="table-th text-white text-center">DETALLE</th>
                                        </tr>
                                    </thead>
                                    <tbody> <!--cuerpo de tabla-->
                                        
                                        @if(count($data) < 1)   <!--validar si no se obtienen detalles de venta-->
                                            <tr>
                                                <td colspan="7">
                                                    <h5 class="text-center text-muted">Sin resultados</h5>
                                                </td>
                                            </tr>
                                        @endif

                                        @foreach ($data as $d)  <!--iteracion de los datos almacenados en variable pasada desde controlador-->
            
                                        <tr>
                                            <td class="text-center"><h6>{{ $d->product }}</h6></td>
                                            <td class="text-center"><h6>{{ $d->office }}</h6></td>
                                            <!--funcion number_format de php para dar formato decimal recibe 2 parametros (numero,cantidad de decimales)-->
                                            <td class="text-center"><h6>${{ number_format($d->total,2) }}</h6></td>
                                            <td class="text-center"><h6>{{ $d->items }}</h6></td>
                                            {{--<td class="text-center"><h6>{{ $d->status }}</h6></td>--}}
                                            <td class="text-center"><h6>{{ $d->user }}</h6></td>
                                            <!--imprimir la fecha con formato perzonalizado-->
                                            <td class="text-center"><h6>{{\Carbon\Carbon::parse($d->created_at)->format('d-m-Y')}}</h6></td>
                                            <td class="text-center">
                                                <!--directiva click de livewire que hace llamado a metodo del componente pasandole el id-->
                                                <button wire:click.prevent="getDetails({{ $d->id }})" class="btn btn-dark btn-sm">
                                                    <i class="fas fa-list"></i>
                                                </button>
                                            </td>
                                        </tr>
            
                                        @endforeach
            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($reportType == 2)
                        <div class="col-sm-12 col-md-9">
                            <div class="table-responsive">  <!--tabla-->
                                <table class="table table-striped table-bordered mt-1">
                                    <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                                        <tr>
                                            <th class="table-th text-white text-center">PRODUCTO</th>
                                            <th class="table-th text-white text-center">ORIGEN</th>
                                            <th class="table-th text-white text-center">DESTINO</th>
                                            <th class="table-th text-white text-center">TOTAL</th>
                                            <th class="table-th text-white text-center">CANT</th>
                                            {{--<th class="table-th text-white text-center">ESTADO</th>--}}
                                            <th class="table-th text-white text-center">USUARIO</th>
                                            <th class="table-th text-white text-center">FECHA</th>
                                            <th class="table-th text-white text-center">DETALLE</th>
                                        </tr>
                                    </thead>
                                    <tbody> <!--cuerpo de tabla-->
                                        
                                        @if(count($data) < 1)   <!--validar si no se obtienen detalles de venta-->
                                            <tr>
                                                <td colspan="7">
                                                    <h5 class="text-center text-muted">Sin resultados</h5>
                                                </td>
                                            </tr>
                                        @endif

                                        @foreach ($data as $d)  <!--iteracion de los datos almacenados en variable pasada desde controlador-->
            
                                        <tr>
                                            <td class="text-center"><h6>{{ $d->product }}</h6></td>
                                            <!--funcion number_format de php para dar formato decimal recibe 2 parametros (numero,cantidad de decimales)-->
                                            <td class="text-center"><h6>{{ $d->from_office }}</h6></td>
                                            <td class="text-center"><h6>{{ $d->to_office }}</h6></td>
                                            <td class="text-center"><h6>${{ number_format($d->total,2) }}</h6></td>
                                            <td class="text-center"><h6>{{ $d->items }}</h6></td>
                                            {{--<td class="text-center"><h6>{{ $d->status }}</h6></td>--}}
                                            <td class="text-center"><h6>{{ $d->user }}</h6></td>
                                            <!--imprimir la fecha con formato perzonalizado-->
                                            <td class="text-center"><h6>{{\Carbon\Carbon::parse($d->created_at)->format('d-m-Y')}}</h6></td>
                                            <td class="text-center">
                                                <!--directiva click de livewire que hace llamado a metodo del componente pasandole el id-->
                                                <button wire:click.prevent="getDetails({{ $d->id }})" class="btn btn-dark btn-sm">
                                                    <i class="fas fa-list"></i>
                                                </button>
                                            </td>
                                        </tr>
            
                                        @endforeach
            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('livewire.report.sale-details')   <!--modal de detalle de venta-->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        flatpickr(document.getElementsByClassName('flatpickr'), {   //evento para calendario personalizado
            enableTime: false,
            dateFormat: 'Y-m-d',
            locale: {
                firstDayofWeek: 1,
                weekdays: {
                    shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                    longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                    ],
                },
                months: {
                    shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                    ],
                    longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                    ],
                },
            }
        })

        
        window.livewire.on('show-modal', Msg => {   //evento para mostrar modal
            $('#modalDetails').modal('show')
        })

        window.livewire.on('report-error', Msg => {   //evento para los errores del componente
            noty(Msg)
        })
    })
</script>