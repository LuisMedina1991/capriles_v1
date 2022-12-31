<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-center"><b>Informe de Ingresos</b></h4>
            </div>
            <div class="widget-content">    <!--card-->
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Usuario</label>
                            <!--directiva de livewire para relacionar el select con una propiedad publica-->
                            <select wire:model="userid" class="form-control">
                                <option value="0" disabled>Elegir</option>  <!--opcion por defecto-->
                                @foreach ($users as $user) <!--iteracion de los datos almacenados en variable pasada desde controlador-->
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                            @error('userid')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger">{{$message}}</span>   <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Fecha inicial</label>
                            <!--directiva de livewire para relacionar el input con una propiedad publica-->
                            <input type="date" wire:model.lazy="fromDate" class="form-control">
                            @error('fromDate')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger">{{$message}}</span>   <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Fecha final</label>
                            <!--directiva de livewire para relacionar el input con una propiedad publica-->
                            <input type="date" wire:model.lazy="toDate" class="form-control">
                            @error('toDate')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger">{{$message}}</span>   <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 align-self-center d-flex justify-content-around">
                        @if($userid > 0 && $fromDate != null && $toDate != null)    <!--validar que se selecciono un usuario,fecha inicial y fecha final-->
                            <!--directiva click de livewire que hace llamado al metodo del componente pasandole-->
                            <!--<button wire:click.prevent="Consultar" type="button" class="btn btn-dark">Consultar</button>-->
                            <button wire:click="$refresh" type="button" class="btn btn-dark">Consultar</button>
                        @endif
                        {{--
                        @if($total > 0) <!--validar que haya ventas en las fechas seleccionadas-->
                        <!--directiva click de livewire que hace llamado al metodo del componente-->
                        <button wire:click.prevent="Print()" type="button" class="btn btn-dark">Imprimir</button>
                        @endif--}}
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-sm-12 col-md-4 mbmobile">
                    <div class="connect-sorting bg-dark">
                        @if($userid > 0 && $fromDate != null && $toDate != null) 
                            <!--funcion number_format de php para dar formato decimal recibe 2 parametros (numero,cantidad de decimales)-->
                            <h5 class="text-white">Ingresos totales: ${{number_format($total, 2)}}</h5>
                            <h5 class="text-white">Articulos: {{$items}}</h5>
                        @else
                            <h5 class="text-white">Ingresos totales: $0</h5>
                            <h5 class="text-white">Articulos: 0</h5>
                        @endif
                    </div>
                </div>
                <div class="col-sm-12 col-md-8">
                    <div class="table-responsive">  <!--tabla-->
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                                <tr>
                                    <th class="table-th text-center text-white">CODIGO PRODUCTO</th>
                                    <th class="table-th text-center text-white">SUCURSAL</th>
                                    <th class="table-th text-center text-white">CANTIDAD</th>
                                    <th class="table-th text-center text-white">TOTAL</th>
                                    <th class="table-th text-center text-white">USUARIO</th>
                                    <th class="table-th text-center text-white">FECHA</th>
                                    <th class="table-th text-center text-white">DETALLE</th>
                                </tr>
                            </thead>
                            <tbody> <!--cuerpo de tabla-->
                                @if ($total <= 0)   <!--validar si existen ventas en las fechas seleccionadas-->
                                    <tr><td colspan="5"><h5 class="text-center text-muted">No hay ingresos en la fecha seleccionada</h5></td></tr>
                                @endif

                                @if($userid > 0 && $fromDate != null && $toDate != null)    <!--validar que se selecciono un usuario,fecha inicial y fecha final-->

                                    @foreach ($incomes as $income)   <!--iteracion de los datos almacenados en variable pasada desde controlador-->
                                        <tr>
                                            <td class="text-center"><h6>{{$income->product}}</h6></td>
                                            <td class="text-center"><h6>{{$income->office}}</h6></td>
                                            <td class="text-center"><h6>{{$income->items}}</h6></td>
                                            <!--funcion number_format de php para dar formato decimal recibe 2 parametros (numero,cantidad de decimales)-->
                                            <td class="text-center"><h6>${{number_format($income->total, 2)}}</h6></td>
                                            <td class="text-center"><h6>{{$income->user}}</h6></td>
                                            <td class="text-center"><h6>{{$income->created_at}}</h6></td>
                                            <td class="text-center">
                                                <!--directiva click de livewire que hace llamado al metodo del componente pasandole el id-->
                                                <button wire:click.prevent="viewDetails({{$income}})" class="btn btn-dark btn-sm">
                                                    <i class="fas fa-list"></i>
                                                </button>
                                                @can('Anular_Ingreso')    <!--aplicar permiso al boton para anular venta-->
                                                <a href="javascript:void(0)" onclick="Confirm('{{$income->id}}')" class="btn btn-dark" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.incomes.modalDetails')   <!--modal de detalle de venta-->

</div>

<!--script de eventos provenientes del backend a ser escuchados-->
<script>
    document.addEventListener('DOMContentLoaded', function(){
        
        window.livewire.on('show-modal', Msg => {   //evento para mostrar modal
            $('#modal-details').modal('show')
        });

        window.livewire.on('income-deleted', msg=>{   //evento para eliminar registro
            noty(msg,2)
        });
    });

    function Confirm(id){   //metodo para alerta de confirmacion que recibe el id

        swal({  //alerta sweetalert
            title: 'CONFIRMAR',
            text: '¿CONFIRMA ANULAR EL INGRESO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'
        }).then(function(result){
            if(result.value){ //validar si se presiono el boton de confirmacion
                window.livewire.emit('remove', id)   //emision de evento para hacer llamado al metodo Destroy del controlador
                swal.close()    //cerrar alerta
            }
        })
    }

</script>