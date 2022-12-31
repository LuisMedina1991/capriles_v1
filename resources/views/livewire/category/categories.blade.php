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
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                            <tr>
                                <th class="table-th text-center text-white">DESCRIPCION</th>
                                <th class="table-th text-center text-white text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>     <!--cuerpo de tabla-->

                            @foreach ($categories as $category) <!--iteracion de los datos almacenados en variable pasada desde controlador-->

                            <tr>
                                <td><h6 class="text-center text-uppercase">{{ $category->name }}</h6></td>
                                <td class="text-center">
                                    <!--directiva click de livewire que hace llamado al metodo edit del componente pasandole el id-->
                                    <a href="javascript:void(0)" wire:click="Edit({{$category->id}})" class="btn btn-dark mtmobile" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!--evento onclick de js hace llamado a la funcion confirm de js pasandole el id-->
                                    <a href="javascript:void(0)" onclick="Confirm('{{$category->id}}')" class="btn btn-dark" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$categories->links()}}    <!--paginacion de laravel-->

                </div>
            </div>
        </div>
    </div>

    @include('livewire.category.form')      <!--formulario modal-->

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
            if(result.value){ //validar si se presiono el boton de confirmacion
                window.livewire.emit('destroy', id)   //emision de evento para hacer llamado al metodo Destroy del controlador
                swal.close()    //cerrar alerta
            }
        })
    }

</script>