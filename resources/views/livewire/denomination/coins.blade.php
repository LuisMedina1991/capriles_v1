<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$pageTitle}} | {{$componentName}}</b>  <!--nombre y titulo dinamico de componente--> 
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a>
                    </li>
                </ul>
            </div>

            @include('common.searchbox')    <!--barra de busqueda-->

            <div class="widget-content">    <!--card-->
                <div class="table-responsive">  <!--tabla-->
                    <table class="table table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                            <tr>
                                <th class="table-th text-white">TIPO</th>
                                <th class="table-th text-white text-center">VALOR</th>
                                <th class="table-th text-white text-center">IMAGEN</th>
                                <th class="table-th text-white text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody> <!--cuerpo de tabla-->

                            @foreach ($coins as $coin)   <!--iteracion de los datos almacenados en variable pasada desde controlador-->

                            <tr>
                                <td><h6 class="text-uppercase">{{ $coin->type }}</h6></td>
                                <!--funcion number_format de php para dar formato decimal recibe 2 parametros (numero,cantidad de decimales)-->
                                <td><h6 class="text-center">${{ number_format($coin->value,2) }}</h6></td>
                                <td class="text-center">
                                    <span>
                                        <!--desplegar imagenes en el almacenamiento local de nuestro sistema con enlace simbolico-->
                                        <!--se obtiene el valor de la columna a traves del accesor imagen creado en el modelo-->
                                        @if($coin->image != null)
                                        <img src="{{ asset('storage/denominations/' . $coin->image->url) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                        @else
                                        <img src="{{ asset('storage/noimg.jpg') }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!--directiva click de livewire que hace llamado al metodo edit del componente pasandole el id-->
                                    <a href="javascript:void(0)" wire:click="Edit({{$coin->id}})" class="btn btn-dark mtmobile" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!--evento onclick de js hace llamado a la funcion confirm de js pasandole el id-->
                                    <a href="javascript:void(0)" onclick="Confirm('{{$coin->id}}')" class="btn btn-dark" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$coins->links()}}  <!--paginacion de laravel-->

                </div>
            </div>
        </div>
    </div>

    @include('livewire.denomination.form') <!--formulario modal-->

</div>

<!--script de eventos provenientes del backend a ser escuchados-->
<script>
    document.addEventListener('DOMContentLoaded', function(){
        
        window.livewire.on('item-added', msg=>{ //evento para agregar registro
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-updated', msg=>{   //evento para actualizar registro
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-deleted', msg=>{   //evento para eliminar registro
            noty(msg)
        });
        window.livewire.on('show-modal', msg=>{ //evento para mostral modal
            $('#theModal').modal('show')
        });
        window.livewire.on('modal-hide', msg=>{ //evento para cerrar modal
            $('#theModal').modal('hide')
        });
        
    });

    function Confirm(id){   //metodo para alerta de confirmacion que recibe el id

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
            if(result.value){   //validar si se presiono el boton de confirmacion
                window.livewire.emit('destroy', id)   //emision de evento para hacer llamado al metodo Destroy del controlador
                swal.close()    //cerrar alerta
            }
        })
    }

</script>