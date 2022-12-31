<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$pageTitle}} | {{$componentName}}</b> <!--nombre y titulo dinamico de componente--> 
                </h4>
                {{--@can('Agregar_Producto')--}}    <!--aplicar permiso al boton-->
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a>
                    </li>
                </ul>
                {{--@endcan--}}
            </div>
            
            @include('common.searchbox')    <!--barra de busqueda-->

            <div class="widget-content">    <!--card-->
                <div class="table-responsive">  <!--tabla-->
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                            <tr>
                                <th class="table-th text-white text-center">DESCRIPCION</th>
                                <th class="table-th text-white text-center">CODIGO</th>
                                <th class="table-th text-white text-center">MARCA</th>
                                <th class="table-th text-white text-center">ARO</th>
                                <th class="table-th text-white text-center">TRILLA</th>
                                <th class="table-th text-white text-center">LONA</th>
                                <th class="table-th text-white text-center">COMENTARIO</th>
                                <th class="table-th text-white text-center">COSTO</th>
                                <th class="table-th text-white text-center">PRECIO</th>
                                <th class="table-th text-white text-center">CATEGORIA</th>
                                <th class="table-th text-white text-center">SUBCATEGORIA</th>
                                {{--<th class="table-th text-white text-center">IMAGEN</th>--}}
                                <th class="table-th text-white text-center">ESTADO</th>
                                <th class="table-th text-white text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody> <!--cuerpo de tabla-->
                            @foreach ($products as $product)    <!--iteracion de los datos almacenados en variable pasada desde controlador-->
                            <tr>
                                <td><h6 class="text-center text-uppercase">{{$product->prefix->name}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$product->code}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$product->brand}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$product->ring}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$product->threshing}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$product->tarp}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$product->comments}}</h6></td>
                                <td>
                                    <select wire:model="value" class="form-control">|  
                                        @foreach($product->values as $value)
                                            <option value="{{$value->id}}">${{number_format($value->cost,2)}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select wire:model="value" class="form-control" disabled>
                                        @foreach($product->values as $value)
                                            <option value="{{$value->id}}">${{number_format($value->price,2)}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><h6 class="text-center text-uppercase">{{$product->category->name}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$product->subcategory->name}}</h6></td>
                                {{--<td class="text-center">
                                    <span>
                                        <!--desplegar imagenes en el almacenamiento local de nuestro sistema con enlace simbolico-->
                                        <!--se obtiene el valor de la columna a traves del accesor imagen creado en el modelo-->
                                        <img src="{{ asset('storage/products/' . $product->image->url) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                    </span>
                                </td>--}}
                                <td><h6 class="text-center text-uppercase"><span class="badge {{$product->status->name == 'ok' ? 'badge-success' : 'badge-danger'}} text-uppercase">{{$product->status->name}}</span></h6></td>
                                <td class="text-center">
                                    {{--@can('Actualizar_Producto')    <!--aplicar permiso al boton-->--}}
                                    <!--directiva click de livewire que hace llamado al metodo edit del componente pasandole el id-->
                                    <a href="javascript:void(0)" wire:click.prevent="Edit({{$product->id}})" class="btn btn-dark mtmobile" title="Editar" data-toggle="modal" data-target="#theModal2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{--@endcan--}}
                                    {{--@can('Eliminar_Producto')    <!--aplicar permiso al boton-->--}}
                                    <!--evento onclick de js hace llamado a la funcion confirm de js pasandole el id-->
                                    <a href="javascript:void(0)" onclick="Confirm('{{$product->id}}')" class="btn btn-dark" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {{--@endcan--}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$products->links()}}  <!--paginacion de laravel-->
                </div>
            </div>
        </div>
    </div>
    @include('livewire.product.form')  <!--formulario modal-->
    {{--@include('livewire.product.form2')  <!--formulario modal-->--}}
</div>

<!--script de eventos provenientes del backend a ser escuchados-->
<script>
    document.addEventListener('DOMContentLoaded', function(){
        
        window.livewire.on('show-modal', msg=>{ //evento para mostral modal
            $('#theModal').modal('show')
        });
        window.livewire.on('show-modal2', msg=>{     //evento para mostral modal
            $('#theModal2').modal('show')
        });
        window.livewire.on('item-added', msg=>{  //evento para agregar registro
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-updated', msg=>{    //evento para actualizar registro
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-deleted', msg=>{    //evento para eliminar registro
            noty(msg)
        });
        window.livewire.on('modal-hide', msg=>{ //evento para cerrar modal
            $('#theModal').modal('hide')
        });
        $('#theModal').on('shown.bs.modal', function(e){    //metodo para autofocus al campo nombre
            $('.component-name').focus()
        });
        window.livewire.on('record-error', msg=>{    //evento para eliminar registro
            noty(msg,2)
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