<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="btn bg-dark" data-toggle="modal"
                            data-target="#theModal">Agregar</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <h6><b>Filtro de busqueda</b></h6>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-gp">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text" wire:model="search" placeholder="BUSCAR..." class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <h6><b>Estado del registro</b></h6>
                    <div class="form-group">
                        <select wire:model="search_2" class="form-control">
                            <option value="0">Activo</option>
                            <option value="1">Bloqueado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">nombre</th>
                                <th class="table-th text-center text-white">alias</th>
                                <th class="table-th text-center text-white">telefono</th>
                                <th class="table-th text-center text-white">direccion</th>
                                <th class="table-th text-center text-white">opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offices as $office)
                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $office->name }}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $office->alias }}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center">{{ $office->phone }}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $office->address }}</h6>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" wire:click="Edit({{$office->id}})"
                                        class="btn btn-dark mtmobile" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{--<a href="javascript:void(0)"
                                        onclick="Confirm('{{$office->id}}','{{$office->values_count}}','{{$office->active_values_count}}')"
                                        class="btn btn-dark" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>--}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{$offices->links()}}

                </div>
            </div>
        </div>
    </div>

    @include('livewire.office.form')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        window.livewire.on('item-added', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-deleted', msg=>{
            noty(msg)
        });
        window.livewire.on('item-updated', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        $('#theModal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('item-error', msg=>{
            noty(msg,2)
        });
    });

    function Confirm(id,values_count,active_values_count){

        if(active_values_count > 0){

            swal('NO SE PUEDE ELIMINAR DEBIDO A RELACION')
            return;

        }

        swal({

            title: 'CONFIRMAR',
            text: '¿CONFIRMA ELIMINAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'

        }).then(function(result){

            if(result.value){

                window.livewire.emit('destroy', id,values_count,active_values_count)
                swal.close()
            }
        })
    }

</script>