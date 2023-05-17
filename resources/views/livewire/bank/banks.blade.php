<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="btn bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a>
                    </li>
                </ul>
            </div>

            @include('common.searchbox')

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-center text-white">nombre</th>
                                <th class="table-th text-white text-center">alias</th>
                                <th class="table-th text-white text-center">codigo de entidad</th>
                                {{--<th class="table-th text-white text-center">cuentas asociadas</th>--}}
                                <th class="table-th text-center text-white text-center">opciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($banks as $bank)

                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$bank->name}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$bank->alias}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$bank->entity_code}}</h6>
                                </td>
                                <td class="text-center">
                                    @if($search_2 == 0)
                                        <a href="javascript:void(0)" wire:click="Edit({{$bank->id}})"
                                            class="btn btn-dark" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_1('{{$bank->id}}')"
                                            class="btn btn-dark" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_2('{{$bank->id}}')"
                                            class="btn btn-dark" title="Activar">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$banks->links()}}

                </div>
            </div>
        </div>
    </div>

    @include('livewire.bank.form')

</div>


<script> 
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        window.livewire.on('record-added', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('record-updated', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('record-activated', msg=>{
            noty(msg)
        });
        window.livewire.on('record-deleted', msg=>{
            noty(msg)
        });
        window.livewire.on('record-error', msg=>{
            noty(msg,2)
        });
        $('#theModal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
    });

    function Confirm_1(id,accounts_count){

        if(accounts_count > 0){

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

                window.livewire.emit('destroy',id,accounts_count)
                swal.close()
            }
        })
    }

    function Confirm_2(id){

        swal({

            title: 'CONFIRMAR',
            text: '¿CONFIRMA ACTIVAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'

        }).then(function(result){

            if(result.value){
                
                window.livewire.emit('activate',id)
                swal.close()
            }
        })
    }

</script>