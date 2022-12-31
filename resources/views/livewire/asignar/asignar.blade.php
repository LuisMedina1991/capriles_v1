<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}}</b>   <!--nombre dinamico de componente--> 
                </h4>
            </div>
            <div class="widget-content">    <!--card-->
                <div class="form-inline">
                    <div class="form-group mr-5">
                        <!--directiva de livewire para relacionar el select con una propiedad publica-->
                        <select wire:model="role" class="form-control">
                            <option value="Elegir" selected>== Seleccione el Rol ==</option>    <!--opcion por defecto-->
                            @foreach ($roles as $role)  <!--iteracion de los datos almacenados en variable pasada desde controlador-->
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--directiva click de livewire que hace llamado al metodo del componente-->
                    <button wire:click.prevent="SyncAll()" type="button" class="btn btn-dark mbmobile inblock mr-5">Sincronizar Todos</button>
                    <!--evento onclick de js hace llamado a la funcion js-->
                    <button onclick="Revocar()" type="button" class="btn btn-dark mbmobile mr-5">Revocar Todos</button>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #3B3F5C">
                                    <tr>
                                        <th class="table-th text-white text-center">ID</th>
                                        <th class="table-th text-white text-center">PERMISO</th>
                                        <th class="table-th text-white text-center">ROLES CON EL PERMISO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permisos as $permiso)    <!--iteracion de los datos almacenados en variable pasada desde controlador-->
                                    <tr>
                                        <td><h6 class="text-center">{{$permiso->id}}</h6></td>
                                        <td class="text-center">
                                            <div class="n-check">
                                                <label class="new-control new-checkbox checkbox-primary">
                                                    <!--directiva de livewire con el evento change que hace llamado al metodo del controlador-->
                                                    <!--metodo SyncPermiso recibe (id_permiso, verificacion, nombre_permiso)-->
                                                    <!--metod .is de jquery recibe (propiedad para verificar si esta activo)-->
                                                    <input 
                                                    type="checkbox" wire:change="SyncPermiso($('#p' + {{ $permiso->id }}).is(':checked'), '{{ $permiso->name }}' )" 
                                                    id="p{{ $permiso->id }}" value="{{ $permiso->id }}" class="new-control-input" {{ $permiso->checked == 1 ? 'checked' : '' }}
                                                    >
                                                    <span class="new-control-indicator"></span>
                                                    <h6>{{ $permiso->name }}</h6>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <!--obteniendo conteo de roles relacionados con el permiso-->
                                            <h6>{{ \App\Models\User::permission($permiso->name)->count() }}</h6>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$permisos->links()}}  <!--paginacion de laravel-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--script de eventos provenientes del backend a ser escuchados-->
<script>
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('sync-error', Msg => {   //evento para los errores del componente
            noty(Msg,2)
        })

        window.livewire.on('permi', Msg => {    //evento
            noty(Msg)
        })

        window.livewire.on('syncall', Msg => {  //evento para asignar todos los permisos a un rol
            noty(Msg)
        })

        window.livewire.on('removeall', Msg => {    //evento para quitar todos los permisos a un rol
            noty(Msg)
        })
    });

    function Revocar(){   //metodo para alerta de confirmacion

        swal({  //alerta sweetalert
            title: 'CONFIRMAR',
            text: '¿CONFIRMA REVOCAR TODOS LOS PERMISOS?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'
        }).then(function(result){
            if(result.value){   //validar si se presiono el boton de confirmacion
                window.livewire.emit('revokeall')   //emision de evento para hacer llamado al metodo del controlador
                swal.close()    //cerrar alerta
            }
        })
    }

</script>