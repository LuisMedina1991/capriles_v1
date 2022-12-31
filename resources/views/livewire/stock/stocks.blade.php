<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}} | {{$pageTitle}}</b>  <!--nombre y titulo dinamico de componente--> 
                </h4>
                <div class="form-inline">
                    <ul class="tabs tab-pills">
                        <li><h5 class="mr-5">VALOR TOTAL DE INVENTARIO: ${{ number_format($my_total,2)}}</h5></li>
                        {{--<li><a href="#" class="btn btn-dark mbmobile inblock mr-5">IMPRIMIR</a></li>--}}
                        @can('Agregar_Stock')    <!--aplicar permiso al boton-->                   
                        <li><a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a></li>
                        @endcan
                    </ul>
                </div>
            </div>
            @include('common.searchbox')    <!--barra de busqueda-->
            <div class="widget-content">    <!--card-->
                <div class="table-responsive">  <!--tabla-->
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                            <tr>
                                <th class="table-th text-white text-center">CODIGO DE PRODUCTO</th>
                                <th class="table-th text-white text-center">MARCA</th>
                                <th class="table-th text-white text-center">TRILLA</th>
                                <th class="table-th text-white text-center">LONA</th>
                                <th class="table-th text-white text-center">COSTO</th>
                                <th class="table-th text-center text-white">SUCURSAL</th>
                                <th class="table-th text-center text-white">STOCK</th>
                                <th class="table-th text-center text-white">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>     <!--cuerpo de tabla-->

                            @foreach ($stocks as $stock) <!--iteracion de los datos almacenados en variable pasada desde controlador-->

                            <tr>
                                <td><h6 class="text-center">{{ $stock->code }}</h6></td>
                                <td><h6 class="text-center">{{$stock->brand}}</h6></td>
                                <td><h6 class="text-center">{{$stock->threshing}}</h6></td>
                                <td><h6 class="text-center">{{ $stock->tarp }}</h6></td>
                                <td><h6 class="text-center">${{ $stock->cost }}</h6></td>
                                <td><h6 class="text-center">{{ $stock->office }}</h6></td>
                                <td><h6 class="text-center">{{ $stock->stock }}</h6></td>
                                <td class="text-center">
                                    @can('Traspasar_Stock')    <!--aplicar permiso al boton-->
                                    <!--directiva click de livewire que hace llamado al metodo edit del componente pasandole el id-->
                                    <a href="javascript:void(0)" wire:click="Charge({{$stock->id}})" class="btn btn-dark mtmobile" data-toggle="modal" data-target="#theModal2">
                                        <i class="fas fa-truck"></i>
                                    </a>
                                    @endcan
                                    @can('Actualizar_Stock')    <!--aplicar permiso al boton-->
                                    <!--directiva click de livewire que hace llamado al metodo edit del componente pasandole el id-->
                                    <a href="javascript:void(0)" wire:click="Edit({{$stock->id}})" class="btn btn-dark mtmobile" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('Eliminar_Stock')    <!--aplicar permiso al boton-->
                                    <!--evento onclick de js hace llamado a la funcion confirm de js pasandole el id-->
                                    <a href="javascript:void(0)" onclick="Confirm('{{$stock->product_id}}','{{$stock->office_id}}')" class="btn btn-dark" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    @endcan
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$stocks->links()}}    <!--paginacion de laravel-->

                </div>
            </div>
        </div>
    </div>

    @include('livewire.stock.form')      <!--formulario modal-->
    @include('livewire.stock.form2')      <!--formulario modal-->

</div>

<!--script de eventos provenientes del backend a ser escuchados-->
<script> 
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('show-modal', msg=>{     //evento para mostral modal
            $('#theModal').modal('show')
        });
        window.livewire.on('item-added', msg=>{     //evento para agregar registro
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-deleted', msg=>{   //evento para eliminar registro
            noty(msg)
        });
        window.livewire.on('item-updated', msg=>{   //evento para actualizar registro
            $('#theModal').modal('hide')
            noty(msg)
        });
        /*
        $('#theModal').on('hidden.bs.modal', function(e){  //evento para limpiar validaciones
            $('.er').css('display', 'none')
        });
        */
        $('#theModal').on('shown.bs.modal', function(e){    //metodo para autofocus al campo nombre
            $('.component-name').focus()
        });
        window.livewire.on('income-error', Msg => {   //evento para capturar errores al realizar venta
            noty(Msg, 2)
        })
        window.livewire.on('show-modal2', msg=>{     //evento para mostral modal
            $('#theModal2').modal('show')
        });
        window.livewire.on('item-transfered', msg=>{     //evento para agregar registro
            $('#theModal2').modal('hide')
            noty(msg)
        });
    });

    function Confirm(product,office){   //metodo para alerta de confirmacion que recibe el id

        swal({  //alerta sweetalert
            title: 'CONFIRMAR',
            text: '¿CONFIRMA ELIMINAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'
        }).then(function(result){
            if(result.value){ //validar si se presiono el boton de confirmacion
                window.livewire.emit('destroy',product,office)   //emision de evento para hacer llamado al metodo Destroy del controlador
                swal.close()    //cerrar alerta
            }
        })
    }

</script>