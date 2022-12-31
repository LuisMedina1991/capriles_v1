<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}} | {{$pageTitle}}</b>  <!--nombre y titulo dinamico de componente--> 
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
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">  <!--encabezado de tabla-->
                            <tr>
                                <th class="table-th text-white">USUARIO</th>
                                <th class="table-th text-white text-center">TELEFONO</th>
                                <th class="table-th text-white text-center">EMAIL</th>
                                <th class="table-th text-white text-center">ESTADO</th>
                                <th class="table-th text-white text-center">PERFIL</th>
                                <th class="table-th text-white text-center">IMAGEN</th>
                                <th class="table-th text-white text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody> <!--cuerpo de tabla-->

                            @foreach ($users as $user)  <!--iteracion de los datos almacenados en variable pasada desde controlador-->
                                
                            <tr>
                                <td><h6>{{$user->name}}</h6></td>
                                <td class="text-center"><h6>{{$user->phone}}</h6></td>
                                <td class="text-center"><h6>{{$user->email}}</h6></td>
                                <td class="text-center">
                                    <!--validar status de usuario. si es active sera verde, caso contrario sera rojo-->
                                    <span class="badge {{$user->status == 'active' ? 'badge-success' : 'badge-danger'}} text-uppercase">{{$user->status}}</span>
                                </td>
                                <td class="text-center text-uppercase"><h6>{{$user->profile}}</h6></td>
                                <td class="text-center">
                                    <span>
                                        <!--desplegar imagenes en el almacenamiento local de nuestro sistema con enlace simbolico-->
                                        <!--se obtiene el valor de la columna a traves del accesor imagen creado en el modelo-->
                                        <img src="{{ asset('storage/users/' . $user->imagen) }}" alt="imagen" height="70" width="80" class="rounded">
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!--directiva click de livewire que hace llamado al metodo edit del componente pasandole el id-->
                                    <a href="javascript:void(0)" wire:click="edit({{$user->id}})" class="btn btn-dark mtmobile" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!--evento onclick de js hace llamado a la funcion confirm de js pasandole el id-->
                                    <a href="javascript:void(0)" onclick="Confirm('{{$user->id}}')" class="btn btn-dark" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    {{$users->links()}} <!--paginacion de laravel-->
                </div>
            </div>
        </div>
    </div>
    @include('livewire.user.form') <!--formulario modal-->
</div>

<!--script de eventos provenientes del backend a ser escuchados-->
<script>
    document.addEventListener('DOMContentLoaded', function(){
        
        window.livewire.on('item-added', Msg => {   //evento para agregar registro
            $('#theModal').modal('hide')
            noty(Msg)
        })

        window.livewire.on('item-updated', Msg => { //evento para actualizar registro
            $('#theModal').modal('hide')
            noty(Msg)
        })

        window.livewire.on('item-deleted', Msg => { //evento para eliminar registro
            noty(Msg)
        })

        window.livewire.on('hide-modal', Msg => {   //evento para cerrar modal
            $('#theModal').modal('hide')
        })

        window.livewire.on('show-modal', Msg => {   //evento para mostral modal
            $('#theModal').modal('show')
        })

        window.livewire.on('user-with-sales', Msg => {  //evento para notificar que usuario tiene ventas asociadas
            noty(Msg)
        })
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
            if(result.value){   //validar si se presiono el boton de confirmacion
                window.livewire.emit('destroy', id)   //emision de evento para hacer llamado al metodo Destroy del controlador
                swal.close()    //cerrar alerta
            }
        })
    }

</script>